<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dados extends MY_Controller {

    public function buscar() {
        $array = array('data' =>
            array('dto' =>
                $this->DadosModel->buscarPorId($this->jwtController->id)));

        print_r(json_encode($array));
    }
    
    public function senha() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $senhas = json_decode($data);

        if (empty($senhas->atual)) {
            $this->gerarErro("Senha atual é obrigatório.");
        }

        if (empty($senhas->nova)) {
            $this->gerarErro("Nova senha é obrigatório.");
        }

        if (empty($senhas->nova2)) {
            $this->gerarErro("Repetir nova senha é obrigatório.");
        }

        if ($senhas->nova2 != $senhas->nova) {
            $this->gerarErro("Novas senhas são diferentes.");
        }

        $usuarioBanco = $this->ClienteModel->buscarPorId($this->jwtController->id);

        if ($usuarioBanco['senha'] != md5($senhas->atual)) {
            $this->gerarErro("Senha atual incorreta.");
        }

        $usuarioBanco['senha'] = md5($senhas->nova);

        $this->ClienteModel->atualizar($this->jwtController->id, $usuarioBanco);

        $array = $this->gerarRetorno(TRUE, "Senha atualizada com sucesso.");
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
        if (!empty($dados->tel) && !$tel->valida()) {
            print_r(json_encode($this->gerarRetorno(FALSE, "Telefone Residêncial é inválido.")));
            die();
        }

        $cel = new ValidaTelefone($dados->cel);
        if (!empty($dados->cel) && !$cel->valida()) {
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

        if (isset($dados->logo) && $dados->logo != $dadosBanco['logo']) {
            $dados->logo = $this->uploadArquivo($dados->logo, 'logo');
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
