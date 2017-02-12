<?php

require_once(__DIR__ . '/Address.php');
require_once(__DIR__ . '/Phone.php');
require_once(__DIR__ . '/CreditCard.php');

/**
 * Created by PhpStorm.
 * User: papanav
 * Date: 4/15/2016
 * Time: 9:59 PM
 */
class Traveller {
    public $prefix, $firstName, $lastName;
    public $age, $dob, $gender, $nationality, $email;
    public $type = 'ADT';
    public $phone, $shippingAddress, $address, $creditCard;

    public function __construct($prefix, $firstName, $lastName, $age, $dob, $gender, $nationality, $email) {
        $this->prefix = $prefix;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->age = $age;
        $this->dob = $dob;
        $this->gender = $gender;
        $this->nationality = $nationality;
        $this->email = $email;
    }
}