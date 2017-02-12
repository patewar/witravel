<?php

require_once("BaseParser.php");


class HotelDetailsParser extends BaseParser {
	protected $startDate, $endDate;
	protected $durationNumDays = 1;

	public function __construct($startDate='', $endDate='') {
		$this->startDate = $startDate;
		$this->endDate = $endDate;

		$this->__calculateDuration();

	}

	private function __calculateDuration() {
		if(!empty($this->startDate) && !empty($this->endDate)) {
			/*
			$date1 = new DateTime($this->startDate);
			$date2 = new DateTime($this->endDate);
			$this->durationNumDays = $date1->diff($date2)->days;
			*/
			$start_ts				= strtotime($this->startDate);
			$end_ts 				= strtotime($this->endDate);
			$this->durationNumDays 	= ($end_ts - $start_ts)/(60*60*24);
			  		
		}
	}


	public function parse($jsonResponse = "") {
		if(!empty($jsonResponse)) {
			$this->setJsonResponse($jsonResponse);
		}

		$details = array();
		
		$hotelProperty 	= $this->arrResponse['RequestedHotelDetails']['HotelProperty'];
		$arrAddress 	= $hotelProperty['PropertyAddress']['Address'];

		$details['address'] = implode(",", $arrAddress);

		foreach ($hotelProperty['PhoneNumber']['!Type'] as $key => $value) {
			$details['phone'][$value['!Type']] = $value['!Number'];	
		}


		//Room Rates
		$roomRates = array();
		$HotelRateDetail = $this->arrResponse['RequestedHotelDetails']['HotelRateDetail'];

		foreach ($HotelRateDetail as $hotelRate) {
			$RoomRateDescription = $hotelRate['RoomRateDescription'];
			foreach($RoomRateDescription as $hotelDesc) {
				if($hotelDesc['!Name'] == 'Description') {
					$room['Description'] = $hotelDesc['Text'];
				}
				
			}
			$room['Rate'] = $hotelRate['HotelRateByDate']['!Base'];
			$room['RateNum'] = preg_replace('/[a-z]/i', '', $room['Rate'] );

			$room['TotalRate'] = $hotelRate['!Total'];
			$room['TotalRateNum'] = preg_replace('/[a-z]/i', '', $room['TotalRate'] );


			$RateMatchIndicator = $hotelRate['RateMatchIndicator'];
			foreach($RateMatchIndicator as $hotelMatch) {
				if($hotelMatch['!Type'] == 'RoomCount') {
					$room['RoomCount'] = $hotelMatch['!Value'];
				}
				
			}

			$rooms[] = $room;
						
		}
		$details['rooms'] = $rooms;
		
		return $details;	
	}

}