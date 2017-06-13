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

    public function contato() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $contato = json_decode($data);

        if (empty($contato->nome)) {
            $this->gerarErro("Nome é obrigatório.");
        }

        if (empty($contato->email)) {
            $this->gerarErro("E-mail é obrigatório.");
        }

        if (empty($contato->mensagem)) {
            $this->gerarErro("Mensagem é obrigatório.");
        }
        
        if (empty($contato->telefone)) {
            $contato->telefone = "";
        }

        $this->email
                ->from($this->config->item('smtp_user'))
                ->to("contato@esomarcar.com.br")
                ->subject($contato->nome . ' - Contato É Só Marcar')
                ->message('<p>Nome: ' . strip_tags($contato->nome) . '</p>'
                        . '<p>Email: ' . strip_tags($contato->email) . '</p>'
                        . '<p>Telefone: ' . strip_tags($contato->telefone) . '</p>'
                        . '<p>Mensagem: ' . strip_tags($contato->mensagem) . '</p>'
                )
                ->send();


        $array = $this->gerarRetorno(TRUE, "Obrigado! Sua mensagem foi encaminhada à nossa equipe.");
        print_r(json_encode($array));
    }

}
