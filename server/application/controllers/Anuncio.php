<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Anuncio extends MY_Controller {

    public function atualizar() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $anuncio = json_decode($data);
        $anuncioBanco = $this->AnuncioModel->buscarPorId($this->uri->segment(3));

        $this->validaDados($anuncio);

        if ($anuncioBanco['id_cliente'] != $this->jwtController->id) {
            $this->gerarErro("Você não pode alterar este registro.");
        }

        $anuncio->id = $anuncioBanco['id'];
        if ($anuncioBanco['imagem'] != $anuncio->imagem) {
            $anuncio->imagem = $this->uploadArquivo($anuncio->imagem, 'anuncio');
        }

        $response = array('exec' => $this->AnuncioModel->atualizar($anuncio->id, $anuncio));

        $array = $this->gerarRetorno($response, $response ? "Sucesso ao atualizar o registro." : "Erro ao atualizar o registro.");

        print_r(json_encode($array));
    }

    public function buscar() {

        $array = array('data' =>
            array('dto' => $this->AnuncioModel->buscarPorId($this->uri->segment(2))));

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

        $response = $this->AnuncioModel->excluir($this->uri->segment(3), 'id_pessoa');

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
    }

}
