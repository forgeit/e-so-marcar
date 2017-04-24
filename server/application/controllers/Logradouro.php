<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logradouro extends MY_Controller {

	public function filtrar() {	
		$data = $this->security->xss_clean($this->input->raw_input_stream);
		$logradouro = json_decode($data);

		$lista = $this->LogradouroModel->buscarPorDescricaoBairro($logradouro->filtro, $logradouro->bairro);
		print_r(json_encode(array('data' => array ('ArrayList' => $lista ? $lista : array()))));
	}

	public function salvar() {	
		$data = $this->security->xss_clean($this->input->raw_input_stream);
		$logradouro = json_decode($data);
		$logradouroModel = array();

		if ($logradouro->nome) {
			$logradouroModel['nome'] = ucwords($logradouro->nome);
		} else {
			print_r(json_encode($this->gerarRetorno(FALSE, "O campo nome é obrigatório.")));
			die();
		}

		if ($logradouro->bairro) {
			$logradouroModel['id_bairro'] = $logradouro->bairro;
		} else {
			print_r(json_encode($this->gerarRetorno(FALSE, "O campo bairro é obrigatório.")));
			die();
		}

		$dados = $this->LogradouroModel->buscarPorNomeBairro($logradouroModel['nome'], $logradouroModel['id_bairro']);

		if ($dados) {
			print_r(json_encode($this->gerarRetorno(FALSE, "O logradouro informado já está registrado no sistema.")));
			die();	
		}
		
		$id = $this->LogradouroModel->inserirRetornaId($logradouroModel);

		if ($id) {
			$retorno = $this->gerarRetorno(TRUE, "Sucesso ao adicionar o logradouro.");

			$retorno['id'] = $id;

			print_r(json_encode($retorno));
			die();
		} else {
			print_r(json_encode($this->gerarRetorno(FALSE, "Ocorreu um erro ao registrar no sistema.")));
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