<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Publico extends MY_Controller {

    public function buscar() {

        $cliente = $this->ClienteModel->buscarPorId($this->uri->segment(3));

        $array = array('data' =>
            array('dto' => $cliente));

        print_r(json_encode($array));
    }

    public function buscarTodos() {
        print_r(json_encode(array('data' => array('ArrayList' => $this->ClienteModel->buscarTodos('nome_fantasia')))));
    }

    public function buscarQuadra() {

        $quadra = $this->QuadraModel->buscarPorId($this->uri->segment(3));
        $quadra['esportes'] = $this->QuadraEsporteModel->buscarEsportes($quadra['id']);

        $array = array('data' =>
            array('dto' => $quadra));

        print_r(json_encode($array));
    }

    public function buscarTodosNativo() {
        print_r(json_encode(array('data' => array('ArrayList' => $this->QuadraModel->buscarTodosNativo($this->uri->segment(3))))));
    }
    
    public function buscarReservas() {
        print_r(json_encode(array('data' => array('ArrayList' => $this->HorarioModel->buscarReservas($this->uri->segment(3))))));
    }

}
