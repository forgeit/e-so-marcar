<?php

class UsuarioModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'usuario';
    }

    function verificarLogin($login, $senha) {

        $sql = "SELECT 
                    id, nome, email, flag_email_confirmado
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

}
