<?php
class HotelsController extends WiTravelBaseController
{

    public function init()
    {
      $this->getHelper('ViewRenderer')
        ->setNoRender();

        $this->getResponse()
         ->setHeader('Content-Type', 'application/json');
    }

    public function indexAction()
    {
      echo __METHOD__;
    }

    public function hotelSearchAction() {
        $wiconfig = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOption('wiconfig');

        $request = Zend_Controller_Front::getInstance()->getRequest();

        $location = $request->getParam('location');
        // $budget = $request->getParam('budget')?$request->getParam('budget'): 200;
        $adults = $request->getParam('adults')?$request->getParam('adults'): 1;

        $startDate = $request->getParam('startDate')?$request->getParam('startDate'): $this->getNextFriday();
        $endDate = $request->getParam('endDate')?$request->getParam('endDate'): $this->getNextSunday();


        //TODO: read the req params
        $search = new HotelSearchCriteria();
        $search->location = $location;
        $search->numAdults = $adults;
        $search->checkinDate = $startDate;
        $search->checkoutDate = $endDate;
        $search->numRooms = ceil($search->numAdults/2);

        //send request
        // $gds = GdsProvider::getGdsObj();
        $this->gds->setRequest($search);
        $results = $this->gds->getHotelResults();

        echo $results;

    }

    public function cacheHotelSearchAction() {
        // $wiconfig = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOption('wiconfig');

        $request = Zend_Controller_Front::getInstance()->getRequest();

        $location = $request->getParam('location');
        // $budget = $request->getParam('budget')?$request->getParam('budget'): 200;
        $adults = $request->getParam('adults')?$request->getParam('adults'): 1;

        $startDate = $request->getParam('startDate')?$request->getParam('startDate'): $this->getNextFriday();
        $endDate = $request->getParam('endDate')?$request->getParam('endDate'): $this->getNextSunday();


        //TODO: read the req params
        $hotelsTable = new DBTable_Hotels();
        $hotels = $hotelsTable->fetchAll(
            $hotelsTable->select()
                ->where('Availability = ?', 'Available')
                ->order('city_code ASC')
                ->order('MinimumAmountNum ASC')
        );

        $arrHotels = array();
        foreach ($hotels as $hotel) {
            $arrHotels[$hotel->city_code][] = $hotel->toArray();;
        }

        echo json_encode($arrHotels);



        // $search = new HotelSearchCriteria();
        // $search->location = $location;
        // $search->numAdults = $adults;
        // $search->checkinDate = $startDate;
        // $search->checkoutDate = $endDate;
        // $search->numRooms = ceil($search->numAdults/2);

        // //send request
        // // $gds = GdsProvider::getGdsObj();
        // $this->gds->setRequest($search);
        // $results = $this->gds->getHotelResults();

        // echo $results;

    }    

    public function hotelDetailsAction() {
        // $wiconfig = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOption('wiconfig');
        $request = Zend_Controller_Front::getInstance()->getRequest();

        $results = [];

        if($request->getParam('hotelCode')) {
        //     //TODO: read the req params
            $search = new HotelDetailsCriteria();
            $search->hotelCode = $request->getParam('hotelCode');
            $search->numAdults = $request->getParam('adults')?$request->getParam('adults'): 1;
            $search->numChildren = 0;
            $search->checkinDate = $request->getParam('startDate')? $request->getParam('startDate'): $this->getNextFriday();
            $search->checkoutDate = $request->getParam('endDate')?$request->getParam('endDate'): $this->getNextSunday();
            $search->numRooms = '1';
            $search->hotelChain = 'YX';

            //send request
            // $gds = GdsProvider::getGdsObj();
            $this->gds->setRequest($search);
            $results = $this->gds->getHotelDetails();
        }
        echo $results;

    }

    public function hotelMediaLinksAction() {
        $request = Zend_Controller_Front::getInstance()->getRequest();

        // $wiconfig = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOption('wiconfig');
        $results = array();

        if($request->getParam('hotelCode')) {
            $hotelCode = $request->getParam('hotelCode');
// echo __METHOD__ . $hotelCode; exit;            
            //TODO: read the req params
            $search = new HotelMediaLinksCriteria();
            $search->hotelCode = $hotelCode;

            //send request
            // $gds = GdsProvider::getGdsObj();
            $this->gds->setRequest($search);
            $results = $this->gds->getHotelMediaLinks();
        }
        echo $results;

    }

    public function hotelReservationAction() {
        // $wiconfig = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getOption('wiconfig');
	
	$request = Zend_Controller_Front::getInstance()->getRequest()->getRawBody();
        $request=json_decode($request);
        $selectedHotel=$request->SelectedHotel;
        //var_dump($selectedHotel);exit;
        //TODO: read the req params
        $reservation = new HotelReservationCriteria();
        $reservation->hotelCode = $selectedHotel->HotelCode;
        $reservation->numAdults = "2";
        $reservation->checkinDate = Utils::getNextFriday();
        $reservation->checkoutDate = Utils::getNextSunday();
        $reservation->numRooms = "1";
        
        //traveller
        $traveller = new Traveller('Mr','John','Smith','40','1976-01-21','M','US','johnsmith@travelportuniversalapidemo.com');

        //delivery address
        $shippingAddr = new Address();
        $shippingAddr->street = 'Via Augusta 59 5';
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
        $phone = new Phone('DEN', '1', '303', '123456789');
        $traveller->phone = $phone;

        //credit card
        $cc = new CreditCard('VI', '4444333322221111', '2016-12', '123');
        $traveller->creditCard = $cc;

        $reservation->traveller = $traveller;



        //send request
        // $gds = GdsProvider::getGdsObj();
        $this->gds->setRequest($reservation);
        $results = $this->gds->bookHotel();

        echo $results;

    }

}
