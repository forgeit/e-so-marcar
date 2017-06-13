<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends MY_Controller {

    public function count() {
        $quadras = $this->QuadraModel->countPorColuna();
        $jogos = $this->ReservaModel->countPorColuna();
        $jogadores = $this->UsuarioModel->countPorColuna();


        $array = array('data' =>
            array('dto' => array($quadras, $jogos, $jogadores)));

        print_r(json_encode($array));
    }

    public function newsletter() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $newsletter = json_decode($data);

        if (empty($newsletter->email)) {
            $this->gerarErro("E-mail é obrigatório.");
        }

        $id = $this->NewsletterModel->inserirRetornaId($newsletter);

        if ($id) {
            $hash = md5($newsletter->email . 'newsletter-esomarcar');

            $this->email
                    ->from($this->config->item('smtp_user'))
                    ->to($newsletter->email)
                    ->subject('Confirmação de e-mail')
                    ->message('<p>Para confirmar seu cadastro clique <a href="' . $this->config->item('base_url') . '/server/home/ativar/newsletter/' . $id . '/hash/' . $hash . '">aqui</a>.</p>')
                    ->send();
        }

        $array = $this->gerarRetorno(TRUE, "Obrigado! Seu e-mail foi armazenado em nossa lista.");
        print_r(json_encode($array));
    }

    public function cadastrar() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $usuario = json_decode($data);

        $this->validaDados($usuario);

        $usuario->data_cadastro = date('Y-m-d');
        $usuario->flag_email_confirmado = 0;
        $usuario->senha = md5($usuario->senha);
        unset($usuario->senha_denovo);

        $id = $this->UsuarioModel->inserirRetornaId($usuario);

        if ($id) {
            $hash = md5($usuario->email . 'cadastro-esomarcar');

            $this->email
                    ->from($this->config->item('smtp_user'))
                    ->to($usuario->email)
                    ->subject('Validação de Conta')
                    ->message('<p>Para ativar sua conta clique <a href="' . $this->config->item('base_url') . '/server/home/ativar/cadastro/' . $id . '/hash/' . $hash . '">aqui</a>.</p>')
                    ->send();
        }

        $array = $this->gerarRetorno(TRUE, "Sucesso ao salvar o registro.");
        print_r(json_encode($array));
    }

    public function logar() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $usuario = json_decode($data);

        $this->load->library("JWT");

        if (!isset($usuario->login) || !isset($usuario->senha)) {
            print_r(json_encode($this->gerarRetorno(FALSE, "Email e senha são obrigatórios.")));
            die();
        }

        $usuario = $this->UsuarioModel->verificarLogin($usuario->login, md5($usuario->senha));
        
        if(!$usuario['flag_email_confirmado']) {    
            print_r(json_encode($this->gerarRetorno(FALSE, "Você deve confirmar sua conta antes de acessar o sistema.")));
            die();
        }
        
        if ($usuario) {
            $array = $this->gerarRetorno(TRUE, "Bem-vindo, ao É Só Marcar.");
            $array['data'] = array('token' => $this->generate_token($usuario[0]));

            print_r(json_encode($array));
            die();
        } else {
            print_r(json_encode($this->gerarRetorno(FALSE, "Dados informados são inválidos.")));
            die();
        }
    }

    public function generate_token($usuario) {
        //$this->load->library("JWT");
        $CONSUMER_SECRET = 'sistema_mathias_2016';
        $CONSUMER_TTL = 28800;
        return $this->jwt->encode(array(
                    'id' => $usuario['id'],
                    'nome' => $usuario['nome'],
                    'email' => $usuario['email'],
                    'issuedAt' => date(DATE_ISO8601, strtotime("now")),
                    'dtBegin' => strtotime("now"),
                    'ttl' => $CONSUMER_TTL
                        ), $CONSUMER_SECRET);
    }

    public function ativar() {

        //ativa newsletter
        if ($this->uri->segment(3) == 'newsletter' && $this->uri->segment(4) && $this->uri->segment(5) == 'hash' && $this->uri->segment(6)) {

            $newsletter = $this->NewsletterModel->buscarPorId($this->uri->segment(4));
            $hash = hash('md5', $newsletter['email'] . 'newsletter-esomarcar');

            if ($hash == $this->uri->segment(6)) {
                $newsletter['flag_email_confirmado'] = 1;
                $this->NewsletterModel->atualizar($newsletter['id'], $newsletter);
                header("location:" . $this->config->item('base_url'));
                die();
            } else {
                print_r('Hash inválido!');
            }
        }

        //ativa cadastro
        if ($this->uri->segment(3) == 'cadastro' && $this->uri->segment(4) && $this->uri->segment(5) == 'hash' && $this->uri->segment(6)) {

            $usuario = $this->UsuarioModel->buscarPorId($this->uri->segment(4));
            $hash = hash('md5', $usuario['email'] . 'cadastro-esomarcar');

            if ($hash == $this->uri->segment(6)) {
                $usuario['flag_email_confirmado'] = 1;
                $this->UsuarioModel->atualizar($usuario['id'], $usuario);
                header("location:" . $this->config->item('base_url'));
                die();
            } else {
                print_r('Hash inválido!');
            }
        }
    }

    private function validaDados($usuario) {
        if (empty($usuario->email)) {
            $this->gerarErro("E-mail é obrigatório.");
        }

        $emailUnico = $this->UsuarioModel->buscarPorColuna('email', $usuario->email);
        if ($emailUnico != null) {
            $this->gerarErro("E-mail já cadastrado.");
        }

        if (empty($usuario->senha)) {
            $this->gerarErro("Senha é obrigatório.");
        }

        if (empty($usuario->senha_denovo)) {
            $this->gerarErro("Repetir Senha é obrigatórios.");
        }

        if ($usuario->senha != $usuario->senha_denovo) {
            $this->gerarErro("As senhas são diferentes.");
        }
    }

}
