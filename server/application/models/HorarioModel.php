<?php

class HorarioModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'horario';
    }

    function horarioDisponivel($idCliente, $idQuadra, $dataHora) {
        $sql = "SELECT * FROM horario
                    WHERE id_cliente = ?
                    AND id_quadra = ?
                    AND dia_semana = dayofweek(?)
                    AND hora_inicial = DATE_FORMAT(?,'%H:%i:%s')";

        $query = $this->db->query($sql, array($idCliente, $idQuadra, $dataHora, $dataHora));

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null;
        }
    }

    function horarioExiste($idCliente, $idUsuario, $diaSemana, $dataHoraIni, $dataHoraFim, $idHorario = null) {
        $sql = "SELECT * FROM horario
                    WHERE id_cliente = ?
                    AND id_quadra = ?
                    AND dia_semana = ?
                    AND (
                         (hora_inicial < ? AND hora_final > ?)
                    )";
        
        if ($idHorario == null) {
            $query = $this->db->query($sql, array($idCliente, $idUsuario, $diaSemana, $dataHoraFim, $dataHoraIni));
        } else {
            $sql .= " AND id <> ?";
            $query = $this->db->query($sql, array($idCliente, $idUsuario, $diaSemana, $dataHoraFim, $dataHoraIni, $idHorario));
        }
        
        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null;
        }
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

    function buscarHorario($idQuadra, $idCliente, $diaSemana, $hora) {
        $sql = "
            SELECT 
                *
            FROM horario
            WHERE hora_inicial = ?
            AND dia_semana = ?
            AND id_quadra = ?
            AND id_cliente = ?";

        $query = $this->db->query($sql, array($hora, $diaSemana, $idQuadra, $idCliente));

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null;
        }
    }
    
    function buscarSomenteReservas($idCliente) {
        $sql = '
                        SELECT 
                   q.titulo as "title",
                   CONCAT(DATE(data_hora_reserva), "T", TIME(data_hora_reserva)) as "start",
                   CASE WHEN r.id IS NULL THEN "livre" ELSE "reservado" END as "className",
                   CONCAT("R$ ", REPLACE(r.valor, ".", ",")) as valor
           FROM reserva r
           JOIN quadra q ON q.id = r.id_quadra
           WHERE r.id_cliente = ?
           AND r.data_hora_reserva >= CURDATE()';

        $query = $this->db->query($sql, array($idCliente));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

    function buscarReservas($idQuadra) {
        $sql = '
             SELECT 
                CASE WHEN r.id IS NULL THEN "Livre" ELSE "Reservado" END as "title",
                CONCAT(dias.d, "T", hora_inicial) as "start",
                CONCAT(dias.d, "T", hora_final) as "end",
                CASE WHEN r.id IS NULL THEN "livre" ELSE "reservado" END as "className",
                CONCAT("R$ ", REPLACE(h.valor, ".", ",")) as valor
            FROM (
                SELECT 
                        CAST((SYSDATE()+INTERVAL (H+T+U) DAY) AS date) as d,
                        CASE WHEN WEEKDAY(CAST((SYSDATE()+INTERVAL (H+T+U) DAY) AS date)) = 6 THEN 1 ELSE WEEKDAY(CAST((SYSDATE()+INTERVAL (H+T+U) DAY) AS date)) + 2 END as dow
                FROM ( SELECT 0 H
                    UNION ALL SELECT 100 UNION ALL SELECT 200 UNION ALL SELECT 300
                  ) H CROSS JOIN ( SELECT 0 T
                    UNION ALL SELECT  10 UNION ALL SELECT  20 UNION ALL SELECT  30
                    UNION ALL SELECT  40 UNION ALL SELECT  50 UNION ALL SELECT  60
                    UNION ALL SELECT  70 UNION ALL SELECT  80 UNION ALL SELECT  90
                  ) T CROSS JOIN ( SELECT 0 U
                    UNION ALL SELECT   1 UNION ALL SELECT   2 UNION ALL SELECT   3
                    UNION ALL SELECT   4 UNION ALL SELECT   5 UNION ALL SELECT   6
                    UNION ALL SELECT   7 UNION ALL SELECT   8 UNION ALL SELECT   9
                  ) U
                WHERE
                  (SYSDATE()+INTERVAL (H+T+U) DAY) <= (SYSDATE()+INTERVAL 3 MONTH)) as dias 
            JOIN horario h ON dias.dow = h.dia_semana
            LEFT JOIN reserva r ON r.id_quadra = h.id_quadra AND CONCAT(dias.d, "T", hora_inicial) = r.data_hora_reserva
            LEFT JOIN horario_excecao e ON e.id_quadra = h.id_quadra AND NOT e.flag_pode_jogar AND
                (
                    (CONCAT(dias.d, "T", hora_inicial) >= e.data_hora_inicial AND CONCAT(dias.d, "T", hora_inicial) <= e.data_hora_final)
                    OR (CONCAT(dias.d, "T", hora_final) >= e.data_hora_inicial AND CONCAT(dias.d, "T", hora_final) <= e.data_hora_final)
                )
            WHERE h.id_quadra = ?
            AND e.id IS NULL';

        $query = $this->db->query($sql, array($idQuadra));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

}
