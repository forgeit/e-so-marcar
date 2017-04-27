<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Quadra extends MY_Controller {

    public function atualizar() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $quadra = json_decode($data);
        $quadraBanco = $this->QuadraModel->buscarPorId($this->uri->segment(3));

        $quadra->id = $quadraBanco['id'];
        $this->validaDados($quadra);

        if ($quadraBanco['id_cliente'] != $this->jwtController->id) {
            $this->gerarErro("Você não pode alterar este registro.");
        }

        if ($quadraBanco['imagem'] != $quadra->imagem) {
            $quadra->imagem = $this->uploadArquivo($quadra->imagem, 'quadra');
        }

        $response = array('exec' => $this->QuadraModel->atualizar($quadra->id, $quadra));

        $array = $this->gerarRetorno($response, $response ? "Sucesso ao atualizar o registro." : "Erro ao atualizar o registro.");

        print_r(json_encode($array));
    }

    public function buscar() {

        $array = array('data' =>
            array('dto' => $this->QuadraModel->buscarPorId($this->uri->segment(2))));

        print_r(json_encode($array));
    }

    public function buscarTodos() {

        if ($this->uri->segment(2) == 'tipo-quadra' && $this->uri->segment(3)) {
            $lista = $this->QuadraModel->buscarTodosNativo($this->jwtController->id, $this->uri->segment(3));
        } else {
            $lista = $this->QuadraModel->buscarTodosNativo($this->jwtController->id);
        }

        print_r(json_encode(array('data' => array('datatables' => $lista ? $lista : array()))));
    }

    public function excluir() {

        $quadraBanco = $this->QuadraModel->buscarPorId($this->uri->segment(3));

        if ($quadraBanco['id_cliente'] != $this->jwtController->id) {
            $this->gerarErro("Você não pode alterar este registro.");
        }

        if (date('Y-m-d') >= $quadraBanco['data_inicial'] && date('Y-m-d') <= $quadraBanco['data_final']) {
            $this->gerarErro("Anúncio em período exibição não pode ser removido.");
        }

        if ($quadraBanco['ativo']) {
            $this->gerarErro("Somente registros desativos podem ser removidos.");
        }

        $response = $this->QuadraModel->excluir($quadraBanco['id']);
        $this->deletarArquivo($quadraBanco['imagem']);

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
        $quadra = json_decode($data);

        $this->validaDados($quadra);

        if (empty($quadra->imagem)) {
            $this->gerarErro("Imagem é obrigatório.");
        }

        $quadra->id_cliente = $this->jwtController->id;
        $quadra->imagem = $this->uploadArquivo($quadra->imagem, 'quadra');

        $response = array('exec' => $this->QuadraModel->inserir($quadra));

        $array = $this->gerarRetorno($response, $response ? "Sucesso ao salvar o registro." : "Erro ao salvar o registro.");
        print_r(json_encode($array));
    }

    private function validaDados($quadra) {
        if (empty($quadra->id_tipo_quadra)) {
            $this->gerarErro("Tipo Anúncio é obrigatório.");
        }

        if (empty($quadra->titulo)) {
            $this->gerarErro("Título é obrigatório.");
        }

        if (empty($quadra->data_inicial)) {
            $this->gerarErro("Data Inicial é obrigatório.");
        }

        if (empty($quadra->data_final)) {
            $this->gerarErro("Data Final é obrigatório.");
        }

        if (!isset($quadra->valor) && !$quadra->valor) {
            $this->gerarErro("Valor é obrigatório.");
        }

        if (!isset($quadra->ativo) && !$quadra->ativo) {
            $this->gerarErro("Flag Ativo é obrigatório.");
        }

        $quadra->data_inicial = $this->toDate($quadra->data_inicial);
        $quadra->data_final = $this->toDate($quadra->data_final);

        if ($this->QuadraModel->quadraNaoUnico($this->jwtController->id, $quadra)) {
            $this->gerarErro("Já existe anúncio deste tipo para o período selecionado.");
        }
    }

}
