<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

	public function entrar() {
		$data = $this->security->xss_clean($this->input->raw_input_stream);
		$usuario = json_decode($data);

		$this->load->library("JWT");

		if (!isset($usuario->login) || !isset($usuario->senha)) {
			print_r(json_encode($this->gerarRetorno(FALSE, "Login e senha são obrigatórios.")));
			die();
		}

		$usuario = $this->UsuarioModel->verificarLogin($usuario->login, md5($usuario->senha));
		if ($usuario) {
			$array = $this->gerarRetorno(TRUE, "Sucesso ao autenticar, redirecionando.");
			$array['data'] = array('token' => $this->generate_token($usuario[0]));

			print_r(json_encode($array));
		} else {
			print_r(json_encode($this->gerarRetorno(FALSE, "Dados informados são inválidos.")));
			die();
		}
	}

	public function generate_token($usuario){
    	$this->load->library("JWT");
	    $CONSUMER_SECRET = 'sistema_mathias_2016';
	    $CONSUMER_TTL = 28800;
	    return $this->jwt->encode(array(
	   	  'id' => $usuario['id_usuario'],
	   	  'nome' => $usuario['nome'],
	   	  'cargo' => $usuario['cargo'],
	   	  'imagem' => $usuario['imagem'],
	      'issuedAt'=> date(DATE_ISO8601, strtotime("now")),
	      'dtBegin' => strtotime("now"),
	      'ttl'=> $CONSUMER_TTL
	    ), $CONSUMER_SECRET);
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