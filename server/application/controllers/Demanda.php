<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Demanda extends MY_Controller {

    public function atualizar() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $demanda = json_decode($data);
        $demandaModel = array();

        if ($demanda->titulo) {
            $demandaModel['titulo'] = strtoupper($demanda->titulo);
        } else {
            print_r(json_encode($this->gerarRetorno(FALSE, "O campo título é obrigatório.")));
            die();
        }

        if ($demanda->solicitante) {
            $demandaModel['id_solicitante'] = strtoupper($demanda->solicitante);
        } else {
            print_r(json_encode($this->gerarRetorno(FALSE, "O campo solicitante é obrigatório.")));
            die();
        }

        if (isset($demanda->descricao)) {
            if ($demanda->descricao) {
                $demandaModel['descricao'] = strtoupper($demanda->descricao);
            }
        }

        if ($demanda->tipoDemanda) {
            $demandaModel['id_tipo_demanda'] = strtoupper($demanda->tipoDemanda);
        } else {
            print_r(json_encode($this->gerarRetorno(FALSE, "O campo tipo de demanda é obrigatório.")));
            die();
        }

        if ($demanda->dtContato) {
            $data = explode("/", $demanda->dtContato);
            $demandaModel['dt_contato'] = $data[2] . '-' . $data[1] . '-' . $data[0];
        } else {
            print_r(json_encode($this->gerarRetorno(FALSE, "O campo data de contato é obrigatório.")));
            die();
        }

        if (isset($demanda->prazoFinal)) {
            if ($demanda->prazoFinal) {

                $data = explode("/", $demanda->prazoFinal);
                $demandaModel['prazo_final'] = $data[2] . '-' . $data[1] . '-' . $data[0];
            }
        }

        $this->db->trans_begin();

        $this->DemandaModel->atualizar($this->uri->segment(3), $demandaModel, 'id_demanda');

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            print_r(json_encode($this->gerarRetorno(FALSE, "Ocorreu um erro ao atualizar a nova demanda.")));
        } else {
            $this->db->trans_commit();
            print_r(json_encode($this->gerarRetorno(TRUE, "A demanda foi atualizada com sucesso.")));
        }
    }

    public function buscarTodos() {
        if ($this->uri->segment(2) == 'tipo-demanda' && $this->uri->segment(4) == 'situacao') {
            $lista = $this->DemandaModel->buscarTodosNativo($this->uri->segment(3), $this->uri->segment(5));
        } else if ($this->uri->segment(2) == 'tipo-demanda') {
            $lista = $this->DemandaModel->buscarTodosNativo($this->uri->segment(3));
        } else if ($this->uri->segment(2) == 'situacao') {
            $lista = $this->DemandaModel->buscarTodosNativo(null, $this->uri->segment(3));
        } else {
            $lista = $this->DemandaModel->buscarTodosNativo();
        }

        print_r(json_encode(array('data' => array('datatables' => $lista ? $lista : array()))));
    }

    public function buscarPorData() {
        $data = $this->uri->segment(4) . '-' . $this->uri->segment(3) . '-' . $this->uri->segment(2);
        $lista = $this->DemandaModel->buscarPorDataNativo($data);
        print_r(json_encode(array('data' => array('datatables' => $lista ? $lista : array()))));
    }

    public function buscar() {

        $dados = $this->DemandaModel->buscarPorIdCompleto($this->uri->segment(3));
        $arquivos = $this->DemandaArquivoModel->buscarArquivosPorIdDemanda($this->uri->segment(3));

        $fluxo = $this->DemandaFluxoModel->buscarFluxoPorIdDemanda($this->uri->segment(3));

        foreach ($fluxo as $key => $value) {
            $fluxo[$key]['descricao'] = $value['descricao'] == '' ? 'Não Informado' : $value['descricao'];
            $fluxo[$key]['pessoa'] = $value['pessoa'] == '' ? 'Não Informado' : $value['pessoa'];
            $fluxo[$key]['total'] = $value['total'] == 0 ? 'Não Possui' : $value['total'];
        }

        $dados['prazoFinalDescricao'] = $dados['prazoFinal'] == '' ? 'Não Informado' : $dados['prazoFinal'];
        $dados['descricaoDescricao'] = $dados['descricao'] == '' ? 'Não Informado' : $dados['descricao'];
        $dados['arquivos'] = $arquivos;
        $dados['fluxo'] = $fluxo;

        $array = array('data' => array('DemandaDto' => $dados));

        print_r(json_encode($array));
    }

    public function salvar() {
        $data = $this->security->xss_clean($this->input->raw_input_stream);
        $demanda = json_decode($data);
        $demandaModel = array();
        $demandaModel['dt_criacao'] = date('Y-m-d');
        $demandaModel['id_situacao'] = 1; // Demanda iniciada

        if ($demanda->titulo) {
            $demandaModel['titulo'] = strtoupper($demanda->titulo);
        } else {
            print_r(json_encode($this->gerarRetorno(FALSE, "O campo título é obrigatório.")));
            die();
        }

        if ($demanda->solicitante) {
            $demandaModel['id_solicitante'] = strtoupper($demanda->solicitante);
        } else {
            print_r(json_encode($this->gerarRetorno(FALSE, "O campo solicitante é obrigatório.")));
            die();
        }

        if (isset($demanda->descricao)) {
            if ($demanda->descricao) {
                $demandaModel['descricao'] = strtoupper($demanda->descricao);
            }
        }

        if ($demanda->tipoDemanda) {
            $demandaModel['id_tipo_demanda'] = strtoupper($demanda->tipoDemanda);
        } else {
            print_r(json_encode($this->gerarRetorno(FALSE, "O campo tipo de demanda é obrigatório.")));
            die();
        }

        if ($demanda->dtContato) {
            $data = explode("/", $demanda->dtContato);
            $demandaModel['dt_contato'] = $data[2] . '-' . $data[1] . '-' . $data[0];
        } else {
            print_r(json_encode($this->gerarRetorno(FALSE, "O campo data de contato é obrigatório.")));
            die();
        }

        if (isset($demanda->prazoFinal)) {
            if ($demanda->prazoFinal) {

                $data = explode("/", $demanda->prazoFinal);
                $demandaModel['prazo_final'] = $data[2] . '-' . $data[1] . '-' . $data[0];
            }
        }

        $novosArquivos = array();

        if (isset($demanda->arquivos)) {
            if (count($demanda->arquivos) > 0) {
                $arquivosTemporarios = $demanda->arquivos;
                $temporario = "application/views/upload/arquivos/tmp/";
                $diretorio = "application/views/upload/arquivos/demanda/";

                foreach ($arquivosTemporarios as $key => $value) {
                    if (!file_exists($temporario . $value)) {
                        print_r(json_encode($this->gerarRetorno(FALSE, "Ocorreu um erro ao efetuar o upload.")));
                        die();
                    } else {
                        $novoDiretorio = $diretorio . date('Ymd');
                        if (!file_exists($novoDiretorio)) {
                            if (!mkdir($novoDiretorio)) {
                                print_r(json_encode($this->gerarRetorno(FALSE, "Ocorreu um erro ao criar o diretório.")));
                                die();
                            }
                        }

                        if (!is_dir($novoDiretorio)) {
                            print_r(json_encode($this->gerarRetorno(FALSE, "Ocorreu um erro ao criar o diretório.")));
                            die();
                        }

                        $novo = array(
                            'arquivo' => $novoDiretorio . "/" . date('YmdHis-') . rand(1001, 9999) . "-" . $value,
                            'nome' => $value);

                        if (!copy($temporario . $value, $novo['arquivo'])) {
                            print_r(json_encode($this->gerarRetorno(FALSE, "Ocorreu um erro ao efetuar o upload.")));
                            die();
                        }

                        $novosArquivos[] = $novo;
                    }
                }
            }
        }

        $this->db->trans_begin();
        $idDemanda = $this->DemandaModel->inserirRetornaId($demandaModel);

        $demandaFluxoModel = array(
            'id_demanda' => $idDemanda,
            'id_situacao' => 1,
            'ts_transacao' => date('Y-m-d H:i:s')
        );

        $this->DemandaFluxoModel->inserir($demandaFluxoModel);

        if (count($novosArquivos) > 0) {
            foreach ($novosArquivos as $key => $value) {
                $demandaArquivoModel = array();
                $demandaArquivoModel['id_demanda'] = $idDemanda;
                $demandaArquivoModel['arquivo'] = $value['arquivo'];
                $demandaArquivoModel['nome'] = $value['nome'];
                $this->DemandaArquivoModel->inserir($demandaArquivoModel);
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            print_r(json_encode($this->gerarRetorno(FALSE, "Ocorreu um erro ao registrar a nova demanda.")));
        } else {
            $this->db->trans_commit();
            print_r(json_encode($this->gerarRetorno(TRUE, "A demanda foi registrada com sucesso.")));
        }
    }

    public function remover() {
        $id = $this->uri->segment(3);

        $this->db->trans_begin();

        try {
            if (!$this->DemandaArquivoFluxoModel->removerPorIdDemanda($id)) {
                $this->db->trans_rollback();
                print_r(json_encode($this->gerarRetorno(FALSE, "Ocorreu um erro ao remover.")));
                die();
            }

            if (!$this->DemandaFluxoModel->removerPorIdDemanda($id)) {
                $this->db->trans_rollback();
                print_r(json_encode($this->gerarRetorno(FALSE, "Ocorreu um erro ao remover.")));
                die();
            }

            if (!$this->DemandaArquivoModel->removerPorIdDemanda($id)) {
                $this->db->trans_rollback();
                print_r(json_encode($this->gerarRetorno(FALSE, "Ocorreu um erro ao remover.")));
                die();
            }

            if (!$this->DemandaModel->removerPorIdDemanda($id)) {
                $this->db->trans_rollback();
                print_r(json_encode($this->gerarRetorno(FALSE, "Ocorreu um erro ao remover.")));
                die();
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                print_r(json_encode($this->gerarRetorno(FALSE, "Ocorreu um erro ao remover.")));
            } else {
                $this->db->trans_commit();
                print_r(json_encode($this->gerarRetorno(TRUE, "Sucesso ao remover.")));
            }
        } catch (Exception $ex) {
            $this->db->trans_rollback();
            print_r(json_encode($this->gerarRetorno(FALSE, "Ocorreu um erro ao remover.")));
        }
    }

    private function gerarRetorno($response, $mensagem) {
        $message = array();
        $message[] = $response == TRUE ?
                array('tipo' => 'success', 'mensagem' => $mensagem) :
                array('tipo' => 'error', 'mensagem' => $mensagem);

        $array = array(
            'message' => $message,
            'status' => $response == TRUE ? 'true' : 'false'
        );

        return $array;
    }

}
