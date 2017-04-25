<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dados extends MY_Controller {

    public function buscar() {
        $array = array('data' =>
            array('dto' => $this->DadosModel->buscarPorId(1)));

        print_r(json_encode($array));
    }

    public function alterarSenha() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $usuario = json_decode($data);

        $usuarioAtual = $this->UsuarioModel->buscarPorId($this->uri->segment(3), 'id_usuario');

        if ($usuarioAtual['senha'] !== md5($usuario->senha)) {
            print_r(json_encode($this->gerarRetorno(FALSE, "A senha atual está errada.")));
            die();
        }

        if ($usuario->novaSenha !== $usuario->confirmacao) {
            print_r(json_encode($this->gerarRetorno(FALSE, "A nova senha deve ser igual a confirmação.")));
            die();
        }

        $usuarioAtual['senha'] = md5($usuario->novaSenha);

        if ($this->UsuarioModel->atualizar($this->uri->segment(3), $usuarioAtual, 'id_usuario')) {
            print_r(json_encode($this->gerarRetorno(TRUE, "Sucesso ao alterar a senha.")));
            die();
        } else {
            print_r(json_encode($this->gerarRetorno(FALSE, "Ocorreu um erro ao alterar a senha.")));
            die();
        }
    }

    private function gerarRetorno($response, $mensagem) {
        $message = array();
        $message[] = $response == TRUE ?
                array('tipo' => 'success', 'mensagem' => $mensagem) :
                array('tipo' => 'error', 'mensagem' => $mensagem);

        $array = array(
            'message' => $message,
            'status' => $response == TRUE ? 'true' : 'false'
        );

        return $array;
    }

}
