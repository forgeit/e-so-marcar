<?php

class LogradouroModel extends MY_Model {
	function __construct() {
		parent::__construct();
		$this->table = 'logradouro';
	}

	function buscarPorDescricaoBairro($descricao, $bairro) {
		$sql = "SELECT 
				id_logradouro, nome
				FROM logradouro
				WHERE nome LIKE ? AND id_bairro = ? ORDER BY nome";

        $query = $this->db->query($sql, array('%' . $descricao . '%', $bairro));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
	}

	function buscarPorNomeBairro($descricao, $bairro) {
		$sql = "SELECT 
				id_logradouro, nome
				FROM logradouro
				WHERE nome = ? AND id_bairro = ? ORDER BY nome";

        $query = $this->db->query($sql, array($descricao, $bairro));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
	}

	function buscarTodosPorBairro($bairro) {
		$sql = "SELECT 
				id_logradouro, nome
				FROM logradouro
				WHERE id_bairro = ? ORDER BY nome";

        $query = $this->db->query($sql, array($bairro));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
	}
}