<?php

class AnuncioModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'pessoa';
    }

    function buscarTodosNativo($idCliente, $id = null) {
        $sql = "SELECT p.titulo, p.data_inicial, p.data_final, t.nome as tipo
                FROM anuncio p
		LEFT JOIN tipo_anuncio t ON t.id = p.id_tipo_anuncio 
                WHERE p.id_cliente = ?";

        if ($id != null) {
            $sql .= " AND t.id_tipo_anuncio = ?";
        }

        if ($id == null) {
            $query = $this->db->query($sql, array($idCliente));
        } else {
            $query = $this->db->query($sql, array($idCliente, $id));
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
