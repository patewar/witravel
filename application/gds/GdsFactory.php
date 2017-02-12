<?php

class GdsFactory{
	
    public static function getGdsProvider($wiconfig, $source = 'TRAVELPORT') {
        if(strtoupper($source) == 'TRAVELPORT') {
          require_once __DIR__ . '/travelport/Travelport.php';    
          $gds = new Travelport($wiconfig);
        }
        return $gds;
    }	
}