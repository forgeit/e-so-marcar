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
        
    }

}
