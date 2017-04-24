<?php

class PessoaModel extends MY_Model {
	function __construct() {
		parent::__construct();
		$this->table = 'pessoa';
	}

	function buscarTodosNativo($id = null) {
		$sql = "SELECT 
				p.id_pessoa, 
                p.nome, 
                CASE WHEN p.email IS NULL THEN '-' ELSE p.email END AS email, 
                CASE WHEN p.celular IS NULL THEN '-' ELSE p.celular END AS celular, 
                t.descricao as tipo
				FROM pessoa p
				JOIN tipo_pessoa t ON t.id_tipo_pessoa = p.id_tipo_pessoa ";

        if ($id != null) {
            $sql .= " WHERE t.id_tipo_pessoa = ?";
        }

        if ($id == null) {
            $query = $this->db->query($sql);
        } else {
            $query = $this->db->query($sql, array($id));
        }

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
	}

	function buscarCombo() {
		$sql = "SELECT id_pessoa, nome AS nome FROM pessoa ORDER BY 2";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
	}

	function buscarComboFiltro($filtro) {
		$sql = "SELECT id_pessoa, concat(nome, ' <', email, '>') AS nome FROM pessoa WHERE nome LIKE ? ORDER BY 2";

        $query = $this->db->query($sql, array('%' . $filtro . '%'));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
	}

	function buscarPorEmail($email) {
		$sql = "SELECT 
				nome
				FROM pessoa p
				WHERE email = ?";

        $query = $this->db->query($sql, array($email));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
	}

	function buscarPorEmailId($email, $id) {
		$sql = "SELECT 
				nome
				FROM pessoa p
				WHERE email = ? AND id_pessoa <> ?";

        $query = $this->db->query($sql, array($email, $id));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
	}
}