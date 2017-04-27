<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class EsporteTipo extends MY_Controller {

    public function atualizar() {
        
    }

    public function buscar() {
        
    }

    public function buscarTodos() {
        print_r(json_encode(array('data' => array('ArrayList' => $this->EsporteTipoModel->buscarTodos('nome')))));
    }

    public function excluir() {
        
    }

    public function salvar() {
        
    }

}
