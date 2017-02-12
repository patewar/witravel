<?php

class WiTravelBaseController extends Zend_Controller_Action {
	protected $gds;

  public function WiTravelBaseController($request, $response, $invokeArgs) {
  	$wiconfig = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOption('wiconfig');
	$this->gds = GdsFactory::getGdsProvider($wiconfig, 'TRAVELPORT');
    parent::__construct($request, $response, $invokeArgs);
  }


  public function getTemplateConfig() {


  }


  public function getNextFriday() {
    return '2016-05-20';
  }


  public function getNextSunday() {
    return '2016-05-22';
  }
}
