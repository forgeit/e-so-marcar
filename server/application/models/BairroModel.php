<?php

class BairroModel extends MY_Model {
	function __construct() {
		parent::__construct();
		$this->table = 'bairro';
	}

	function buscarTodosPorCidade($cidade) {
		$sql = "SELECT 
				id_bairro, nome
				FROM bairro
				WHERE id_cidade = ? ORDER BY nome";

        $query = $this->db->query($sql, array($cidade));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
	}
}