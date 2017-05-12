<?php

class QuadraEsporteModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'quadra_esporte';
    }
    
    function removerPorIdQuadra($id) {
		$sql = "delete from quadra_esporte where id_quadra = ?";
        return $query = $this->db->query($sql, array($id));
    }

}
