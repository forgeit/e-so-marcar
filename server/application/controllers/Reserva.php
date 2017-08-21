<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reserva extends MY_Controller {

    public function atualizar() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $reserva = json_decode($data);
        $reservaBanco = $this->ReservaModel->buscarPorId($this->uri->segment(3));

        $reserva->id = $reservaBanco['id'];
        $this->validaDados($reserva);

        if ($reservaBanco['id_cliente'] != $this->jwtController->id) {
            $this->gerarErro("Você não pode alterar este registro.");
        }

        if ($reservaBanco['imagem'] != $reserva->imagem) {
            $reserva->imagem = $this->uploadArquivo($reserva->imagem, 'anuncio');
        }

        $response = array('exec' => $this->ReservaModel->atualizar($reserva->id, $reserva));

        $array = $this->gerarRetorno($response, $response ? "Sucesso ao atualizar o registro." : "Erro ao atualizar o registro.");

        print_r(json_encode($array));
    }

    public function buscar() {

        $array = array('data' =>
            array('dto' => $this->ReservaModel->buscarPorIdNativo($this->uri->segment(2))));

        print_r(json_encode($array));
    }

    public function buscarTodos() {

        $lista = $this->ReservaModel->buscarTodosNativo($this->jwtController->id);

        print_r(json_encode(array('data' => array('datatables' => $lista ? $lista : array()))));
    }

    public function excluir() {

        $reservaBanco = $this->ReservaModel->buscarPorId($this->uri->segment(3));

        if ($reservaBanco['id_cliente'] != $this->jwtController->id) {
            $this->gerarErro("Você não pode remover este registro.");
        }

        if (date('Y-m-d H:i:s') >= $reservaBanco['data_hora_reserva']) {
            $this->gerarErro("Reserva não pode ser removida. Data e hora da reserva anterior a data atual.");
        }

        $response = $this->ReservaModel->excluir($reservaBanco['id']);

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

    public function salvarCliente() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $reserva = json_decode($data);

        $reserva->id_cliente = $this->jwtController->id;
        $reserva->data_hora_reserva = $this->toDateTime($reserva->data_hora_reserva);

        $this->validaDados($reserva);

        if ($this->validaClienteQuadra($reserva->id_quadra)) {
            $this->gerarErro("Você não pode efetuar reservas para esta quadra.");
        }

        $reserva->data_hora_insercao = date('Y-m-d H:i:s');

        $response = array('exec' => $this->ReservaModel->inserir($reserva));

        $array = $this->gerarRetorno($response, $response ? "Reserva efetuada com sucesso!" : "Erro ao reservar.");
        print_r(json_encode($array));
    }

    public function salvarUsuario() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $reserva = json_decode($data);

        $reserva->data_hora_reserva = $this->toDateTimeSemZero($reserva->data_hora_reserva);
        $reserva->id_usuario = $this->jwtController->id;

        $horario = $this->validaDados($reserva);

        if ($reserva->marcacao_mensal) {
            $horario['id_usuario'] = $this->jwtController->id;

            $response = array('exec' => $this->HorarioModel->atualizar($horario['id'], $horario));
        } else {
            $reserva->data_hora_insercao = date('Y-m-d H:i:s');

            $response = array('exec' => $this->ReservaModel->inserir($reserva));
        }


        $array = $this->gerarRetorno($response, $response ? "Reserva efetuada com sucesso!" : "Erro ao reservar.");
        print_r(json_encode($array));
    }

    private function validaDados($reserva) {
        if (empty($reserva->id_quadra)) {
            $this->gerarErro("Quadra é obrigatório.");
        }

        if (empty($reserva->id_usuario)) {
            $this->gerarErro("Usuário é obrigatório.");
        }

        if (empty($reserva->id_cliente)) {
            $this->gerarErro("Cliente é obrigatório.");
        }

        if (empty($reserva->data_hora_reserva)) {
            $this->gerarErro("Data e Hora são obrigatórios.");
        }

        $horario = $this->horarioReserva($reserva->id_cliente, $reserva->id_quadra, $reserva->data_hora_reserva);

        $reserva->valor = $horario['valor'];

        return $horario;
    }

    public function buscarHorarioReserva() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $reserva = json_decode($data);

        if (empty($reserva->id_quadra)) {
            $this->gerarErro("Quadra é obrigatório.");
        }

        if ($this->validaClienteQuadra($reserva->id_quadra)) {
            $this->gerarErro("Você não pode efetuar reservas para esta quadra.");
        }

        if (empty($reserva->data_hora_reserva)) {
            $this->gerarErro("Data e Hora são obrigatórios.");
        }

        $reserva->data_hora_reserva = $this->toDateTime($reserva->data_hora_reserva);

        $horario = $this->horarioReserva($this->jwtController->id, $reserva->id_quadra, $reserva->data_hora_reserva);

        $array = array('data' =>
            array('dto' => $horario, 'status' => TRUE));
        print_r(json_encode($array));
    }

    private function horarioReserva($idCliente = null, $idQuadra = null, $dataHora = null) {
        if (date('Y-m-d H:i:s') >= $dataHora) {
            $this->gerarErro("Data e Hora da reserva deve ser maior que data atual.");
        }

        if ($this->ReservaModel->buscarPorColuna('data_hora_reserva', $dataHora) != null) {
            $this->gerarErro("Horário já reservado.");
        }

        if ($this->ExcecaoModel->isHorarioNaoJogar($idQuadra, $idCliente, $dataHora) != null) {
            $this->gerarErro("Horário de exceção. Não é possível fazer reserva.");
        }

        $horario = $this->ExcecaoModel->buscarHorarioJogar($idQuadra, $idCliente, $dataHora);

        if ($horario == null) {
            $horario = $this->HorarioModel->buscarHorario($idQuadra, $idCliente, date('w', strtotime($dataHora)) + 1, date('H:i:s', strtotime($dataHora)));
        }

        if ($horario == null) {
            $this->gerarErro("Horário selecionado não disponível para esta quadra.");
        }

        return $horario;
    }

}
