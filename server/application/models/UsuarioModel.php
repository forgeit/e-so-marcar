<?php

class UsuarioModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'usuario';
    }

    function verificarLogin($login, $senha) {

        $sql = "SELECT 
                    id, nome, email
                FROM usuario
                WHERE email = ? AND senha = ?";

        $query = $this->db->query($sql, array($login, $senha));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

}
