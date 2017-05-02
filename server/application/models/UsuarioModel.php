<?php

class UsuarioModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'usuario';
    }

}
