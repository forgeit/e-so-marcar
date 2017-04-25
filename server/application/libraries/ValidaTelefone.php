<?php

class ValidaTelefone {

    function __construct($valor = null) {
        // Deixa apenas números no valor
        $this->valor = preg_replace('/[^0-9]/', '', $valor);

        // Garante que o valor é uma string
        $this->valor = (string) $this->valor;
    }

    public function valida() {
        return preg_match('#^[0-9]{10}[0-9]?$#', $this->valor) > 0;
    }

}
