<?php

class ClienteModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'cliente';
    }

    function verificarLogin($login, $senha) {

        $sql = "SELECT 
				id, nome_fantasia, logo
				FROM cliente
				WHERE email = ? AND senha = ?";

        $query = $this->db->query($sql, array($login, $senha));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

}
