<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VerArquivo extends MY_Controller {

	public function porDemanda() {
		$idDemanda = $this->uri->segment(3);
		$idArquivo = $this->uri->segment(5);
		$retorno = $this->DemandaArquivoModel->buscarArquivosPorIdDemandaEId($idDemanda, $idArquivo);
		if ($retorno) {
			$arquivo = file_get_contents($retorno['arquivo']);
			$fileInfo = new finfo(FILEINFO_MIME_TYPE);
			$mimeType = $fileInfo->buffer(file_get_contents($retorno['arquivo']));
			header("content-type: " . $mimeType);
			print_r($arquivo);
		} else {
			print_r("Erro ao carregar o arquivo.");
		}
	}

	public function porDemandaFluxo() {
		$idDemandaFluxo = $this->uri->segment(3);
		$idArquivo = $this->uri->segment(5);

		$retorno = $this->DemandaArquivoFluxoModel->buscarArquivosPorIdDemandaFluxoEId($idDemandaFluxo, $idArquivo);
		if ($retorno) {
			$arquivo = file_get_contents($retorno['arquivo']);
			$fileInfo = new finfo(FILEINFO_MIME_TYPE);
			$mimeType = $fileInfo->buffer(file_get_contents($retorno['arquivo']));
			header("content-type: " . $mimeType);
			print_r($arquivo);
		} else {
			print_r("Erro ao carregar o arquivo.");
		}
	}
	
}