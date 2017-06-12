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
    
    function buscarEsportes($idQuadra) {
        $sql = "SELECT te.nome FROM quadra q 
                JOIN quadra_esporte qe ON qe.id_quadra = q.id
                JOIN tipo_esporte te ON te.id = qe.id_esporte
                WHERE q.id = ?";

        $query = $this->db->query($sql, array($idQuadra));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

}
