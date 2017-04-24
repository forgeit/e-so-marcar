<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Endereco extends MY_Controller {
	
	public function cidade() {
		print_r(json_encode(array('data' => array('ArrayList' => array($this->CidadeModel->buscarPorId(8733, 'id_cidade'))))));
	}

	public function bairro() {
		print_r(json_encode(array('data' => array('ArrayList' => $this->BairroModel->buscarTodosPorCidade($this->uri->segment(3))))));
	}

	public function logradouro() {
		print_r(json_encode(array('data' => array('ArrayList' => $this->LogradouroModel->buscarTodosPorBairro($this->uri->segment(3))))));
	}
	
}