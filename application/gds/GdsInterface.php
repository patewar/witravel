<?php

   class GdsInterface {
    protected $wiconfig, $requestType;
    protected $request, $response;
    protected $status, $message;

    public function __construct($wiconfig = array()) {
        $this->wiconfig = $wiconfig;      
    }

    public function setRequest($request) {
      $this->request = $request; //Model Object, includes all request parameters
    }

    public function setResponse($response) {
      $this->response = $response;
    }

    public function getRequest() {
      return $this->request;
    }

    public function getMessage() {
      return $this->message;
    }

    public function getResponse() {
      return $this->response;
    }

    public function sendRequest() {
      $client = new nusoap_client($this->getEndPoint(), false);

      $client->setCredentials($this->wiconfig['Travelport']['USERNAME'], $this->wiconfig['Travelport']['PASSWORD']);
      // print_r($client); exit;
      $client->setHTTPEncoding();
      $result = $client->send($this->reqXML, $this->getEndPoint());
       

      if(!empty($result) && is_array($result)) {
        array_walk_recursive($result, function (&$elem) {
            if (is_string($elem)) {
               //$elem = iconv('ISO-8895', 'UTF-8', $elem);
               $elem = utf8_encode($elem);
            }
        });  
        $this->status = true;
        $this->responseJson = Zend_Json::encode($result);      
        
      }else if ($client->fault) {
        $this->status = false;
        $this->message =  'Fault: ' . print_r($result, 1);
      } else {
        // check result
        $err_msg = $client->getError();
       // var_dump($err_msg);exit;
        if ($err_msg) {
          // Print error msg
          $this->status = false;
          $this->message =  'Error: ' . $err_msg;
        }
      }

    }



    public function prepareRequest() {}
    // public function sendRequest() {}
    public function parseResponse() {}

    public function getFlightResults() {}
    public function getHotelResults() {}

    public function getFlightDetails() {}
    public function getHotelDetails() {}

    public function bookFlight() {}
    public function bookHotel() {}



  }
