<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Quadra extends MY_Controller {

    public function atualizar() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $quadra = json_decode($data);
        $quadraBanco = $this->QuadraModel->buscarPorId($this->uri->segment(3));

        if ($quadraBanco['id_cliente'] != $this->jwtController->id) {
            $this->gerarErro("Você não pode alterar este registro.");
        }
        
        $quadra->id = $quadraBanco['id'];
        $this->validaDados($quadra);

        if(!$quadra->situacao) {
            $reservas = $this->ReservaModel->buscarEmAbertoPorQuadra($quadra->id);
            if($reservas != null) {
                $this->gerarErro("Quadra possui reservas em aberto. Não pode ser desativada.");
            }
        }

        if ($quadraBanco['imagem'] != $quadra->imagem) {
            $quadra->imagem = $this->uploadArquivo($quadra->imagem, 'quadra');
        }
        if ($quadraBanco['imagem1'] != $quadra->imagem1) {
            $quadra->imagem1 = $this->uploadArquivo($quadra->imagem1, 'quadra');
        }
        if ($quadraBanco['imagem2'] != $quadra->imagem2) {
            $quadra->imagem2 = $this->uploadArquivo($quadra->imagem2, 'quadra');
        }
        if ($quadraBanco['imagem3'] != $quadra->imagem3) {
            $quadra->imagem3 = $this->uploadArquivo($quadra->imagem3, 'quadra');
        }

        $response = array('exec' => $this->QuadraModel->atualizar($quadra->id, $quadra));

        $this->QuadraEsporteModel->removerPorIdQuadra($quadraBanco['id']);
        foreach ($quadra->esportes as $value) {
            $quadraEsporte = new stdClass();
            $quadraEsporte->id_quadra = $quadraBanco['id'];
            $quadraEsporte->id_esporte = $value->id;
            $this->QuadraEsporteModel->inserir($quadraEsporte);
        }

        $array = $this->gerarRetorno($response, $response ? "Sucesso ao atualizar o registro." : "Erro ao atualizar o registro.");

        print_r(json_encode($array));
    }

    public function buscar() {

        $quadra = $this->QuadraModel->buscarPorId($this->uri->segment(2));
        $quadra['esportes'] = $this->QuadraEsporteModel->buscarPorColuna('id_quadra', $quadra['id']);

        if ($quadra['id_cliente'] != $this->jwtController->id) {
            $this->gerarErro("Você não pode alterar este registro.");
        }

        $array = array('data' =>
            array('dto' => $quadra));

        print_r(json_encode($array));
    }

    public function buscarQuadra() {

        $quadra = $this->QuadraModel->buscarPorId($this->uri->segment(3));
        $quadra['esportes'] = $this->QuadraEsporteModel->buscarEsportes($quadra['id']);

        $array = array('data' =>
            array('dto' => $quadra));

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

    public function buscarCombo() {
        print_r(json_encode(array('data' => array('ArrayList' => $lista = $this->QuadraModel->buscarTodosNativo($this->jwtController->id, 1)))));
    }

    public function buscarTodosNativo() {
        print_r(json_encode(array('data' => array('ArrayList' => $this->QuadraModel->buscarTodosNativo($this->uri->segment(3), 1)))));
    }

    public function excluir() {

        $quadraBanco = $this->QuadraModel->buscarPorId($this->uri->segment(3));

        if ($quadraBanco['id_cliente'] != $this->jwtController->id) {
            $this->gerarErro("Você não pode alterar este registro.");
        }

        if ($quadraBanco['situacao']) {
            $this->gerarErro("Quadra ainda está ativa.");
        }
        
        $reservas = $this->ReservaModel->buscarPorColuna('id_quadra', $quadraBanco['id']);
        if($reservas != null) {
            $this->gerarErro("Não pode ser removido pois já existem reservas para esta quadra.");
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
        if (isset($quadra->imagem1)) {
            $quadra->imagem1 = $this->uploadArquivo($quadra->imagem1, 'quadra');
        }
        if (isset($quadra->imagem2)) {
            $quadra->imagem2 = $this->uploadArquivo($quadra->imagem2, 'quadra');
        }
        if (isset($quadra->imagem3)) {
            $quadra->imagem3 = $this->uploadArquivo($quadra->imagem3, 'quadra');
        }
        
        $idQuadra = $this->QuadraModel->inserirRetornaId($quadra);

        foreach ($quadra->esportes as $value) {
            $quadraEsporte = new stdClass();
            $quadraEsporte->id_quadra = $idQuadra;
            $quadraEsporte->id_esporte = $value->id;
            $this->QuadraEsporteModel->inserir($quadraEsporte);
        }

        $array = $this->gerarRetorno(TRUE, "Sucesso ao salvar o registro.");
        print_r(json_encode($array));
    }

    private function validaDados($quadra) {
        if (empty($quadra->id_tipo_local)) {
            $this->gerarErro("Tipo Local é obrigatório.");
        }

        if (empty($quadra->id_tipo_quadra)) {
            $this->gerarErro("Tipo Quadra é obrigatório.");
        }

        if (empty($quadra->esportes)) {
            $this->gerarErro("Esportes são obrigatórios.");
        }

        if (empty($quadra->titulo)) {
            $this->gerarErro("Título é obrigatório.");
        }

        if (empty($quadra->descricao)) {
            $this->gerarErro("Descrição é obrigatório.");
        }

        if (!isset($quadra->valor_bola)) {
            $quadra->valor_bola = 0;
        }

        if (!empty($quadra->largura) && empty($quadra->comprimento)) {
            $this->gerarErro("Largura e Comprimento devem ser preenchidos.");
        }

        if (empty($quadra->largura) && !empty($quadra->comprimento)) {
            $this->gerarErro("Largura e Comprimento devem ser preenchidos.");
        }

        if (!empty($quadra->largura) && !is_numeric($quadra->largura)) {
            $this->gerarErro("Largura deve ser numérico.");
        }

        if (!empty($quadra->comprimento) && !is_numeric($quadra->comprimento)) {
            $this->gerarErro("Comprimento deve ser numérico.");
        }

        if (!isset($quadra->flag_tamanho_oficial) && !$quadra->flag_tamanho_oficial) {
            $this->gerarErro("Tamanho Oficial é obrigatório.");
        }

        if (!isset($quadra->flag_dia_chuva) && !$quadra->flag_dia_chuva) {
            $this->gerarErro("Dia de Chuva é obrigatório.");
        }

        if (!isset($quadra->flag_marcacao_mensal) && !$quadra->flag_marcacao_mensal) {
            $this->gerarErro("Marcação Mensal é obrigatório.");
        }

        if (!isset($quadra->situacao) && !$quadra->situacao) {
            $this->gerarErro("Situação é obrigatório.");
        }
    }

}
