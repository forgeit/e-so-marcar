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
            $hash = hash('md5', $newsletter->email . 'newsletter-esomarcar');

            $this->email
                    ->from($this->config->item('smtp_user'))
                    ->to($newsletter->email)
                    ->subject('Confirmação de e-mail')
                    ->message('<p>Para confirmar seu cadastro clique <a href="' . $this->config->item('base_url') . 'home/ativar/newsletter/' . $id . '/hash/' . $hash . '">aqui</a>.</p>')
                    ->send();
        }

        $array = $this->gerarRetorno("Obrigado! Seu e-mail foi armazenado em nossa lista.");
        print_r(json_encode($array));
    }

    public function ativar() {

        //ativa newsletter
        if ($this->uri->segment(3) == 'newsletter' && $this->uri->segment(4) && $this->uri->segment(5) == 'hash' && $this->uri->segment(6)) {

            $newsletter = $this->NewsletterModel->buscarPorId($this->uri->segment(4));
            $hash = hash('md5', $newsletter->email . 'newsletter-esomarcar');

            if ($hash == $this->uri->segment(4)) {
                $newsletter['flag_email_confirmado'] = 1;
                $this->NewsletterModel->atualizar($newsletter);
                print_r('Newsletter ativado!');
            } else {
                print_r('Hash inválido!');
            }
        }

        //ativa cadastro
        if ($this->uri->segment(3) == 'cadastro' && $this->uri->segment(4) && $this->uri->segment(5) == 'hash' && $this->uri->segment(6)) {

            $newsletter = $this->UsuarioModel->buscarPorId($this->uri->segment(4));
            $hash = hash('md5', $newsletter->email . 'cadastro-esomarcar');

            if ($hash == $this->uri->segment(4)) {
                $newsletter['flag_email_confirmado'] = 1;
                $this->UsuarioModel->atualizar($newsletter);
                print_r('Cadastro ativado!');
            } else {
                print_r('Hash inválido!');
            }
        }
    }

}
