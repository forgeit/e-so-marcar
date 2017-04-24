<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
	public function __construct() {
		parent::__construct();	

		$seguro = true;

		if ($this->uri->uri_string == 'login/entrar' || $this->uri->uri_string == 'upload' || (0 === strrpos($this->uri->uri_string, 'ver-arquivo/demanda'))) {
			$seguro = false;
		}

		// $seguro = false;

		if ($seguro) {
			if ($this->input->get_request_header('Authorization')) {
				$code = str_replace("Bearer ", "", $this->input->get_request_header('Authorization'));
				$this->load->library("JWT");

				try {
					$CONSUMER_SECRET = 'sistema_mathias_2016';

					$retorno = $this->jwt->decode($code, $CONSUMER_SECRET, true);

					if (!$retorno) {
						header('HTTP/1.1 401 Unauthorized', true, 401);
						die();
					} else {
						$diff = abs(strtotime("now") - $retorno->dtBegin);

						if ($diff > $retorno->ttl) {
							header('HTTP/1.1 401 Unauthorized', true, 401);
							die();
						}
					}
				} catch(Exception $ex) {
					header('HTTP/1.1 401 Unauthorized', true, 401);
					die();
				}
			} else {
				header('HTTP/1.1 401 Unauthorized', true, 401);
				die();
			}
		}
	}
}