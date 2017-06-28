<?php

class AnuncioModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'anuncio';
    }

    function buscarTodosNativo($idCliente, $id = null) {
        $sql = "SELECT 
                    p.id, 
                    p.titulo, 
                    DATE_FORMAT(p.data_inicial,'%d/%m/%Y') AS data_inicial,
                    DATE_FORMAT(p.data_final,'%d/%m/%Y') AS data_final,
                    CASE WHEN p.ativo THEN 'Ativo' ELSE 'Desativo' END as ativo, 
                    t.nome as tipo
                FROM anuncio p
		LEFT JOIN tipo_anuncio t ON t.id = p.id_tipo_anuncio 
                WHERE p.id_cliente = ?";

        if ($id != null) {
            $sql .= " AND t.id_tipo_anuncio = ?";
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

    function buscarPorIdNativo($id) {
        $sql = "SELECT 
                    p.*, 
                    DATE_FORMAT(p.data_inicial,'%d/%m/%Y') AS data_inicial,
                    DATE_FORMAT(p.data_final,'%d/%m/%Y') AS data_final
                FROM anuncio p
                WHERE p.id = ?";

        $query = $this->db->query($sql, array($id));

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null;
        }
    }
    
    function buscarPorClientePorTipo($idCliente, $idTipoAnuncio) {
        $sql = "SELECT 
                    titulo,
                    imagem,
                    url_direcionamento
                FROM anuncio a
                WHERE a.id_cliente = ?
                AND a.id_tipo_anuncio = ?
                AND current_date() >= a.data_inicial
                AND current_date() <= a.data_final";

        $query = $this->db->query($sql, array($idCliente, $idTipoAnuncio));

        if ($query->num_rows() > 0) {
            return $query->row_array();
        } else {
            return null;
        }
    }

    function anuncioNaoUnico($idCliente, $anuncio) {
        $sql = "SELECT 
                    COUNT(*) > 0 as retorno
		FROM anuncio p
		WHERE id_cliente = ?
                AND id_tipo_anuncio = ?
                AND (
                    (data_inicial >= ? AND data_inicial <= ?)
                    OR 
                    (data_final >= ? AND data_final <= ?)
                )";

        if (isset($anuncio->id)) {
            $sql .= " AND id <> ?";
            $query = $this->db->query($sql, array($idCliente,
                $anuncio->id_tipo_anuncio,
                $anuncio->data_inicial,
                $anuncio->data_final,
                $anuncio->data_inicial,
                $anuncio->data_final,
                $anuncio->id
            ));
        } else {
            $query = $this->db->query($sql, array($idCliente,
                $anuncio->id_tipo_anuncio,
                $anuncio->data_inicial,
                $anuncio->data_final,
                $anuncio->data_inicial,
                $anuncio->data_final
            ));
        }

        if ($query->num_rows() > 0) {
            return $query->row_array()['retorno'];
        } else {
            return null;
        }
    }

}
