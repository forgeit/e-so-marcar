<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Anuncio extends MY_Controller {

    public function atualizar() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $anuncio = json_decode($data);
        $anuncioBanco = $this->AnuncioModel->buscarPorId($this->uri->segment(3));

        $anuncio->id = $anuncioBanco['id'];
        $this->validaDados($anuncio);

        if ($anuncioBanco['id_cliente'] != $this->jwtController->id) {
            $this->gerarErro("Você não pode alterar este registro.");
        }

        if ($anuncioBanco['imagem'] != $anuncio->imagem) {
            $anuncio->imagem = $this->uploadArquivo($anuncio->imagem, 'anuncio');
        }

        $response = array('exec' => $this->AnuncioModel->atualizar($anuncio->id, $anuncio));

        $array = $this->gerarRetorno($response, $response ? "Sucesso ao atualizar o registro." : "Erro ao atualizar o registro.");

        print_r(json_encode($array));
    }

    public function buscar() {

        $anuncio = $this->AnuncioModel->buscarPorIdNativo($this->uri->segment(2));

        if ($anuncio['id_cliente'] != $this->jwtController->id) {
            $this->gerarErro("Você não pode alterar este registro.");
        }

        $array = array('data' =>
            array('dto' => $anuncio));

        print_r(json_encode($array));
    }

    public function buscarTodos() {

        if ($this->uri->segment(2) == 'tipo-anuncio' && $this->uri->segment(3)) {
            $lista = $this->AnuncioModel->buscarTodosNativo($this->jwtController->id, $this->uri->segment(3));
        } else {
            $lista = $this->AnuncioModel->buscarTodosNativo($this->jwtController->id);
        }

        print_r(json_encode(array('data' => array('datatables' => $lista ? $lista : array()))));
    }

    public function excluir() {

        $anuncioBanco = $this->AnuncioModel->buscarPorId($this->uri->segment(3));

        if ($anuncioBanco['id_cliente'] != $this->jwtController->id) {
            $this->gerarErro("Você não pode alterar este registro.");
        }

        if (date('Y-m-d') >= $anuncioBanco['data_inicial'] && date('Y-m-d') <= $anuncioBanco['data_final']) {
            $this->gerarErro("Anúncio em período exibição não pode ser removido.");
        }

        if ($anuncioBanco['ativo']) {
            $this->gerarErro("Somente registros desativos podem ser removidos.");
        }

        $response = $this->AnuncioModel->excluir($anuncioBanco['id']);
        $this->deletarArquivo($anuncioBanco['imagem']);

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
        $anuncio = json_decode($data);

        $this->validaDados($anuncio);

        if (empty($anuncio->imagem)) {
            $this->gerarErro("Imagem é obrigatório.");
        }

        $anuncio->id_cliente = $this->jwtController->id;
        $anuncio->imagem = $this->uploadArquivo($anuncio->imagem, 'anuncio');

        $response = array('exec' => $this->AnuncioModel->inserir($anuncio));

        $array = $this->gerarRetorno($response, $response ? "Sucesso ao salvar o registro." : "Erro ao salvar o registro.");
        print_r(json_encode($array));
    }

    private function validaDados($anuncio) {
        if (empty($anuncio->id_tipo_anuncio)) {
            $this->gerarErro("Tipo Anúncio é obrigatório.");
        }

        if (empty($anuncio->titulo)) {
            $this->gerarErro("Título é obrigatório.");
        }

        if (empty($anuncio->data_inicial)) {
            $this->gerarErro("Data Inicial é obrigatório.");
        }

        if (empty($anuncio->data_final)) {
            $this->gerarErro("Data Final é obrigatório.");
        }

        if (!isset($anuncio->valor) && !$anuncio->valor) {
            $this->gerarErro("Valor é obrigatório.");
        }

        if (!isset($anuncio->ativo) && !$anuncio->ativo) {
            $this->gerarErro("Flag Ativo é obrigatório.");
        }

        $anuncio->data_inicial = $this->toDate($anuncio->data_inicial);
        $anuncio->data_final = $this->toDate($anuncio->data_final);

        if ($this->AnuncioModel->anuncioNaoUnico($this->jwtController->id, $anuncio)) {
            $this->gerarErro("Já existe anúncio deste tipo para o período selecionado.");
        }
    }

}
