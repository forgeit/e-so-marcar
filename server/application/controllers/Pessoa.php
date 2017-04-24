<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pessoa extends MY_Controller {

	public function atualizar() {
		$data = $this->security->xss_clean($this->input->raw_input_stream);
		$pessoa = json_decode($data);
		$pessoaModel = array();

		$pessoaModel['id_pessoa'] = $this->uri->segment(3);

		if (isset($pessoa->nome)) {		
			if (!trim($pessoa->nome)) {
				print_r(json_encode($this->gerarRetorno(FALSE, "O campo nome é obrigatório.")));
				die();
			} else {
				$pessoaModel['nome'] = $pessoa->nome;
			}
		} else {
			print_r(json_encode($this->gerarRetorno(FALSE, "O campo nome é obrigatório.")));
			die();
		}

		$pessoaModel['email'] = null;

		if (isset($pessoa->email)) {		
			if ($pessoa->email) {
				$pessoaModel['email'] = $pessoa->email;

				if ($this->PessoaModel->buscarPorEmailId($pessoaModel['email'], $pessoaModel['id_pessoa'])) {
					print_r(json_encode($this->gerarRetorno(FALSE, "O e-mail informado já está registrado.")));
					die();
				}
			}
		}

		if (isset($pessoa->tipoPessoa)) {
			if (!trim($pessoa->tipoPessoa)) {
				print_r(json_encode($this->gerarRetorno(FALSE, "O campo tipo pessoa é obrigatório.")));
				die();
			} else {
				$pessoaModel['id_tipo_pessoa'] = $pessoa->tipoPessoa;
			}
		} else {
			print_r(json_encode($this->gerarRetorno(FALSE, "O campo tipo pessoa é obrigatório.")));
			die();		
		}

		if (isset($pessoa->fgTipoPessoa)) {
			if (!trim($pessoa->fgTipoPessoa)) {
				print_r(json_encode($this->gerarRetorno(FALSE, "O campo tipo é obrigatório.")));
				die();
			} else {
				$pessoaModel['fg_tipo_pessoa'] = $pessoa->fgTipoPessoa === "F" ? true : false;
			}
		} else {
			print_r(json_encode($this->gerarRetorno(FALSE, "O campo tipo pessoa é obrigatório.")));
			die();		
		}

		$pessoaModel['cpf_cnpj'] = null;

		if ($pessoa->fgTipoPessoa === "J") {
			if (isset($pessoa->cnpj)) {
				if ($pessoa->cnpj) {
					$pessoaModel['cpf_cnpj'] = $pessoa->cnpj;
				}
			}
		} else {
			if (isset($pessoa->cpf)) {
				if ($pessoa->cpf) {
					$pessoaModel['cpf_cnpj'] = $pessoa->cpf;
				}
			}
		}

		$pessoaModel['telefone'] = null;

		if (isset($pessoa->telefone)) {
			if ($pessoa->telefone) {
				$pessoaModel['telefone'] = $pessoa->telefone;
			}
		}

		$pessoaModel['numero'] = null;

		if (isset($pessoa->numero)) {
			if ($pessoa->numero) {
				$pessoaModel['numero'] = $pessoa->numero;
			}
		}

		$pessoaModel['celular'] = null;

		if (isset($pessoa->celular)) {
			if ($pessoa->celular) {
				$pessoaModel['celular'] = $pessoa->celular;
			}
		}

		$pessoaModel['observacao'] = null;

		if (isset($pessoa->observacao)) {
			if ($pessoa->observacao) {
				$pessoaModel['observacao'] = $pessoa->observacao;	
			}
		}

		$pessoaModel['id_logradouro'] = null;

		if (isset($pessoa->logradouro)) {
			if ($pessoa->logradouro) {
				$pessoaModel['id_logradouro'] = $pessoa->logradouro;
			}
		}

		$pessoaModel['id_bairro'] = null;

		if (isset($pessoa->bairro)) {
			if ($pessoa->bairro) {
				$pessoaModel['id_bairro'] = $pessoa->bairro;
			}
		}

		$pessoaModel['id_cidade'] = null;

		if (isset($pessoa->cidade)) {
			if ($pessoa->cidade) {
				$pessoaModel['id_cidade'] = $pessoa->cidade;
			}
		}

		$response = array('exec' => $this->PessoaModel->atualizar($pessoaModel['id_pessoa'], $pessoaModel, 'id_pessoa'));

		$array = $this->gerarRetorno($response, $response ? "Sucesso ao atualizar o registro." : "Erro ao atualizar o registro.");

		print_r(json_encode($array));
	}

	public function buscar() {

		$array = array('data' => 
			array('PessoaDto' => $this->PessoaModel->buscarPorId($this->uri->segment(2), 'id_pessoa')));

		print_r(json_encode($array));
	}

	public function buscarTodos() {

		if ($this->uri->segment(2) == 'tipo-pessoa' && $this->uri->segment(3)) {
			$lista = $this->PessoaModel->buscarTodosNativo($this->uri->segment(3));
		} else {
			$lista = $this->PessoaModel->buscarTodosNativo();
		}

		print_r(json_encode(array('data' => array ('datatables' => $lista ? $lista : array()))));
	}

	public function buscarCombo() {
		$lista = $this->PessoaModel->buscarCombo();
		print_r(json_encode(array('data' => array ('ArrayList' => $lista ? $lista : array()))));
	}

	public function buscarComboFiltro() {
		$data = $this->security->xss_clean($this->input->raw_input_stream);
		$filtro = json_decode($data);

		$lista = $this->PessoaModel->buscarComboFiltro($filtro->filtro);
		print_r(json_encode(array('data' => array ('ArrayList' => $lista ? $lista : array()))));
	}

	public function excluir() {

		$dados = $this->DemandaModel->buscarPorPessoa($this->uri->segment(3));

		if (count($dados) > 0) {
			print_r(json_encode($this->gerarRetorno(FALSE, "Não é possível remover a pessoa, a mesma possui demandas no sistema.")));
			die();		
		}

		$dados = $this->DemandaFluxoModel->buscarPorPessoa($this->uri->segment(3));

		if (count($dados) > 0) {
			print_r(json_encode($this->gerarRetorno(FALSE, "Não é possível remover a pessoa, a mesma faz parte do fluxo de demandas no sistema.")));
			die();		
		}

		$response = $this->PessoaModel->excluir($this->uri->segment(3), 'id_pessoa');

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
		$pessoa = json_decode($data);
		$pessoaModel = array();

		if (isset($pessoa->nome)) {		
			if (!trim($pessoa->nome)) {
				print_r(json_encode($this->gerarRetorno(FALSE, "O campo nome é obrigatório.")));
				die();
			} else {
				$pessoaModel['nome'] = $pessoa->nome;
			}
		} else {
			print_r(json_encode($this->gerarRetorno(FALSE, "O campo nome é obrigatório.")));
			die();
		}

		$pessoaModel['email'] = null;

		if (isset($pessoa->email)) {		
			if ($pessoa->email) {
				$pessoaModel['email'] = $pessoa->email;

				if ($this->PessoaModel->buscarPorEmail($pessoaModel['email'])) {
					print_r(json_encode($this->gerarRetorno(FALSE, "O e-mail informado já está registrado.")));
					die();
				}
			}
		}

		if (isset($pessoa->tipoPessoa)) {
			if (!trim($pessoa->tipoPessoa)) {
				print_r(json_encode($this->gerarRetorno(FALSE, "O campo tipo pessoa é obrigatório.")));
				die();
			} else {
				$pessoaModel['id_tipo_pessoa'] = $pessoa->tipoPessoa;
			}
		} else {
			print_r(json_encode($this->gerarRetorno(FALSE, "O campo tipo pessoa é obrigatório.")));
			die();		
		}

		if (isset($pessoa->fgTipoPessoa)) {
			if (!trim($pessoa->fgTipoPessoa)) {
				print_r(json_encode($this->gerarRetorno(FALSE, "O campo tipo é obrigatório.")));
				die();
			} else {
				$pessoaModel['fg_tipo_pessoa'] = $pessoa->fgTipoPessoa === "F" ? true : false;
			}
		} else {
			print_r(json_encode($this->gerarRetorno(FALSE, "O campo tipo pessoa é obrigatório.")));
			die();		
		}

		if ($pessoa->fgTipoPessoa === "J") {
			if (isset($pessoa->cnpj)) {
				if ($pessoa->cnpj) {
					$pessoaModel['cpf_cnpj'] = $pessoa->cnpj;
				}
			}
		} else {
			if (isset($pessoa->cpf)) {
				if ($pessoa->cpf) {
					$pessoaModel['cpf_cnpj'] = $pessoa->cpf;
				}
			}
		}

		if (isset($pessoa->telefone)) {
			if ($pessoa->telefone) {
				$pessoaModel['telefone'] = $pessoa->telefone;
			}
		}

		
		if (isset($pessoa->numero)) {
			if ($pessoa->numero) {
				$pessoaModel['numero'] = $pessoa->numero;
			}
		}

		if (isset($pessoa->celular)) {
			if ($pessoa->celular) {
				$pessoaModel['celular'] = $pessoa->celular;
			}
		}

		if (isset($pessoa->observacao)) {
			if ($pessoa->observacao) {
				$pessoaModel['observacao'] = $pessoa->observacao;	
			}
		}

		if (isset($pessoa->logradouro)) {
			if ($pessoa->logradouro) {
				$pessoaModel['id_logradouro'] = $pessoa->logradouro;
			}
		}

		if (isset($pessoa->bairro)) {
			if ($pessoa->bairro) {
				$pessoaModel['id_bairro'] = $pessoa->bairro;
			}
		}

		if (isset($pessoa->cidade)) {
			if ($pessoa->cidade) {
				$pessoaModel['id_cidade'] = $pessoa->cidade;
			}
		}

		$response = array('exec' => $this->PessoaModel->inserir($pessoaModel));

		$array = $this->gerarRetorno($response, $response ? "Sucesso ao salvar o registro." : "Erro ao salvar o registro.");

		print_r(json_encode($array));
	}
	
}