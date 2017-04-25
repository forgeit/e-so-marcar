<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dados extends MY_Controller {

    public function buscar() {
        $array = array('data' =>
            array('dto' =>
                $this->DadosModel->buscarPorId($this->jwtController->id)));

        print_r(json_encode($array));
    }

    public function salvar() {
        $dadosBanco = $this->DadosModel->buscarPorId($this->jwtController->id);

        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $dados = json_decode($data);

        $dados->id = $dadosBanco['id'];
        $dados->email = $dadosBanco['email'];

        if (empty($dados->razao_social)) {
            print_r(json_encode($this->gerarRetorno(FALSE, "Razão Social é obrigatório.")));
            die();
        }

        if (empty($dados->nome_fantasia)) {
            print_r(json_encode($this->gerarRetorno(FALSE, "Nome Fantasia é obrigatório.")));
            die();
        }

        if (empty($dados->cpf_cnpj)) {
            print_r(json_encode($this->gerarRetorno(FALSE, "CPF/CNPJ é obrigatório.")));
            die();
        }

        $cpf_cnpj = new ValidaCpfCnpj($dados->cpf_cnpj);
        if (!$cpf_cnpj->valida()) {
            print_r(json_encode($this->gerarRetorno(FALSE, "CPF/CNPJ é inválido.")));
            die();
        }

        if (empty($dados->endereco)) {
            print_r(json_encode($this->gerarRetorno(FALSE, "Endereço é obrigatório.")));
            die();
        }

        $tel = new ValidaTelefone($dados->tel);
        if (!$tel->valida()) {
            print_r(json_encode($this->gerarRetorno(FALSE, "Telefone Residêncial é inválido.")));
            die();
        }

        $cel = new ValidaTelefone($dados->tel);
        if (!$cel->valida()) {
            print_r(json_encode($this->gerarRetorno(FALSE, "Telefone Celular é inválido.")));
            die();
        }

        if (!empty($dados->latitude) && empty($dados->longitude)) {
            print_r(json_encode($this->gerarRetorno(FALSE, "Latitude e Longitude devem ser preenchidos.")));
            die();
        }

        if (empty($dados->latitude) && !empty($dados->longitude)) {
            print_r(json_encode($this->gerarRetorno(FALSE, "Latitude e Longitude devem ser preenchidos.")));
            die();
        }

        if (!empty($dados->latitude) && !is_numeric($dados->latitude)) {
            print_r(json_encode($this->gerarRetorno(FALSE, "Latitude deve ser numérico.")));
            die();
        }

        if (!empty($dados->longitude) && !is_numeric($dados->longitude)) {
            print_r(json_encode($this->gerarRetorno(FALSE, "Longitude deve ser numérico.")));
            die();
        }

        if (!empty($dados->url_facebook) && !$this->startsWith($dados->url_facebook, 'http://')) {
            print_r(json_encode($this->gerarRetorno(FALSE, "Página do Facebook é inválido.")));
            die();
        }

        if (isset($dados->logo)) {
            $arquivo = $dados->logo;
            $temporario = 'application/views/upload/arquivos/tmp/';
            $diretorio = 'application/views/upload/arquivos/logo/';

            if (!file_exists($temporario . $arquivo)) {
                print_r(json_encode($this->gerarRetorno(FALSE, "Ocorreu um erro ao efetuar o upload.")));
                die();
            }
            
            $novoDiretorio = $diretorio . date('Ymd');
            if (!file_exists($novoDiretorio)) {
                if (!mkdir($novoDiretorio)) {
                    print_r(json_encode($this->gerarRetorno(FALSE, "Ocorreu um erro ao criar o caminho.")));
                    die();
                }
            }

            if (!is_dir($novoDiretorio)) {
                print_r(json_encode($this->gerarRetorno(FALSE, "Ocorreu um erro ao criar o caminho.")));
                die();
            }

            $novo = array(
                'arquivo' => $novoDiretorio . "/" . date('YmdHis-') . rand(1001, 9999) . "-" . $arquivo,
                'nome' => $arquivo);

            if (!copy($temporario . $arquivo, $novo['arquivo'])) {
                print_r(json_encode($this->gerarRetorno(FALSE, "Ocorreu um erro ao efetuar o upload.")));
                die();
            }

            $dados->logo = $novo['arquivo'];
        }

        if ($this->DadosModel->atualizar($dados->id, $dados, 'id')) {
            print_r(json_encode($this->gerarRetorno(TRUE, "Sucesso ao alterar os dados.")));
            die();
        } else {
            print_r(json_encode($this->gerarRetorno(FALSE, "Ocorreu um erro ao alterar os dados.")));
            die();
        }
    }

}
