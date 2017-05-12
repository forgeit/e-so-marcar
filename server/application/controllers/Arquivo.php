<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Arquivo extends MY_Controller {

    public function buscar() {
        $idArquivo = $this->uri->segment(3);
        $retorno = $this->ArquivoModel->buscarPorId($idArquivo);

        if ($retorno) {
            $arquivo = file_get_contents($retorno['caminho']);
            $fileInfo = new finfo(FILEINFO_MIME_TYPE);
            $mimeType = $fileInfo->buffer(file_get_contents($retorno['caminho']));
            header("content-type: " . $mimeType);
            print_r($arquivo);
        } else {
            print_r("Erro ao carregar o arquivo.");
        }
    }

}
