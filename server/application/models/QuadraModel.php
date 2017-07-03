<?php

class QuadraModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'quadra';
    }
    
    function buscarPorIdNativo($id) {
        $sql = "SELECT 
                q.*,
                tq.nome as tipo_quadra,
                tl.nome as tipo_local
            FROM quadra q
            JOIN tipo_quadra tq ON tq.id = q.id_tipo_quadra
            JOIN tipo_local tl ON tl.id = q.id_tipo_local
            WHERE q.id = ?";

        $query = $this->db->query($sql, array($id));

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null;
        }
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
