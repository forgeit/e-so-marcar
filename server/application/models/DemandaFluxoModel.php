<?php

class DemandaFluxoModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'demanda_fluxo';
    }

    function removerPorIdDemanda($id) {
        $sql = "delete from demanda_fluxo where id_demanda = ?";
        return $query = $this->db->query($sql, array($id));
    }

    function buscarPorPessoa($id) {
        $sql = "SELECT
				id_demanda
				FROM demanda_fluxo d
				WHERE id_pessoa = ?";

        $query = $this->db->query($sql, array($id));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

    function buscarArquivosPorDemanda($id) {
        $sql = "SELECT
				*
				FROM demanda_arquivo_fluxo d
				WHERE id_demanda_fluxo = ?";

        $query = $this->db->query($sql, array($id));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

    function buscarFluxoPorIdDemanda($id) {
        $sql = "select 
				df.id_demanda_fluxo,
				df.descricao AS descricao,
				p.nome AS pessoa,
				s.descricao AS situacao,
				DATE_FORMAT(df.ts_transacao, '%d/%m/%Y %H:%i:%s') AS tsTransacao,
				count(daf.id_demanda_arquivo_fluxo) AS total
				from demanda_fluxo df
				left join pessoa p on p.id_pessoa = df.id_pessoa
				join situacao s on s.id_situacao = df.id_situacao
				left join demanda_arquivo_fluxo daf on daf.id_demanda_fluxo = df.id_demanda_fluxo
				WHERE
				df.id_demanda = ?
				GROUP BY 1, 2, 3, 4
				ORDER BY ts_transacao";

        $query = $this->db->query($sql, array($id));

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

}
