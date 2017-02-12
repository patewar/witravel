<?php

/**
 * Created by PhpStorm.
 * User: papanav
 * Date: 4/15/2016
 * Time: 10:59 PM
 */
class Phone {
    public $location, $countryCode, $areaCode, $number;

    public function __construct($location, $countryCode, $areaCode, $number) {
        $this->location     = $location;
        $this->countryCode  = $countryCode;
        $this->areaCode     = $areaCode;
        $this->number       = $number;
    }
}
