<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

    public function entrar() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $usuario = json_decode($data);

        $this->load->library("JWT");

        if (!isset($usuario->login) || !isset($usuario->senha)) {
            print_r(json_encode($this->gerarRetorno(FALSE, "Email e senha são obrigatórios.")));
            die();
        }

        $usuario = $this->LoginModel->verificarLogin($usuario->login, md5($usuario->senha));
        if ($usuario) {
            $array = $this->gerarRetorno(TRUE, "Sucesso ao autenticar, redirecionando.");
            $array['data'] = array('token' => $this->generate_token($usuario[0]));

            print_r(json_encode($array));
        } else {
            print_r(json_encode($this->gerarRetorno(FALSE, "Dados informados são inválidos.")));
            die();
        }
    }

    public function generate_token($usuario) {
        $this->load->library("JWT");
        $CONSUMER_SECRET = 'sistema_mathias_2016';
        $CONSUMER_TTL = 28800;
        return $this->jwt->encode(array(
                    'id' => $usuario['id'],
                    'nome' => $usuario['nome_fantasia'],
                    'imagem' => $usuario['logo'],
                    'issuedAt' => date(DATE_ISO8601, strtotime("now")),
                    'dtBegin' => strtotime("now"),
                    'ttl' => $CONSUMER_TTL
                        ), $CONSUMER_SECRET);
    }

}
