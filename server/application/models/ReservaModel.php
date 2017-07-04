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
                    CASE WHEN u.nome IS NOT NULL THEN CONCAT(u.nome, ' - ', u.email) ELSE u.email END as usuario, 
                    CASE WHEN u.telefone IS NOT NULL THEN u.telefone ELSE '-' END as telefone, 
                    DATE_FORMAT(r.data_hora_reserva,'%d/%m/%Y %H:%i') AS data_hora_reserva,
                    CONCAT('R$ ', REPLACE(r.valor, '.', ',')) as valor
                FROM reserva r
		LEFT JOIN quadra q ON q.id = r.id_quadra
                LEFT JOIN usuario u ON u.id = r.id_usuario
                WHERE r.id_cliente = ? ";
                //AND r.data_hora_reserva >= CURDATE() ";

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
    
    function buscarParaCancelamento($id) {
        $sql = "SELECT 
                    p.*,
                    SUBTIME(p.data_hora_reserva, CONCAT('0 ', e.horas_antecedencia_cancelamento ,':0:0')) as data_possivel_cancelamento,
                    e.horas_antecedencia_cancelamento
                FROM reserva p
                JOIN cliente e ON e.id = p.id_cliente
                WHERE p.id = ?";

        $query = $this->db->query($sql, array($id));

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null;
        }
    }
    
    function buscarEmAberto($idUsuario) {
        $sql = "SELECT 
                    r.id,
                    r.id_cliente,
                    c.nome_fantasia as cliente,
                    c.logo as logo_cliente,
                    r.id_quadra,
                    q.titulo as quadra, 
                    DATE_FORMAT(r.data_hora_reserva,'%d/%m/%Y %H:%i') AS data_hora_reserva,
                    CONCAT('R$ ', REPLACE(r.valor, '.', ',')) as valor,
                    d.nome as dia_semana
                FROM reserva r
		LEFT JOIN quadra q ON q.id = r.id_quadra
                LEFT JOIN cliente c ON c.id = r.id_cliente
                LEFT JOIN usuario u ON u.id = r.id_usuario
                LEFT JOIN dia_semana d ON d.id = WEEKDAY(r.data_hora_reserva) + 2
                WHERE r.id_usuario = ? 
                AND r.data_hora_reserva >= CURDATE()
                ORDER BY r.data_hora_reserva ASC";

        $query = $this->db->query($sql, array($idUsuario));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }
    
    function buscarEmAbertoPorQuadra($idQuadra) {
        $sql = "SELECT 
                    r.id
                FROM reserva r
                WHERE r.id_quadra = ? 
                AND r.data_hora_reserva >= CURDATE()";

        $query = $this->db->query($sql, array($idQuadra));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

}
