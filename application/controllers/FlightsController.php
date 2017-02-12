<?php
class FlightsController extends WiTravelBaseController {
    public function init() {
      $this->getHelper('ViewRenderer')
        ->setNoRender();

        $this->getResponse()
         ->setHeader('Content-Type', 'application/json');
    }

    public function indexAction() {
        echo __METHOD__;
    }

    public function lowFareSearchAction() {
        
        $request = Zend_Controller_Front::getInstance()->getRequest();

        //$origin = Utils::geoLocation()['city_code'];
        $origin = $request->getParam('origin')? $request->getParam('origin'): 'Madrid'; //$this->getOrigin()
        $origin = Utils::getCityCodeFromName($origin);
	//var_dump($origin); exit;
        $budget = $request->getParam('budget')?$request->getParam('budget'): 1200;
        $travellers = $request->getParam('travellers')?$request->getParam('travellers'): 1;
        $destinations = $request->getParam('destinations')? explode(',', $request->getParam('destinations')): $wiconfig['Destinations'];

        $startDate = $request->getParam('startDate')?$request->getParam('startDate'): $this->getNextFriday();
        $endDate = $request->getParam('endDate')?$request->getParam('endDate'): $this->getNextSunday();
        //$startDate = '2016-10-14';
        //$endDate = '2016-10-16';
        $twoWay = filter_var($request->getParam('twoWay'), FILTER_VALIDATE_BOOLEAN);

        //TODO: read the req params
        $search = new FlightSearchCriteria();
        $search->setFromPlace($origin);
        $search->setFromDate($startDate);
        $search->setToDate($endDate);
        $search->setToPlaces($destinations);
        $search->setBudget($budget);
        $search->setNumPassengers($travellers);
        $search->setIs2Way($twoWay);
         
        //send request
        // $gds = new GdsProvider();
        // $gds = $gds->getGdsObj('TRAVELPORT');
        $this->gds->setRequest($search);
        
        $results = $this->gds->getFlightResults();

        echo $results;



    }

    public function flightReservationAction() {

        // $wiconfig = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOption('wiconfig');
        $request = Zend_Controller_Front::getInstance()->getRequest()->getRawBody();
        $request=json_decode($request);

        
        $transid =$request->transid;
        $status =$request->status;
        $totalamount=$request->amount;
        $first_name=$request->first_name;
        $last_name=$request->last_name;
        $email='johnsmith@travelportuniversalapidemo.com';//$request->email;
        $phone=$request->phone;
        $maskedcc=$request->maskedcc;
        $cc_num=$request->cc_num;
        $cc_name=$request->first_name;
        $cc_type=strtoupper(substr($request->cc_type,0,2));
        $cc_exp_mon=$request->cc_exp_mon;
        $cc_exp_year=$request->cc_exp_year;
        $cc_cvv=$request->cc_cvv;
        $startDate=$request->startDate;
        $endDate=$request->endDate;
        $prefix='Mr';
        //TODO: read the req params
        $reservation = new FlightReservationCriteria();
        $reservation->hotelCode = 53495;
        $reservation->numAdults = 1;
        $reservation->checkinDate = Utils::getNextFriday();
        $reservation->checkoutDate = Utils::getNextSunday();
        $reservation->numRooms = '1';

        //traveller
        $traveller = new Traveller($prefix,$first_name,$last_name,'40','1976-01-21','M','US',$email);
//'40','1976-01-21','M','US','johnsmith@travelportuniversalapidemo.com');

        //delivery address
        $shippingAddr = new Address();
        $shippingAddr->street = 'Via Augusta 595';
        $shippingAddr->city = 'Madrid';
        $shippingAddr->postalCode = '50156';
        $traveller->shippingAddress = $shippingAddr;
        
        //address
        $addr = $shippingAddr;
        $addr->name = 'DemoSiteAddress';
        $addr->state = 'IA';
        $addr->postalCode = '50156';
        $addr->country = 'US';
        $traveller->address = $addr;
        

        //phone
       // $phone = new Phone('DEN', '1', '303', '123456789');
         $phone = new Phone('DEN', '1', '303', $phone);
        $traveller->phone = $phone;

        //credit card
        $cc = new CreditCard($cc_type, $cc_num, '2017-'.$cc_exp_mon, $cc_cvv);
        $traveller->creditCard = $cc;

        $reservation->traveller = $traveller;
        $reservation->airsegment=$request->AirSegment;
        $reservation->airitinerary=$request->AirItinerary;
        $reservation->airpriceresult=$request->AirPriceResult;
        //var_dump($reservation);exit;

        //send request
        // $gds = GdsProvider::getGdsObj();
        // $gds = new GdsProvider();
        // $gds = $gds->getGdsObj('TRAVELPORT')*/;
        $this->gds->setRequest($reservation);
        $results = $this->gds->bookFlight();
//echo "<pre>";var_dump($results);exit;
        echo $results;



    }
    public function flightPricingAction() {
        // $wiconfig = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOption('wiconfig');
        $request = Zend_Controller_Front::getInstance()->getRequest()->getRawBody();
        $request=json_decode($request);
        //echo "prem" ;
        
       // var_dump($request);exit;

        //send request
        // $gds = GdsProvider::getGdsObj();
        // $gds = new GdsProvider();
        // $gds = $gds->getGdsObj('TRAVELPORT');
        $this->gds->setRequest($request);
        $results = $this->gds->PriceListFlight();
//echo "<pre>";var_dump($results);exit;
        echo $results;



    }


}
