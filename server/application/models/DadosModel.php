<?php

class DadosModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'cliente';
    }

}
