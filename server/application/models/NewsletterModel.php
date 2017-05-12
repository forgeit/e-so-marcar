<?php

class NewsletterModel extends MY_Model {

    function __construct() {
        parent::__construct();
        $this->table = 'newsletter';
    }

}
