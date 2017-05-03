<?php

class HorarioModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'horario';
    }

    function buscarTodosNativo($idCliente, $id = null) {
        $sql = "SELECT 
                    r.id,
                    q.titulo as quadra,
                    d.nome as dia,
                    DATE_FORMAT(r.hora_inicial,'%H:%i') AS hora_inicial,
                    DATE_FORMAT(r.hora_final,'%H:%i') AS hora_final,
                    CONCAT('R$ ', REPLACE(r.valor, '.', ',')) as valor
                FROM horario r
                JOIN quadra q ON q.id = r.id_quadra
                JOIN dia_semana d ON d.id = r.dia_semana
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

}
