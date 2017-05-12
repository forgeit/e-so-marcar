<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class DiaSemana extends MY_Controller {

    public function atualizar() {
        
    }

    public function buscar() {
        
    }

    public function buscarCombo() {
        print_r(json_encode(array('data' => array('ArrayList' => $this->DiaSemanaModel->buscarTodos('id')))));
    }

    public function excluir() {
        
    }

    public function salvar() {
        
    }

}
