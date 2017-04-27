<?php

class QuadraModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'quadra';
    }

    function buscarTodosNativo($idCliente, $id = null) {
        $sql = "SELECT 
                    *,
                    CASE WHEN p.situacao THEN 'Ativo' ELSE 'Desativo' END as situacao
                FROM quadra p
                WHERE p.id_cliente = ?";

        if ($id != null) {
            $sql .= " AND t.id_tipo_quadra = ?";
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

}
