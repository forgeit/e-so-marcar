<?php

class ArquivoModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'arquivo';
    }

}
