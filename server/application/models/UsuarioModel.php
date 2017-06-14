<?php

class UsuarioModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'usuario';
    }

    function buscarTodosAtivo() {
        $sql = "SELECT 
                   u.*
                FROM usuario u
                WHERE u.flag_ativo
                ORDER BY u.email";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

    function verificarLogin($login, $senha) {

        $sql = "SELECT 
                    id, nome, email, flag_email_confirmado, flag_ativo
                FROM usuario
                WHERE email = ? AND senha = ?
                LIMIT 1";

        $query = $this->db->query($sql, array($login, $senha));

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null;
        }
    }

    function buscarPorIdNativo($id) {
        $sql = "SELECT 
                    p.*, 
                    DATE_FORMAT(p.data_nascimento,'%d/%m/%Y') AS data_nascimento
                FROM usuario p
                WHERE p.id = ?";

        $query = $this->db->query($sql, array($id));

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null;
        }
    }

}
