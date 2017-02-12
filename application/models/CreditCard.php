<?php

/**
 * Created by PhpStorm.
 * User: papanav
 * Date: 4/15/2016
 * Time: 11:15 PM
 */
class CreditCard {
    public $type, $number, $expDate, $cvv;

    public function __construct($type, $number, $expDate, $cvv) {
        $this->type = $type;
        $this->number = $number;
        $this->expDate = $expDate;
        $this->cvv = $cvv;
    }
}