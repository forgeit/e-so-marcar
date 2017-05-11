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

        $this->validaDados($usuario);

        $usuario->data_cadastro = date('Y-m-d');
        $usuario->flag_email_confirmado = 0;

        $response = array('exec' => $this->UsuarioModel->inserir($usuario));

        $array = $this->gerarRetorno($response, $response ? "Sucesso ao salvar o registro." : "Erro ao salvar o registro.");
        print_r(json_encode($array));
    }

    private function validaDados($usuario) {
        if (empty($usuario->email)) {
            $this->gerarErro("E-mail é obrigatório.");
        }

        if (empty($usuario->senha)) {
            $this->gerarErro("Senha é obrigatório.");
        }

        if (empty($usuario->senha_denovo)) {
            $this->gerarErro("Repetir Senha é obrigatórios.");
        }
        
        if ($usuario->senha != $usuario->senha_denovo) {
            $this->gerarErro("As senhas digitas estão diferentes.");
        }
    }

}
