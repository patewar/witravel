<?php
  require_once __DIR__ . '/../GdsInterface.php';

  class Travelport extends GdsInterface {

    protected $reqXmlPath;
    protected $reqXML, $responseJson;


    public function __construct($wiconfig) {
      parent::__construct($wiconfig);
      $this->reqXmlPath = __DIR__ . "/requests";
      $this->reqXML = "";
    }

    private function getReqXmlFileName() {
      $templateXml = '';
      switch ($this->requestType) {
        case 'LowFareSearch':
          $templateXml = 'LowFareSearchReq.xml';
          break;
        case 'HotelSearch':
          $templateXml = 'HotelSearchAvailabilityReq.xml';
          break;
        case 'HotelDetails':
          $templateXml = 'HotelDetailsReq.xml';
          break;
        case 'HotelMedia':
          $templateXml = 'HotelMediaLinksReq.xml';
          break;
        case 'AirCreateReservation':
          $templateXml = 'AirCreateReservationReq.xml';
          break;
        case 'HotelCreateReservation':
          $templateXml = 'HotelCreateReservationReq.xml';
          break;
        case 'AirPricing':
          $templateXml = 'AirPricingReq.xml';
          break;

      }

      if(!empty($templateXml)) {
        $templateXml = $this->reqXmlPath . '/' . $templateXml;
      }

      return $templateXml;

    }

    public function fillReqXmlTemplate() {
        $templateXml = $this->getReqXmlFileName();
        $strReqXml = file_get_contents($templateXml);
        // $placeholders = file_get_contents(__DIR__ . '/requests/placeholders.json');
        // $arrPlaceholders = Zend_Json::decode($placeholders);
        // $placeholders = $arrPlaceholders[$this->requestType];
        switch ($this->requestType) {
          case 'LowFareSearch':
            $request = $this->getRequest();
            $strReqXml = str_replace("{TARGET_BRANCH}", $this->wiconfig['Travelport']['TARGET_BRANCH'], $strReqXml);
            $strReqXml = str_replace("{AUTHORIZED_BY}", $this->wiconfig['Travelport']['AUTHORIZED_BY'], $strReqXml);
            $strReqXml = str_replace("{FROM_PLACE}", $request->getFromPlace(), $strReqXml);
           $strReqXml = str_replace("{FROM_DATE}", $request->getFromDate(), $strReqXml);


            $SearchOrigins = '';
            $searchDestinations = '';
            $returnSegment = '';

            $arrToPlaces = $request->getToPlaces();
            foreach($arrToPlaces as $dest) {
          		if($dest != $request->getFromPlace()) {
          			$searchDestinations .= '<air:SearchDestination><com:CityOrAirport Code="'.$dest.'" /></air:SearchDestination>';
          			$SearchOrigins .= '<air:SearchOrigin><com:CityOrAirport Code="'.$dest.'" /></air:SearchOrigin>';
          		}
          	}
            $strReqXml = str_replace("{SEARCH_DESTINATIONS}", $searchDestinations, $strReqXml);

            $searchPassengers = '';
            for($i=1; $i<=$request->getNumPassengers(); $i++) {
            	$searchPassengers .= '<com:SearchPassenger Code="ADT" />';
            }
            $strReqXml = str_replace("{PASSENGERS}", $searchPassengers, $strReqXml);


            if($request->getIs2Way() == true) {
            	$returnSegment = '<air:SearchAirLeg>'.$SearchOrigins.'<air:SearchDestination><com:CityOrAirport Code="'.$request->getFromPlace().'" /></air:SearchDestination><air:SearchDepTime PreferredTime="'.$request->getToDate().'" /></air:SearchAirLeg>';
            }

            $strReqXml = str_replace("{RETURN_SEGMENT}", $returnSegment, $strReqXml);
           // echo $strReqXml;exit;
            break;


          case 'HotelSearch':
            $request = $this->getRequest();
            $strReqXml = str_replace("{TARGET_BRANCH}", $this->wiconfig['Travelport']['TARGET_BRANCH'], $strReqXml);
            $strReqXml = str_replace("{AUTHORIZED_BY}", $this->wiconfig['Travelport']['AUTHORIZED_BY'], $strReqXml);
            // $strReqXml = str_replace("{LOCATION}", $request->getLocation(), $strReqXml);
            // $strReqXml = str_replace("{FROM_DATE}", $request->getFromDate(), $strReqXml);
            // $strReqXml = str_replace("{NUM_ADULTS}", $request->getNumAdults(), $strReqXml);
            // $strReqXml = str_replace("{CHECKIN_DATE}", $request->getCheckinDate(), $strReqXml);
            // $strReqXml = str_replace("{CHECKOUT_DATE}", $request->getCheckoutDate(), $strReqXml);
            // $strReqXml = str_replace("{NUM_ROOMS}", $request->getNumRooms(), $strReqXml);
            $strReqXml = str_replace("{LOCATION}", $request->location, $strReqXml);
            $strReqXml = str_replace("{NUM_ADULTS}", $request->numAdults, $strReqXml);
            $strReqXml = str_replace("{CHECKIN_DATE}", $request->checkinDate, $strReqXml);
            $strReqXml = str_replace("{CHECKOUT_DATE}", $request->checkoutDate, $strReqXml);
            $strReqXml = str_replace("{NUM_ROOMS}", $request->numRooms, $strReqXml);

            break;


          case 'HotelDetails':
            $request = $this->getRequest();
            $strReqXml = str_replace("{TARGET_BRANCH}", $this->wiconfig['Travelport']['TARGET_BRANCH'], $strReqXml);
            $strReqXml = str_replace("{AUTHORIZED_BY}", $this->wiconfig['Travelport']['AUTHORIZED_BY'], $strReqXml);
            $strReqXml = str_replace("{NUM_ADULTS}", $request->numAdults, $strReqXml);
            $strReqXml = str_replace("{NUM_CHILDREN}", $request->numChildren, $strReqXml);
            $strReqXml = str_replace("{CHECKIN_DATE}", $request->checkinDate, $strReqXml);
            $strReqXml = str_replace("{CHECKOUT_DATE}", $request->checkoutDate, $strReqXml);
            $strReqXml = str_replace("{HOTEL_CODE}", $request->hotelCode, $strReqXml);
            $strReqXml = str_replace("{HOTEL_CHAIN}", 'YX', $strReqXml);


            break;

          case 'HotelMedia':
            $request = $this->getRequest();
            $strReqXml = str_replace("{TARGET_BRANCH}", $this->wiconfig['Travelport']['TARGET_BRANCH'], $strReqXml);
            $strReqXml = str_replace("{AUTHORIZED_BY}", $this->wiconfig['Travelport']['AUTHORIZED_BY'], $strReqXml);
            $strReqXml = str_replace("{HOTEL_CODE}", $request->hotelCode, $strReqXml);

            break;

          case 'AirCreateReservation':
               $request = $this->getRequest();
               $priceDetails=(array)$request->airpriceresult->AirPricingSolution;
               $to_booking_info=(array)$priceDetails['AirPricingInfo']->BookingInfo[0];
               $from_booking_info=(array)$priceDetails['AirPricingInfo']->BookingInfo[1];
               $taxinfo=(array)$priceDetails['AirPricingInfo']->TaxInfo;
               $tax_tag='';
               foreach ($taxinfo as $key => $value) {
               	$tax_value=(array)$value;
               	
               	$tax_tag .="<TaxInfo Key=\"".$tax_value['!Key']."\" Category=\"".$tax_value['!Category']."\" Amount=\"".$tax_value['!Amount']."\" />";
               	
               }
               $fare_tag=(array)$priceDetails['AirPricingInfo']->FareInfo;
               $fare_info='';
               
               foreach ($fare_tag as $key => $value) {
               	$fare_value=(array)$value;
               	$fare_rule=(array)$fare_value['FareRuleKey'];
               	$brand=(array)$fare_value['Brand'];
               	
               	$fare_info .='<FareInfo Key="'.$fare_value['!Key'].'" FareBasis="'.$fare_value['!FareBasis'].'" PassengerTypeCode="ADT" Origin="'.$fare_value['!Origin'].'" Destination="'.$fare_value['!Destination'].'" EffectiveDate="'.$fare_value['!EffectiveDate'].'" DepartureDate="'.$fare_value['!DepartureDate'].'" Amount="'.$fare_value['!Amount'].'" PrivateFare="'.$fare_value['!PrivateFare'].'" NotValidBefore="'.$fare_value['!NotValidBefore'].'" NotValidAfter="'.$fare_value['!NotValidAfter'].'" TaxAmount="'.$fare_value['!TaxAmount'].'">
                              <FareRuleKey FareInfoRef="'.$fare_rule['!FareInfoRef'].'" ProviderCode="1G">'.$fare_rule['!'].'</FareRuleKey>
                              <Brand Key="'.$fare_value['!Key'].'" BrandFound="'.$brand['!BrandFound'].'" />
                        </FareInfo>';
               }
               $to_farekey_obj=(array)$fare_tag[0];
               $from_farekey_obj=(array)$fare_tag[1];
               
			
               $To_details=(array)$request->airsegment->onward;
               $Return_details=(array)$request->airsegment->return;

               $traveller = $request->traveller;
            $cc = $traveller->creditCard;

            $phone = $traveller->phone;
            $shipAddr = $traveller->shippingAddress;
            $addr = $traveller->address;
            
            
            $strReqXml = str_replace("{TARGET_BRANCH}", $this->wiconfig['Travelport']['TARGET_BRANCH'], $strReqXml);
            $strReqXml = str_replace("{AUTHORIZED_BY}", $this->wiconfig['Travelport']['AUTHORIZED_BY'], $strReqXml);
             ////14JUN86/M//Khurana/Sachin
            //$ssr="////".strtoupper(date('dMY',strtotime($traveller->dob)))."/".$traveller->gender."//".$traveller->lastName."/".$traveller->firstName; 
            $ssr="P/IN/P12345673/IN/".strtoupper(date('dMy',strtotime($traveller->dob)))."/".$traveller->gender."/23DEC18/".$traveller->lastName."/".$traveller->firstName;
            $strReqXml= str_replace("{SECURE_DETAILS}",$ssr,$strReqXml);
             //traveller
           $strReqXml = str_replace("{TRAVELLER_TYPE}", $traveller->type, $strReqXml);
            $strReqXml = str_replace("{TRAVELLER_PREFIX}", $traveller->prefix, $strReqXml);
            $strReqXml = str_replace("{TRAVELLER_FIRSTNAME}", $traveller->firstName, $strReqXml);
            $strReqXml = str_replace("{TRAVELLER_LASTNAME}", $traveller->lastName, $strReqXml);
            $strReqXml = str_replace("{TRAVELLER_EMAIL}", $traveller->email, $strReqXml);
            $strReqXml = str_replace("{TRAVELLER_AGE}", $traveller->age, $strReqXml);
            $strReqXml = str_replace("{TRAVELLER_DOB}", $traveller->dob, $strReqXml);
            $strReqXml = str_replace("{TRAVELLER_GENDER}", $traveller->gender, $strReqXml);
            $strReqXml = str_replace("{TRAVELLER_NATIONALITY}", $traveller->nationality, $strReqXml);

            //phone
            $strReqXml = str_replace("{PHONE_LOCATION}", $phone->location, $strReqXml);
            $strReqXml = str_replace("{PHONE_COUNTRYCODE}", $phone->countryCode, $strReqXml);
            $strReqXml = str_replace("{PHONE_AREACODE}", $phone->areaCode, $strReqXml);
            $strReqXml = str_replace("{PHONE_NUMBER}", $phone->number, $strReqXml);

            //credit card
            $strReqXml = str_replace("{CREDITCARD_TYPE}", $cc->type, $strReqXml);
            $strReqXml = str_replace("{CREDITCARD_NUMBER}", $cc->number, $strReqXml);
            $strReqXml = str_replace("{CREDITCARD_NAME}", $traveller->firstName, $strReqXml);
            $strReqXml = str_replace("{CREDITCARD_EXPDATE}", $cc->expDate, $strReqXml);
            $strReqXml = str_replace("{CREDITCARD_CVV}", $cc->cvv, $strReqXml);

            //shippig address
            $strReqXml = str_replace("{SHIPPING_STREET}", $shipAddr->street, $strReqXml);
            $strReqXml = str_replace("{SHIPPING_CITY}", $shipAddr->city, $strReqXml);
            $strReqXml = str_replace("{SHIPPING_STATE}", $shipAddr->state, $strReqXml);
            $strReqXml = str_replace("{SHIPPING_COUNTRY}", $shipAddr->country, $strReqXml);
            $strReqXml = str_replace("{SHIPPING_POSTALCODE}", $shipAddr->postalCode, $strReqXml);


            //address
            $strReqXml = str_replace("{ADDRESS_NAME}", $addr->name, $strReqXml);
            $strReqXml = str_replace("{ADDRESS_STREET}", $addr->street, $strReqXml);
            $strReqXml = str_replace("{ADDRESS_CITY}", $addr->city, $strReqXml);
            $strReqXml = str_replace("{ADDRESS_STATE}", $addr->state, $strReqXml);
            $strReqXml = str_replace("{ADDRESS_POSTALCODE}", $addr->postalCode, $strReqXml);
            $strReqXml = str_replace("{ADDRESS_COUNTRY}", $addr->country, $strReqXml);
             
             $to_flightnumber=$To_details['!FlightNumber'] ;//"8773";
             $to_destination=$To_details['!Destination'];//"CDG";
             $to_origin=$To_details['!Origin'] ;//"LGW";
             $to_departure_time=$To_details['!DepartureTime'];//"2016-08-12T21:20:00.000+01:00";
             $to_arrival_time=$To_details['!ArrivalTime'];//"2016-08-12T23:35:00.000+02:00";
             $to_flight_time=$To_details['!FlightTime'];//"75";
             $to_carrier=$To_details['!Carrier'];//"VY";
             $to_departure_date=date('Y-m-d',strtotime($to_details['!DepartureTime']));;//"2016-09-09";
             $to_AvailabilitySource=$To_details['!AvailabilitySource'];
            $strReqXml=str_replace('{CARRIER}',$to_carrier,$strReqXml);  
             //Return details
             $return_carrier=$Return_details['!Carrier'];//"VY";
             $return_flightnumber=$Return_details['!FlightNumber'];//"8770";
             $return_origin=$Return_details['!Origin'];//"CDG";
             $return_destination=$Return_details['!Destination'];//"LGW";
             $return_departure_time=$Return_details['!DepartureTime'];//"2016-08-14T06:45:00.000+02:00";
             $return_arrival_time=$Return_details['!ArrivalTime'];//"2016-08-14T07:00:00.000+01:00";
             $return_flight_time=$Return_details['!FlightTime'];//"75";
             $return_AvailabilitySource=$Return_details['!AvailabilitySource'];
            
             //price info
             $air_totalprice=$priceDetails['!TotalPrice'];
             $air_baseprice=$priceDetails['!BasePrice'];
             $air_apprx_totalprice=$priceDetails['!ApproximateTotalPrice'];
             $air_apprx_baseprice=$priceDetails['!ApproximateBasePrice'];
             $air_taxes=$priceDetails['!Taxes'];

             //$air_equi_baseprice=$priceDetails['!TotalPrice'];
             $return_departue_date=date('Y-m-d',strtotime($Return_details['!DepartureTime']));//"2016-09-11";
             $to_effective_date=date('Y-m-d')."T01:00:01.000+02:00";//"2016-09-06T01:00:01.000+02:00";
             $return_effective_date=date('Y-m-d')."T01:00:01.000+02:00";//"2016-09-06T01:00:01.000+02:00"; 
             $to_key="6YxTVSb8QVmlI4E8AegWNQ==";//$To_details['!Key'];//'GGTxIBNzSwi/usStKWFH8w==';
             $return_key="n+XmH2VMSYuwM7Sy4bm3Kg==";//$Return_details['!Key'];//"5tHi0u4mTG6Qv2xfeHPCiw==";

             $test='';
            //air segements
           $test="<AirSegment Key=\"".$to_key."\" OptionalServicesIndicator=\"false\"  AvailabilitySource=\"".$to_AvailabilitySource."\"  AvailabilityDisplayType=\"Fare Specific Fare Quote Unbooked\" Group=\"0\" Carrier=\"".$to_carrier."\" FlightNumber=\"".$to_flightnumber."\" Origin=\"".$to_origin."\" Destination=\"".$to_destination."\" DepartureTime=\"".$to_departure_time."\" ArrivalTime=\"".$to_arrival_time."\" FlightTime=\"".$to_flight_time."\" TravelTime=\"".$to_flight_time."\" ProviderCode=\"1G\"  /><AirSegment Key=\"".$return_key."\" OptionalServicesIndicator=\"false\" AvailabilitySource=\"".$return_AvailabilitySource."\" AvailabilityDisplayType=\"Fare Specific Fare Quote Unbooked\" Group=\"1\" Carrier=\"".$return_carrier."\" FlightNumber=\"".$return_flightnumber."\" Origin=\"".$return_origin."\" Destination=\"".$return_destination."\" DepartureTime=\"".$return_departure_time."\" ArrivalTime=\"".$return_arrival_time."\" FlightTime=\"".$return_flight_time."\" TravelTime=\"".$return_flight_time."\"  ProviderCode=\"1G\"  />";
           //$test .="<com:ContinuityCheckOverride Key=\"PDz8y2dj4hGfaM/wYIhwmw==\">YES</com:ContinuityCheckOverride>";  
           
           
            $strReqXml=str_replace("{AIRSEGMENT}", $test, $strReqXml);
           
            $strReqXml=str_replace("{AIR_TOTALPRICE}", $air_totalprice, $strReqXml);
            $strReqXml=str_replace("{AIR_BASEPRICE}", $air_baseprice, $strReqXml);
            $strReqXml=str_replace("{AIR_APPRX_TOTALPRICE}", $air_apprx_totalprice, $strReqXml);
            $strReqXml=str_replace("{AIR_APPRX_BASEPRICE}", $air_apprx_baseprice, $strReqXml);
            $strReqXml=str_replace("{AIR_TAXES}", $air_taxes, $strReqXml);
            //$strReqXml=str_replace("{AIR_EQUIBASEPRICE}", $air_equi_baseprice, $strReqXml);
            $strReqXml=str_replace("{TO_BOOKINGCODE}", $to_booking_info['!BookingCode'], $strReqXml);
            $strReqXml=str_replace("{FROM_BOOKINGCODE}", $from_booking_info['!BookingCode'], $strReqXml);
            
            //To Departure Details
           $strReqXml=str_replace("{TO_DEPARTURE_DATE}", $to_departure_date, $strReqXml);
            $strReqXml=str_replace("{TO_DESTINATION}", $to_destination, $strReqXml);
            $strReqXml=str_replace("{TO_ORIGIN}", $to_origin, $strReqXml);
            $strReqXml=str_replace("{TO_EFFECTIVE_DATE}", $to_effective_date, $strReqXml);
           
            //Return Departure Details
            $strReqXml=str_replace("{RETURN_DEPARTURE_DATE}", $return_departue_date, $strReqXml);
            $strReqXml=str_replace("{RETURN_DESTINATION}", $return_destination, $strReqXml);
            $strReqXml=str_replace("{RETURN_ORIGIN}", $return_origin, $strReqXml);
            $strReqXml=str_replace("{RETURN_EFFECTIVE_DATE}", $return_effective_date, $strReqXml);
            
            $strReqXml =str_replace("{TOSEGEMENT_KEY}", $to_key, $strReqXml);
            $strReqXml =str_replace("{RETURNSEGEMENT_KEY}", $return_key, $strReqXml);
            $strReqXml =str_replace("{TAXINFO}", $tax_tag, $strReqXml);
            $strReqXml =str_replace("{FAREINFO}", $fare_info, $strReqXml);
            $strReqXml =str_replace("{TO_FAREKEY}", $to_farekey_obj['!Key'], $strReqXml);
            $strReqXml =str_replace("{FROM_FAREKEY}", $from_farekey_obj['!Key'], $strReqXml);
            //echo $strReqXml;exit;
            
            break;

          case 'HotelCreateReservation':
            $request = $this->getRequest();

            $traveller = $request->traveller;
            $cc = $traveller->creditCard;
            $phone = $traveller->phone;
            $shipAddr = $traveller->shippingAddress;
            $addr = $traveller->address;

            $strReqXml = str_replace("{TARGET_BRANCH}", $this->wiconfig['Travelport']['TARGET_BRANCH'], $strReqXml);
            $strReqXml = str_replace("{AUTHORIZED_BY}", $this->wiconfig['Travelport']['AUTHORIZED_BY'], $strReqXml);
            $strReqXml = str_replace("{HOTEL_CODE}", $request->hotelCode, $strReqXml);
            $strReqXml = str_replace("{HOTEL_CHAIN}", $request->hotelChain, $strReqXml);
            $strReqXml = str_replace("{NUM_ADULTS}", $request->numAdults, $strReqXml);
            $strReqXml = str_replace("{CHECKIN_DATE}", $request->checkinDate, $strReqXml);
            $strReqXml = str_replace("{CHECKOUT_DATE}", $request->checkoutDate, $strReqXml);
            $strReqXml = str_replace("{NUM_ROOMS}", $request->numRooms, $strReqXml);

            //traveller
            $strReqXml = str_replace("{TRAVELLER_TYPE}", $traveller->type, $strReqXml);
            $strReqXml = str_replace("{TRAVELLER_PREFIX}", $traveller->prefix, $strReqXml);
            $strReqXml = str_replace("{TRAVELLER_FIRSTNAME}", $traveller->firstName, $strReqXml);
            $strReqXml = str_replace("{TRAVELLER_LASTNAME}", $traveller->lastName, $strReqXml);
            $strReqXml = str_replace("{TRAVELLER_EMAIL}", $traveller->email, $strReqXml);
            $strReqXml = str_replace("{TRAVELLER_AGE}", $traveller->age, $strReqXml);
            $strReqXml = str_replace("{TRAVELLER_DOB}", $traveller->dob, $strReqXml);
            $strReqXml = str_replace("{TRAVELLER_GENDER}", $traveller->gender, $strReqXml);
            $strReqXml = str_replace("{TRAVELLER_NATIONALITY}", $traveller->nationality, $strReqXml);

            //phone
            $strReqXml = str_replace("{PHONE_LOCATION}", $phone->location, $strReqXml);
            $strReqXml = str_replace("{PHONE_COUNTRYCODE}", $phone->countryCode, $strReqXml);
            $strReqXml = str_replace("{PHONE_AREACODE}", $phone->areaCode, $strReqXml);
            $strReqXml = str_replace("{PHONE_NUMBER}", $phone->number, $strReqXml);

            //credit card
            $strReqXml = str_replace("{CREDITCARD_TYPE}", $cc->type, $strReqXml);
            $strReqXml = str_replace("{CREDITCARD_NUMBER}", $cc->number, $strReqXml);
            $strReqXml = str_replace("{CREDITCARD_EXPDATE}", $cc->expDate, $strReqXml);
            $strReqXml = str_replace("{CREDITCARD_CVV}", $cc->cvv, $strReqXml);

            //shippig address
            $strReqXml = str_replace("{SHIPPING_STREET}", $shipAddr->street, $strReqXml);
            $strReqXml = str_replace("{SHIPPING_CITY}", $shipAddr->city, $strReqXml);
            $strReqXml = str_replace("{SHIPPING_POSTALCODE}", $shipAddr->postalCode, $strReqXml);

            //address
            $strReqXml = str_replace("{ADDRESS_NAME}", $addr->name, $strReqXml);
            $strReqXml = str_replace("{ADDRESS_STREET}", $addr->street, $strReqXml);
            $strReqXml = str_replace("{ADDRESS_CITY}", $addr->city, $strReqXml);
            $strReqXml = str_replace("{ADDRESS_STATE}", $addr->state, $strReqXml);
            $strReqXml = str_replace("{ADDRESS_POSTALCODE}", $addr->postalCode, $strReqXml);
            $strReqXml = str_replace("{ADDRESS_COUNTRY}", $addr->country, $strReqXml);
           // echo $strReqXml;exit;

            break;
            case 'AirPricing':
           
             $request = $this->getRequest();
               //var_dump($request);exit;
               $To_details=(array)$request->AirSegment->onward;
               $Return_details=(array)$request->AirSegment->return;

               $traveller = $request->traveller;
           

            
            
            $strReqXml = str_replace("{TARGET_BRANCH}", $this->wiconfig['Travelport']['TARGET_BRANCH'], $strReqXml);
            $strReqXml = str_replace("{AUTHORIZED_BY}", $this->wiconfig['Travelport']['AUTHORIZED_BY'], $strReqXml);

            
             
             $to_availabilitysource=$To_details['!AvailabilitySource'];
             $to_equipment=$To_details['!Equipment'];
             $to_availabilitydisplaytype=$To_details['!AvailabilityDisplayType'];
             $to_group=$To_details['!Group'];
             $to_carrier=$To_details['!Carrier'];//"VY";
             $to_flightnumber=$To_details['!FlightNumber'] ;//"8773";
             $to_origin=$To_details['!Origin'] ;//"LGW";
             $to_destination=$To_details['!Destination'];//"CDG";
             $to_departure_time=$To_details['!DepartureTime'];//"2016-08-12T21:20:00.000+01:00";
             $to_arrival_time=$To_details['!ArrivalTime'];//"2016-08-12T23:35:00.000+02:00";
             $to_flight_time=$To_details['!FlightTime'];//"75";
             $to_distance=$To_details['!Distance'];
              //To Departure Details
            $strReqXml=str_replace("{ToAvailabilitySource}", $to_availabilitysource, $strReqXml);
            $strReqXml=str_replace("{ToEquipment}", $to_equipment, $strReqXml);
            $strReqXml=str_replace("{AvailabilityDisplayType}", $to_availabilitydisplaytype, $strReqXml);
            $strReqXml=str_replace("{ToGroup}", $to_group, $strReqXml);
            $strReqXml=str_replace("{ToCarrier}", $to_carrier, $strReqXml);
            $strReqXml=str_replace("{ToFlightNumber}", $to_flightnumber, $strReqXml);
            $strReqXml=str_replace("{ToOrigin}", $to_origin, $strReqXml);
            $strReqXml=str_replace("{ToDes}", $to_destination, $strReqXml);
            $strReqXml=str_replace("{ToDepartureTime}", $to_departure_time, $strReqXml);
            $strReqXml=str_replace("{ToArrivalTime}", $to_arrival_time, $strReqXml);
            $strReqXml=str_replace("{ToFlightTime}", $to_flight_time, $strReqXml);
            $strReqXml=str_replace("{ToDistance}", $to_distance, $strReqXml);
           
            
            //Return details
             $return_availabilitysource=$Return_details['!AvailabilitySource'];
             $return_equipment=$Return_details['!Equipment'];
             $return_availabilitydisplaytype=$Return_details['!AvailabilityDisplayType'];
             $return_group=$Return_details['!Group'];
             $return_carrier=$Return_details['!Carrier'];//"VY";
             $return_flightnumber=$Return_details['!FlightNumber'] ;//"8773";
             $return_origin=$Return_details['!Origin'] ;//"LGW";
             $return_destination=$Return_details['!Destination'];//"CDG";
             $return_departure_time=$Return_details['!DepartureTime'];//"2016-08-12T21:20:00.000+01:00";
             $return_arrival_time=$Return_details['!ArrivalTime'];//"2016-08-12T23:35:00.000+02:00";
             $return_flight_time=$Return_details['!FlightTime'];//"75";
             $return_distance=$Return_details['!Distance'];
           
            //Return Departure Details
           //Return Departure Details
            $strReqXml=str_replace("{ReturnAvailabilitySource}", $return_availabilitysource, $strReqXml);
            $strReqXml=str_replace("{ReturnAvailabilityDisplayType}", $return_availabilitydisplaytype, $strReqXml);
            $strReqXml=str_replace("{ReturnEquipment}", $return_equipment, $strReqXml);
            $strReqXml=str_replace("{ReturnGroup}", $return_group, $strReqXml);
            $strReqXml=str_replace("{ReturnCarrier}", $return_carrier, $strReqXml);
            $strReqXml=str_replace("{ReturnFlightNumber}", $return_flightnumber, $strReqXml);
            $strReqXml=str_replace("{ReturnOrigin}", $return_origin, $strReqXml);
            $strReqXml=str_replace("{ReturnDes}", $return_destination, $strReqXml);
            $strReqXml=str_replace("{ReturnDepartureTime}", $return_departure_time, $strReqXml);
            $strReqXml=str_replace("{ReturnArrivalTime}", $return_arrival_time, $strReqXml);
            $strReqXml=str_replace("{ReturnFlightTime}", $return_flight_time, $strReqXml);
            $strReqXml=str_replace("{ReturnDistance}", $return_distance, $strReqXml);
            
          
             
             

             /*$test='';
            //air segements
           $test="<AirSegment Key=\"".$to_key."\" OptionalServicesIndicator=\"false\" AvailabilityDisplayType=\"Fare Specific Fare Quote Unbooked\" Group=\"0\" Carrier=\"".$to_carrier."\" FlightNumber=\"".$to_flightnumber."\" Origin=\"".$to_origin."\" Destination=\"".$to_destination."\" DepartureTime=\"".$to_departure_time."\" ArrivalTime=\"".$to_arrival_time."\" FlightTime=\"".$to_flight_time."\" TravelTime=\"".$to_flight_time."\" Distance=\"214\" ProviderCode=\"1G\"  /><AirSegment Key=\"".$return_key."\" OptionalServicesIndicator=\"false\" AvailabilityDisplayType=\"Fare Specific Fare Quote Unbooked\" Group=\"1\" Carrier=\"".$return_carrier."\" FlightNumber=\"".$return_flightnumber."\" Origin=\"".$return_origin."\" Destination=\"".$return_destination."\" DepartureTime=\"".$return_departure_time."\" ArrivalTime=\"".$return_arrival_time."\" FlightTime=\"".$return_flight_time."\" TravelTime=\"".$return_flight_time."\" Distance=\"214\" ProviderCode=\"1G\"  />";
           //$test .="<com:ContinuityCheckOverride Key=\"PDz8y2dj4hGfaM/wYIhwmw==\">YES</com:ContinuityCheckOverride>"; */ 
           
           
            //$strReqXml=str_replace("{AIRSEGMENT}", $test, $strReqXml);
           
            
            
            
            
            //echo $strReqXml;exit;
            
            break;


        }
        //echo"prem";exit;
        
        $this->reqXML = $strReqXml;
       
    }
    public function prepareRequest() {
      $this->fillReqXmlTemplate();
    }

    protected function getEndPoint() {
      $endPoint = '';
      switch ($this->requestType) {
        case 'LowFareSearch':
        case 'AirCreateReservation':
        case 'AirPricing':
          $endPoint = $this->wiconfig['Travelport']['ENDPOINT'];
          break;
        case 'HotelSearch':
        case 'HotelDetails':
        case 'HotelMedia':
        case 'HotelCreateReservation':
            $endPoint = $this->wiconfig['Travelport']['HOTEL_ENDPOINT'];
          break;
      }
      return $endPoint;
    }

    public function getFlightResults() {
      $this->requestType = 'LowFareSearch';
      if($this->wiconfig['Travelport']['USE_CACHE'] == 'true') {
        return file_get_contents(__DIR__ . '/cache/api_responses/LowFareSearchResp.json');
        // return file_get_contents(__DIR__ . '/cache/api_responses/LowFareSearchResp.1way.json');
      }

      //prepare req. xml
      $this->prepareRequest();
  
      //send req
      $this->sendRequest();

      //parse resp
      //$this->parseResponse();

      //file_put_contents(__DIR__ . '/cache/api_responses/LowFareSearchResp.json', $this->responseJson);
      //return result
      return $this->responseJson;
    }

    public function getHotelResults() {
      $this->requestType = 'HotelSearch';
      if($this->wiconfig['Travelport']['USE_CACHE'] == 'true') {
        return file_get_contents(__DIR__ . '/cache/api_responses/HotelSearchResp.json');
      }

      //prepare req. xml
      $this->prepareRequest();

      //send req
      $this->sendRequest();

      //parse resp
      //$this->parseResponse();

      //file_put_contents(__DIR__ . '/cache/api_responses/HotelSearchResp.json', $this->responseJson);
      //return result
      if($this->status) {
        return $this->responseJson;
      }else{
        return false;
      }
    }

    // public function getFlightDetails() {
    //
    //
    // }

    public function getHotelDetails() {

      $this->requestType = 'HotelDetails';
      if($this->wiconfig['Travelport']['USE_CACHE'] == 'true') {
        return file_get_contents(__DIR__ . '/cache/api_responses/HotelDetailsResp.json');
      }

      //prepare req. xml
      $this->prepareRequest();

      //send req
      $this->sendRequest();

      //parse resp
      //$this->parseResponse();

      //file_put_contents(__DIR__ . '/cache/api_responses/HotelDetailsResp.json', $this->responseJson);
      //return result
      if($this->status) {
        return $this->responseJson;
      }else{
        return false;
      }

    }

    public function getHotelMediaLinks() {
      $this->requestType = 'HotelMedia';
      if($this->wiconfig['Travelport']['USE_CACHE'] == 'true') {
        return file_get_contents(__DIR__ . '/cache/api_responses/HotelMediaLinksResp.json');
      }

      //prepare req. xml
      $this->prepareRequest();

      //send req
      $this->sendRequest();

      //parse resp
      //$this->parseResponse();

      //file_put_contents(__DIR__ . '/cache/api_responses/HotelMediaLinksResp.json', $this->responseJson);
      //return result
      if($this->status) {
        return $this->responseJson;
      }else{
        return false;
      }

    }


    public function bookFlight() {
      $this->requestType = 'AirCreateReservation';
      if($this->wiconfig['Travelport']['USE_CACHE'] == 'true') {
        return file_get_contents(__DIR__ . '/cache/api_responses/AirCreateReservationResp.json');
      }

      //prepare req. xml
      $this->prepareRequest();

      //send req
      $this->sendRequest();

      //parse resp
      //$this->parseResponse();

      //file_put_contents(__DIR__ . '/cache/api_responses/AirCreateReservationResp.json', $this->responseJson);
      //return result
      if($this->status) {
        return $this->responseJson;
      }else{
        return false;
      }
    }
    public function PriceListFlight() {
      $this->requestType = 'AirPricing';
      if($this->wiconfig['Travelport']['USE_CACHE'] == 'true') {
        return file_get_contents(__DIR__ . '/cache/api_responses/AirPricingResp.json');
      }

      //prepare req. xml
      $this->prepareRequest();

      //send req
      $this->sendRequest();

      //parse resp
      //$this->parseResponse();

      //file_put_contents(__DIR__ . '/cache/api_responses/AirCreateReservationResp.json', $this->responseJson);
      //return result
      if($this->status) {
        return $this->responseJson;
      }else{
        return false;
      }
    }

    public function bookHotel() {
      $this->requestType = 'HotelCreateReservation';
      if($this->wiconfig['Travelport']['USE_CACHE'] == 'true') {
        return file_get_contents(__DIR__ . '/cache/api_responses/HotelCreateReservationResp.json');
      }

      //prepare req. xml
      $this->prepareRequest();

      //send req
      $this->sendRequest();

      //parse resp
      //$this->parseResponse();

      //file_put_contents(__DIR__ . '/cache/api_responses/HotelCreateReservationResp.json', $this->responseJson);
      //return result
      if($this->status) {
        return $this->responseJson;
      }else{
        return false;
      }

    }

  }
