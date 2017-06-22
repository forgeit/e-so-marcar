<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Horario extends MY_Controller {

    public function atualizar() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $horario = json_decode($data);
        $horarioBanco = $this->HorarioModel->buscarPorId($this->uri->segment(3));

        $horario->id = $horarioBanco['id'];
        $horario->hora_inicial = $horario->hora_inicial . '00';
        $horario->hora_final = $horario->hora_final . '00';
        
        $this->validaDados($horario);

        $response = array('exec' => $this->HorarioModel->atualizar($horario->id, $horario));

        $array = $this->gerarRetorno($response, $response ? "Sucesso ao atualizar o registro." : "Erro ao atualizar o registro.");

        print_r(json_encode($array));
    }

    public function buscar() {

        $horario = $this->HorarioModel->buscarPorId($this->uri->segment(2));



        if ($horario['id_cliente'] != $this->jwtController->id) {
            $this->gerarErro("Você não pode alterar este registro. " . $horario);
        }

        $array = array('data' =>
            array('dto' => $horario));

        print_r(json_encode($array));
    }

    public function buscarTodos() {

        if ($this->uri->segment(2) == 'quadra' && $this->uri->segment(3)) {
            $lista = $this->HorarioModel->buscarTodosNativo($this->jwtController->id, $this->uri->segment(3));
        } else {
            $lista = $this->HorarioModel->buscarTodosNativo($this->jwtController->id);
        }

        print_r(json_encode(array('data' => array('datatables' => $lista ? $lista : array()))));
    }

    public function buscarCombo() {
        print_r(json_encode(array('data' => array('ArrayList' => $lista = $this->HorarioModel->buscarTodosNativo($this->jwtController->id)))));
    }

    public function excluir() {

        $horarioBanco = $this->HorarioModel->buscarPorId($this->uri->segment(3));

        if ($this->validaClienteQuadra($horarioBanco['id_quadra'])) {
            $this->gerarErro("Você não pode excluir este registro.");
        }

        $response = $this->HorarioModel->excluir($horarioBanco['id']);

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
        $horario = json_decode($data);

        $horario->id_cliente = $this->jwtController->id;
        $horario->hora_inicial = $horario->hora_inicial . '00';
        $horario->hora_final = $horario->hora_final . '00';

        $dias = $horario->dia_semana;

        foreach ($dias as $dia_semana) {
            $horario->dia_semana = $dia_semana->id;
            $this->validaDados($horario);

            $response = $this->HorarioModel->inserir($horario);
        }

        $array = $this->gerarRetorno($response, $response ? "Sucesso ao salvar o registro." : "Erro ao salvar o registro.");
        print_r(json_encode($array));
    }

    private function validaDados($horario) {
        if (empty($horario->id_quadra)) {
            $this->gerarErro("Quadra é obrigatório.");
        }

        if (empty($horario->dia_semana)) {
            $this->gerarErro("Dia da Semana é obrigatório.");
        }

        if (empty($horario->hora_inicial)) {
            $this->gerarErro("Hora Inicial é obrigatório.");
        }

        if (empty($horario->hora_final)) {
            $this->gerarErro("Hora Final é obrigatório.");
        }

        if ($horario->hora_inicial >= $horario->hora_final) {
            $this->gerarErro("Hora Inicial deve ser menor que Data Final.");
        }

        if ($this->validaClienteQuadra($horario->id_quadra)) {
            $this->gerarErro("Você não pode inserir horários para esta quadra.");
        }

        if (empty($horario->valor)) {
            $this->gerarErro("Valor é obrigatório.");
        }

        if (!is_numeric($horario->valor)) {
            $this->gerarErro("Valor deve ser numérico.");
        }

        if(isset($horario->id)) {
            $horarios = $this->HorarioModel->horarioExiste($this->jwtController->id, $horario->id_quadra, $horario->dia_semana, $horario->hora_inicial, $horario->hora_final, $horario->id);
        } else {
            $horarios = $this->HorarioModel->horarioExiste($this->jwtController->id, $horario->id_quadra, $horario->dia_semana, $horario->hora_inicial, $horario->hora_final);
        }

        if ($horarios != null) {
            $this->gerarErro("Horário já existe.");
        }
    }

}
