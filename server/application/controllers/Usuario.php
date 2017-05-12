<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends MY_Controller {

    public function atualizar() {
        
    }

    public function buscar() {
        
    }

    public function buscarCombo() {
        print_r(json_encode(array('data' => array('ArrayList' => $this->UsuarioModel->buscarTodos('nome')))));
    }

    public function excluir() {
        
    }

    public function salvar() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $usuario = json_decode($data);

        //$this->validaDados($usuario);

        $usuario->data_cadastro = date('Y-m-d');
        $usuario->flag_email_confirmado = 0;
        unset($usuario->senha_denovo);

        //$response = array('exec' => $this->UsuarioModel->inserir($usuario));

        //if ($response) {
            $hash = 'AaueofhaE0h203480ihAEFOho34089h0';

            $this->email
                    ->from('pogo01acc@gmail.com')
                    ->to('charles.a.goettert@gmail.com')
                    ->subject('Validação de Conta')
                    ->message('<p>Para ativar sua conta clique <a href="' . BASEPATH . '/server/newsletter/validar/' . $hash . '">aqui</a>.</p>')
                    ->send();
        //}

        //$array = $this->gerarRetorno($response, $response ? "Sucesso ao salvar o registro." : "Erro ao salvar o registro.");
        //print_r(json_encode($array));
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
