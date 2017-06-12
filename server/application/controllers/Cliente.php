<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente extends MY_Controller {

    public function atualizar() {
        
    }

    public function buscar() {

        $cliente = $this->ClienteModel->buscarPorId($this->uri->segment(3));

        $array = array('data' =>
            array('dto' => $cliente));

        print_r(json_encode($array));
    }

    public function buscarTodos() {
        print_r(json_encode(array('data' => array('ArrayList' => $this->ClienteModel->buscarTodos('nome_fantasia')))));
    }

    public function excluir() {
        
    }

    public function salvar() {
        
    }

}
