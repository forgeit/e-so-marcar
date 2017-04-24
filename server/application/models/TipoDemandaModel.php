<?php

class TipoDemandaModel extends MY_Model {
	function __construct() {
		parent::__construct();
		$this->table = 'tipo_demanda';
	}

	function buscarPorDescricao($descricao, $id = 0) {
		$sql = "SELECT 
				count(*) as total
				FROM tipo_demanda
				WHERE
				descricao = ? ";

		if ($id !== 0) {
			$sql .= " AND id_tipo_demanda <> ?";
		}

		if ($id === 0) {
			$query = $this->db->query($sql, array($descricao));
		} else {
			$query = $this->db->query($sql, array($descricao, $id));
		}
        
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
	}
}