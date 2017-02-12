<?php
// error_reporting(E_WARNING);

require_once __DIR__ .'/../vendor/autoload.php';

//TODO: autoload
require_once(__DIR__ . '/controllers/WiTravelBaseController.php');
require_once(__DIR__ . '/gds/GdsFactory.php');
// require_once('models/FlightSearchCriteria.php');
// require_once('models/HotelSearchCriteria.php');
// require_once('models/HotelDetailsCriteria.php');
// require_once('models/HotelMediaLinksCriteria.php');
// require_once('models/HotelReservationCriteria.php');
require_once(__DIR__ . '/utils/Utils.php');
foreach (glob(__DIR__ . "/models/*.php") as $filename) {
    require_once $filename;
}
foreach (glob(__DIR__ . "/parsers/*.php") as $filename) {
    require_once $filename;
}

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initAutoload() {
		$autoloader = new Zend_Application_Module_Autoloader(
			array( 'namespace' => 'Default_', 'basePath' => dirname(__FILE__), ));
		return $autoloader;
	}

	protected function _initRestRoute() {
		$this->bootstrap('Request');
		$front = $this->getResource('FrontController');
		$restRoute = new Zend_Rest_Route(
			$front,
			array(),
			array( 'default' => array('version') )
		);
		$front->getRouter()->addRoute('rest', $restRoute);
	}

	protected function _initRequest() {
		$this->bootstrap('FrontController');
		$front = $this->getResource('FrontController');
		$request = $front->getRequest();
		if (null === $front->getRequest()) {
			$request = new Zend_Controller_Request_Http();
			$front->setRequest($request);
		}

		return $request;
	}

}
