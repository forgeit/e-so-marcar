<?php

class DemandaArquivoFluxoModel extends MY_Model {
	function __construct() {
		parent::__construct();
		$this->table = 'demanda_arquivo_fluxo';
	}

	function removerPorIdDemanda($id) {
		$sql = "delete from demanda_arquivo_fluxo where id_demanda_fluxo in (select id_demanda_fluxo from demanda_fluxo where id_demanda = ?)";
        return $query = $this->db->query($sql, array($id));
    }

    function buscarArquivosPorIdDemandaFluxoEId($idDemanda, $idArquivo) {
		$sql = "SELECT 
				id_demanda_arquivo_fluxo,
				nome,
				arquivo
				FROM demanda_arquivo_fluxo
				WHERE 
				id_demanda_fluxo = ? AND
				id_demanda_arquivo_fluxo = ?";

        $query = $this->db->query($sql, array($idDemanda, $idArquivo));

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null;
        }
	}	
}