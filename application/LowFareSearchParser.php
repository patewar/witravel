<?php
require_once("BaseParser.php");

class LowFareSearchParser extends BaseParser{
	private $budget = 0;
	private $tripType ='return';

	public function setBudget($budget) {
		$this->budget = $budget;
	}

	public function getBudget() {
		return $this->budget;
	}

	public function setTripType($tripType = 'return') {
		$this->tripType = $tripType;
	}
	public function getTripType() {
		return $this->tripType;
	}

	public function parse($jsonResponse = "") {

		if(!empty($jsonResponse)) {
			$this->setJsonResponse($jsonResponse);
		}

		$items = array();
		foreach($this->arrResponse['AirPricingSolution'] as $airPricingSolution) {
//			echo "<pre>";  print_r($airPricingSolution); exit;
			$item = array();
			$thisDestination = '';
 			$totalPrice = Utils::getPrice($airPricingSolution['!TotalPrice']);


			if(!empty($this->budget)) {
				if($totalPrice > $this->budget) {
					continue;
				}
			}

			$item['TotalPrice'] = $airPricingSolution['!TotalPrice'];
			$item['TotalPriceNum'] = Utils::getPrice($airPricingSolution['!TotalPrice']);
//TODO: use Journey key to directly get the AirSegmentRef, instead of looping all other arrays inside $airPricingSolution 


			//oneway
			if($this->tripType == 'one_way') {
				foreach($airPricingSolution as $arrAirSegmentRef) {
					if(is_array($arrAirSegmentRef))	{	
						//$airSegment=$arrAirSegmentRef['AirSegmentRef']['!Key'];					
						$airSegment = $this->getAirSegmentBasedOnAirSegmentKey($arrAirSegmentRef['AirSegmentRef']['!Key']);
					}else{					
						$airSegment = $this->getAirSegmentBasedOnAirSegmentKey($arrAirSegmentRef);
					}
					if(!empty($airSegment)) {
						$thisDestination = $airSegment['!Destination'];
						$objFlightDetails = new FlightDetails();

						$objFlightDetails->Origin			= $airSegment['!Origin'];
						$objFlightDetails->Destination		= $airSegment['!Destination'];
						
						list($objFlightDetails->DepartureDate, $objFlightDetails->DepartureTime)	= explode('T', $airSegment['!DepartureTime']);
						$objFlightDetails->DepartureTime = substr($objFlightDetails->DepartureTime, 0, 5);
						list($objFlightDetails->ArrivalDate,$objFlightDetails->ArrivalTime) 		= explode('T', $airSegment['!ArrivalTime']);
						$objFlightDetails->ArrivalTime = substr($objFlightDetails->ArrivalTime, 0, 5);					
						$objFlightDetails->TravelTime = $airSegment['!FlightTime'];
						$objFlightDetails->FlightNumber = $airSegment['!FlightNumber'];
						$objFlightDetails->Carrier = $airSegment['!Carrier'];	


						$item['flights'][] = $objFlightDetails;
					}
				}
			}else{

				//for($i=0;$i<=1;$i++) {
					//$airPricingSolution['Journey'][$i]['AirSegmentRef']
					foreach($airPricingSolution as $arrAirSegmentRef) {
						for($i=0;$i<=1;$i++) {

							if(isset($arrAirSegmentRef[$i]) && is_array($arrAirSegmentRef[$i])) {
								$airSegment = $this->getAirSegmentBasedOnAirSegmentKey($arrAirSegmentRef[$i]['AirSegmentRef']['!Key']);
								//$airSegment = $this->getAirSegmentBasedOnAirSegmentKey($arrAirSegmentRef['!Key']);
							}else{
								$airSegment = $this->getAirSegmentBasedOnAirSegmentKey($arrAirSegmentRef);
							}
							
							if(!empty($airSegment)) {
								if($i == 0) {
									$thisDestination = $airSegment['!Destination'];
								}
								$objFlightDetails = new FlightDetails();
								$objFlightDetails->Origin			= $airSegment['!Origin'];
								$objFlightDetails->Destination		= $airSegment['!Destination'];

								list($objFlightDetails->DepartureDate, $objFlightDetails->DepartureTime)	= explode('T', $airSegment['!DepartureTime']);
								$objFlightDetails->DepartureTime = substr($objFlightDetails->DepartureTime, 0, 5);
								list($objFlightDetails->ArrivalDate,$objFlightDetails->ArrivalTime) 		= explode('T', $airSegment['!ArrivalTime']);
								$objFlightDetails->ArrivalTime = substr($objFlightDetails->ArrivalTime, 0, 5);					
								$objFlightDetails->TravelTime = $airSegment['!FlightTime'];
								$objFlightDetails->FlightNumber = $airSegment['!FlightNumber'];
								$objFlightDetails->Carrier = $airSegment['!Carrier'];	

								$item['flights'][] = $objFlightDetails;
							}
						}
					}
				//}
			}//return end
			if(!empty($thisDestination)) {
				$items[$thisDestination][] = $item;
				//$items[$thisDestination]['flightResults'][] = $item;

			}
		}
		return $items;	
	}


	private function getAirSegmentBasedOnAirSegmentKey($airSegmentKey) {
		foreach($this->arrResponse['AirSegmentList']['AirSegment'] as $arrAirSegment) {
			if($arrAirSegment['!Key'] == $airSegmentKey) {
				return $arrAirSegment;
			}			
		}		
	}

}
