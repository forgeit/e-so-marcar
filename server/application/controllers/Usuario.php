<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuario extends MY_Controller {

    public function buscar() {
        $array = array('data' =>
            array('dto' =>
                $this->UsuarioModel->buscarPorIdNativo($this->jwtController->id)));

        print_r(json_encode($array));
    }

    public function buscarCombo() {
        print_r(json_encode(array('data' => array('ArrayList' => $this->UsuarioModel->buscarTodos('email')))));
    }

    public function excluir() {
        
    }

    public function salvar() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $usuario = json_decode($data);

        $this->validaDados($usuario);

        $usuario->data_cadastro = date('Y-m-d');
        $usuario->flag_email_confirmado = 0;
        unset($usuario->senha_denovo);

        $id = $this->UsuarioModel->inserirRetornaId($usuario);

        if ($id) {
            $hash = hash('md5', $usuario->email . 'cadastro-esomarcar');

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

    public function atualizar() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $usuario = json_decode($data);

        if (empty($usuario->nome)) {
            $this->gerarErro("Nome é obrigatório.");
        }

        $usuarioBanco = $this->UsuarioModel->buscarPorId($this->jwtController->id);

        $usuarioBanco['nome'] = $usuario->nome;
        $usuarioBanco['telefone'] = $usuario->telefone;
        $usuarioBanco['biogradia'] = $usuario->biogradia;
        if (empty($usuario->data_nascimento)) {
            $usuarioBanco['data_nascimento'] = NULL;
        } else {
            $usuarioBanco['data_nascimento'] = $this->toDate($usuario->data_nascimento);
        }

        $this->UsuarioModel->atualizar($this->jwtController->id, $usuarioBanco);

        $array = $this->gerarRetorno(TRUE, "Dados atualizados com sucesso.");
        print_r(json_encode($array));
    }

    public function alterarSenha() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $senhas = json_decode($data);

        if (empty($senhas->atual)) {
            $this->gerarErro("Senha atual é obrigatório.");
        }

        if (empty($senhas->nova)) {
            $this->gerarErro("Nova senha é obrigatório.");
        }

        if (empty($senhas->nova2)) {
            $this->gerarErro("Repetir nova senha é obrigatório.");
        }

        if ($senhas->nova2 != $senhas->nova) {
            $this->gerarErro("Novas senhas são diferentes.");
        }

        $usuarioBanco = $this->UsuarioModel->buscarPorId($this->jwtController->id);

        if ($usuarioBanco['senha'] != md5($senhas->atual)) {
            $this->gerarErro("Senha atual incorreta.");
        }

        $usuarioBanco['senha'] = md5($senhas->nova);

        $this->UsuarioModel->atualizar($this->jwtController->id, $usuarioBanco);

        $array = $this->gerarRetorno(TRUE, "Senha atualizada com sucesso.");
        print_r(json_encode($array));
    }

}
