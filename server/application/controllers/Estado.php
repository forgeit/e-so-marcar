<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Estado extends MY_Controller {

    public function atualizar() {
        
    }

    public function buscar() {
        
    }

    public function buscarTodos() {
        print_r(json_encode(array('data' => array('ArrayList' => $this->EstadoModel->buscarTodos('nome')))));
    }

    public function excluir() {
        
    }

    public function salvar() {
        
    }

}
