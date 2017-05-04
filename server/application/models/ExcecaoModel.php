<?php

class ExcecaoModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'horario_excecao';
    }

    function buscarTodosNativo($idCliente, $id = null) {
        $sql = "SELECT 
                    r.id,
                    q.titulo as quadra,
                    DATE_FORMAT(r.data_hora_inicial,'%d/%m/%Y %H:%i') AS data_hora_inicial,
                    DATE_FORMAT(r.data_hora_final,'%d/%m/%Y %H:%i') AS data_hora_final,
                    CONCAT('R$ ', REPLACE(r.valor, '.', ',')) as valor
                FROM horario_excecao r
                JOIN quadra q ON q.id = r.id_quadra
                WHERE r.id_cliente = ?";

        if ($id != null) {
            $sql .= " AND r.id_quadra = ?";
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
                    DATE_FORMAT(p.data_hora_inicial,'%d/%m/%Y %H:%i') AS data_hora_inicial,
                    DATE_FORMAT(p.data_hora_final,'%d/%m/%Y %H:%i') AS data_hora_final
                FROM horario_excecao p
                WHERE p.id = ?";

        $query = $this->db->query($sql, array($id));

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null;
        }
    }

    function isHorarioNaoJogar($idQuadra, $idCliente, $dataHora) {
        $sql = "
            SELECT 
                *
            FROM horario_excecao e
            WHERE data_hora_inicial <= ?
            AND data_hora_final >= ?
            AND id_quadra = ?
            AND id_cliente = ?
            AND NOT flag_pode_jogar";

        $query = $this->db->query($sql, array($dataHora, $dataHora, $idQuadra, $idCliente));

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null;
        }
    }

    function buscarHorarioJogar($idQuadra, $idCliente, $dataHora) {
        $sql = "
            SELECT 
                *
            FROM horario_excecao e
            WHERE data_hora_inicial >= ?
            AND data_hora_final <= ?
            AND id_quadra = ?
            AND id_cliente = ?
            AND flag_pode_jogar";

        $query = $this->db->query($sql, array($dataHora, $dataHora, $idQuadra, $idCliente));

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null;
        }
    }

}
