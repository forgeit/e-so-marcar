<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cidade extends MY_Controller {

    public function atualizar() {
        
    }

    public function buscar() {
        print_r(json_encode(array('data' => array('ArrayList' => $this->CidadeModel->buscarPorColuna('id_uf', $this->uri->segment(3))))));
    }

    public function buscarTodos() {
        ///print_r(json_encode(array('data' => array('ArrayList' => $this->CidadeModel->buscarTodos('nome')))));
    }

    public function excluir() {
        
    }

    public function salvar() {
        
    }

}
