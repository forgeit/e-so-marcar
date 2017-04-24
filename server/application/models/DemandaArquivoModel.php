<?php

class DemandaArquivoModel extends MY_Model {
	function __construct() {
		parent::__construct();
		$this->table = 'demanda_arquivo';
	}

	function removerPorIdDemanda($id) {
		$sql = "delete from demanda_arquivo where id_demanda = ?";
        return $query = $this->db->query($sql, array($id));
    }

	function buscarArquivosPorIdDemanda($id) {
		$sql = "SELECT 
				id_demanda_arquivo,
				nome,
				arquivo
				FROM demanda_arquivo
				WHERE 
				id_demanda = ?";

        $query = $this->db->query($sql, array($id));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
	}	

	function buscarArquivosPorIdDemandaEId($idDemanda, $idArquivo) {
		$sql = "SELECT 
				id_demanda_arquivo,
				nome,
				arquivo
				FROM demanda_arquivo
				WHERE 
				id_demanda = ? AND
				id_demanda_arquivo = ?";

        $query = $this->db->query($sql, array($idDemanda, $idArquivo));

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null;
        }
	}	
}