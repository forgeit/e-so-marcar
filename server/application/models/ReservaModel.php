<?php

class ReservaModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'reserva';
    }

    function buscarTodosNativo($idCliente) {
        $sql = "SELECT 
                    r.id, 
                    q.titulo as quadra, 
                    CASE WHEN u.nome THEN u.nome ELSE u.email END as usuario, 
                    DATE_FORMAT(r.data_hora_reserva,'%d/%m/%Y %H:%i') AS data_hora_reserva,
                    CONCAT('R$ ', REPLACE(r.valor, '.', ',')) as valor
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
                    DATE_FORMAT(p.data_hora_reserva,'%d/%m/%Y %H:%i') AS data_hora_reserva
                FROM reserva p
                WHERE p.id = ?";

        $query = $this->db->query($sql, array($id));

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null;
        }
    }

}
