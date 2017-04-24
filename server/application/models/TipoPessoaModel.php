<?php

class TipoPessoaModel extends MY_Model {
	function __construct() {
		parent::__construct();
		$this->table = 'tipo_pessoa';
	}
}