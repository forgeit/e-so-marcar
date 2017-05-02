<?php

class ReservaModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'anuncio';
    }

    function buscarTodosNativo($idCliente) {
        $sql = "SELECT 
                    r.id, 
                    q.titulo as quadra, 
                    u.nome as usuario,
                    DATE_FORMAT(r.data_hora,'%d/%m/%Y %H:%i') AS data_hora
                FROM reserva r
		LEFT JOIN quadra q ON q.id = r.id_quadra
                LEFT JOIN usuario u ON u.id = r.id_usuario
                WHERE r.id_cliente = ?";

        $query = $this->db->query($sql, array($idCliente));

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

    function anuncioNaoUnico($idCliente, $anuncio) {
        $sql = "SELECT 
                    COUNT(*) > 0 as retorno
		FROM anuncio p
		WHERE id_cliente = ?
                AND id_tipo_anuncio = ?
                AND (
                    (data_inicial >= ? AND data_inicial <= ?)
                    OR 
                    (data_final >= ? AND data_final <= ?)
                )";

        if (isset($anuncio->id)) {
            $sql .= " AND id <> ?";
            $query = $this->db->query($sql, array($idCliente,
                $anuncio->id_tipo_anuncio,
                $anuncio->data_inicial,
                $anuncio->data_final,
                $anuncio->data_inicial,
                $anuncio->data_final,
                $anuncio->id
            ));
        } else {
            $query = $this->db->query($sql, array($idCliente,
                $anuncio->id_tipo_anuncio,
                $anuncio->data_inicial,
                $anuncio->data_final,
                $anuncio->data_inicial,
                $anuncio->data_final
            ));
        }

        if ($query->num_rows() > 0) {
            return $query->row_array()['retorno'];
        } else {
            return null;
        }
    }

}
