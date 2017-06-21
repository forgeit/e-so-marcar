<?php

class QuadraModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'quadra';
    }

    function buscarTodosNativo($idCliente, $valido = null) {
        $sql = "SELECT 
                    *,
                    CASE WHEN p.situacao THEN 'Ativo' ELSE 'Desativo' END as situacao
                FROM quadra p
                WHERE p.id_cliente = ?";

        if ($valido != null) {
            $sql .= " AND p.situacao ";
        }

        if ($valido == null) {
            $query = $this->db->query($sql, array($idCliente));
        } else {
            $query = $this->db->query($sql, array($idCliente));
        }

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

}
