<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	public function buscar() {

		$cartoes = array();

		$timestamp = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
		$nova = strtotime("-8 day", $timestamp);
		$dia = date('d', $nova);
		$mes = date('m', $nova);
		$ano = date('Y', $nova);

		for ($i = 1; $i < 19; $i++) {
			$timestamp = mktime(0, 0, 0, $mes, $dia, $ano);
			$nova = strtotime("+$i day", $timestamp);

			$totais = $this->DemandaModel->buscarCartoes(date('Y-m-d', $nova));

			$cartao = array (
					'data' => date('d/m/Y', $nova),
					'resolvida' => $totais['resolvida'],
					'nao_resolvida' => $totais['nao_resolvida'],
					'outras' => $totais['outras'],
					'hoje' => date('d/m/Y') == date('d/m/Y', $nova) ? true : false,
					'passado' => $i < 8 ? true : false
					);

			$cartoes[] = $cartao;
		}

		print_r(json_encode($cartoes));
		
	}
	
}