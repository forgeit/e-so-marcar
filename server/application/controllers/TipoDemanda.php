<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TipoDemanda extends MY_Controller {
	public function buscar() {
		$array = array('data' => 
			array('TipoDemandaDto' => $this->TipoDemandaModel->buscarPorId($this->uri->segment(2), 'id_tipo_demanda')));
		print_r(json_encode($array));
	}

	public function buscarTodos() {
		$lista = $this->TipoDemandaModel->buscarTodos('descricao');
		print_r(json_encode(array('data' => array ('datatables' => $lista ? $lista : array()))));
	}

	public function excluir() {

		$dados = $this->DemandaModel->buscarPorTipoDemanda($this->uri->segment(3));

		if (count($dados) > 0) {
			print_r(json_encode($this->gerarRetorno(FALSE, "O tipo de demanda não pode ser removido, o mesmo está sendo utilizado em alguma demanda.")));
			die();
		}
		
		$response = $this->TipoDemandaModel->excluir($this->uri->segment(3), 'id_tipo_demanda');

		$message = array();
		$message[] = $response == TRUE ? 
			array('tipo' => 'success', 'mensagem' => 'Sucesso ao remover o registro.') : 
			array('tipo' => 'error', 'mensagem' => 'Erro ao remover o registro.');

		$array = array(
			'message' => $message,
			'status' => $response == TRUE ? 'true' : 'false'
		);

		print_r(json_encode($array));
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

	public function salvar() {
		$data = $this->security->xss_clean($this->input->raw_input_stream);
		$model = json_decode($data);

		$tipoDemandaModel = array();

		$atualizar = 0;

		if ($this->uri->segment(3)) {
			$atualizar = $this->uri->segment(3);
			$tipoDemandaModel['id_tipo_demanda'] = $atualizar;
		}

		if (!isset($model->descricao)) {
			$array = $this->gerarRetorno(FALSE, "O campo descrição é obrigatório.");
			print_r(json_encode($array));
			die();
		} else {
			$tipoDemandaModel['descricao'] = $model->descricao;
		}

		if ($atualizar === 0) {
			if ($this->TipoDemandaModel->buscarPorDescricao($tipoDemandaModel['descricao'])[0]['total'] > 0) {	
				$array = $this->gerarRetorno(FALSE, "O valor informado já está registrado no banco de dados.");
				print_r(json_encode($array));
				die();
			}

			$response = array('exec' => $this->TipoDemandaModel->inserir($tipoDemandaModel));
			$array = $this->gerarRetorno($response, $response ? "Sucesso ao salvar o registro." : "Erro ao salvar o registro.");
		} else {
			if ($this->TipoDemandaModel->buscarPorDescricao($tipoDemandaModel['descricao'], $atualizar)[0]['total'] > 0) {
				print_r(json_encode($this->gerarRetorno(FALSE, "O valor informado já está registrado no banco de dados.")));
				die();
			}

			$response = array('exec' => $this->TipoDemandaModel->atualizar($tipoDemandaModel['id_tipo_demanda'], $tipoDemandaModel, 'id_tipo_demanda'));
			$array = $this->gerarRetorno($response, $response ? "Sucesso ao atualizar o registro." : "Erro ao atualizar o registro.");
		}

		print_r(json_encode($array));
	}
	
}