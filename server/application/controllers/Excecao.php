<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Excecao extends MY_Controller {

    public function atualizar() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $excecao = json_decode($data);
        $excecaoBanco = $this->ExcecaoModel->buscarPorId($this->uri->segment(3));

        $excecao->id = $excecaoBanco['id'];
        $this->validaDados($excecao);

        $excecao->data_hora_inicial = $this->toDateTime($excecao->data_hora_inicial);
        $excecao->data_hora_final = $this->toDateTime($excecao->data_hora_final);
        
        if($excecao->data_hora_inicial >= $excecao->data_hora_final) {
            $this->gerarErro("Data e Hora Inicial deve ser menor que Data e Hora Final.");
        }

        $response = array('exec' => $this->ExcecaoModel->atualizar($excecao->id, $excecao));

        $array = $this->gerarRetorno($response, $response ? "Sucesso ao atualizar o registro." : "Erro ao atualizar o registro.");

        print_r(json_encode($array));
    }

    public function buscar() {

        $excecao = $this->ExcecaoModel->buscarPorId($this->uri->segment(2));

        if ($excecao['id_cliente'] != $this->jwtController->id) {
            $this->gerarErro("Você não pode alterar este registro. " . $excecao);
        }

        $array = array('data' =>
            array('dto' => $excecao));

        print_r(json_encode($array));
    }

    public function buscarTodos() {

        if ($this->uri->segment(2) == 'quadra' && $this->uri->segment(3)) {
            $lista = $this->ExcecaoModel->buscarTodosNativo($this->jwtController->id, $this->uri->segment(3));
        } else {
            $lista = $this->ExcecaoModel->buscarTodosNativo($this->jwtController->id);
        }

        print_r(json_encode(array('data' => array('datatables' => $lista ? $lista : array()))));
    }

    public function buscarCombo() {
        print_r(json_encode(array('data' => array('ArrayList' => $lista = $this->ExcecaoModel->buscarTodosNativo($this->jwtController->id)))));
    }

    public function excluir() {

        $excecaoBanco = $this->ExcecaoModel->buscarPorId($this->uri->segment(3));

        if ($this->validaClienteQuadra($excecaoBanco['id_quadra'])) {
            $this->gerarErro("Você não pode excluir este registro.");
        }

        $response = $this->ExcecaoModel->excluir($excecaoBanco['id']);

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

    public function salvar() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $excecao = json_decode($data);

        $this->validaDados($excecao);

        $excecao->id_cliente = $this->jwtController->id;
        $excecao->data_hora_inicial = $this->toDateTime($excecao->data_hora_inicial);
        $excecao->data_hora_final = $this->toDateTime($excecao->data_hora_final);

        if($excecao->data_hora_inicial >= $excecao->data_hora_final) {
            $this->gerarErro("Data e Hora Inicial deve ser menor que Data e Hora Final.");
        }
        
        $response = $this->ExcecaoModel->inserir($excecao);

        $array = $this->gerarRetorno($response, $response ? "Sucesso ao salvar o registro." : "Erro ao salvar o registro.");
        print_r(json_encode($array));
    }

    private function validaDados($excecao) {
        if (empty($excecao->id_quadra)) {
            $this->gerarErro("Quadra é obrigatório.");
        }

        if (empty($excecao->data_hora_inicial)) {
            $this->gerarErro("Data Inicial é obrigatória.");
        }
        
        if (empty($excecao->data_hora_final)) {
            $this->gerarErro("Data Final é obrigatória.");
        }

        if (!isset($excecao->flag_pode_jogar)) {
            $this->gerarErro("Pode Jogar é obrigatório.");
        }

        if ($excecao->flag_pode_jogar && empty($excecao->valor)) {
            $this->gerarErro("Valor é obrigatório.");
        }

        if ($excecao->flag_pode_jogar && !is_numeric($excecao->valor)) {
            $this->gerarErro("Valor deve ser numérico.");
        }
                
        if ($this->validaClienteQuadra($excecao->id_quadra)) {
            $this->gerarErro("Você não pode inserir horários de exceção para esta quadra.");
        }
    }

    private function validaClienteQuadra($idQuadra) {
        $quadraBanco = $this->QuadraModel->buscarPorId($idQuadra);
        return ($quadraBanco['id_cliente'] != $this->jwtController->id);
    }

}
