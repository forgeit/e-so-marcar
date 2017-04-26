<?php

class AnuncioModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'anuncio';
    }

    function buscarTodosNativo($idCliente, $id = null) {
        $sql = "SELECT 
                    p.id, 
                    p.titulo, 
                    DATE_FORMAT(p.data_inicial,'%d/%m/%Y') AS data_inicial,
                    DATE_FORMAT(p.data_final,'%d/%m/%Y') AS data_final,
                    CASE WHEN p.ativo THEN 'Ativo' ELSE 'Desativo' END as ativo, 
                    t.nome as tipo
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

    function buscarPorIdNativo($id) {
        $sql = "SELECT 
                    p.*, 
                    DATE_FORMAT(p.data_inicial,'%d/%m/%Y') AS data_inicial,
                    DATE_FORMAT(p.data_final,'%d/%m/%Y') AS data_final
                FROM anuncio p
                WHERE p.id = ?";

        $query = $this->db->query($sql, array($id));

        if ($query->num_rows() > 0) {
            return $query->row_array();
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
