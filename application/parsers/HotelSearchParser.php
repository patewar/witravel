<?php
require_once("BaseParser.php");

class HotelSearchParser extends BaseParser{
	protected $startDate, $endDate;
	protected $durationNumDays = 1;

	public function setStartEndDates($startDate, $endDate) {
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

		$items = array();
		foreach($this->arrResponse['HotelSearchResult'] as $arrHotelSearchResult) {

			if(!isset($arrHotelSearchResult['HotelProperty']['!Availability']) || $arrHotelSearchResult['HotelProperty']['!Availability'] != 'Available') {
				continue;			
			}

			//$objHotelDetails = new HotelDetails();
			$hotelsTable = new DbTable_Hotels();

			$hotelCode								= $arrHotelSearchResult['HotelProperty']['!HotelCode'];

			$objHotelDetails = $hotelsTable->fetchRow($hotelsTable->select()->where('HotelCode = ?', $hotelCode));
			if(!$objHotelDetails) {
				$objHotelDetails = $hotelsTable->createRow();
			}
			
			$objHotelDetails->HotelCode				= $arrHotelSearchResult['HotelProperty']['!HotelCode'];
			$objHotelDetails->Name					= $arrHotelSearchResult['HotelProperty']['!Name'];
			$objHotelDetails->Availability			= $arrHotelSearchResult['HotelProperty']['!Availability'];
			$objHotelDetails->Address				= $arrHotelSearchResult['HotelProperty']['PropertyAddress']['Address'];
			$objHotelDetails->Distance				= $arrHotelSearchResult['HotelProperty']['Distance']['!Value'];
			$objHotelDetails->Transportation		= $arrHotelSearchResult['HotelProperty']['!HotelTransportation'];
			$objHotelDetails->ReserveRequirement	= $arrHotelSearchResult['HotelProperty']['!ReserveRequirement'];
			$objHotelDetails->ParticipationLevel	= $arrHotelSearchResult['HotelProperty']['!ParticipationLevel'];

			if(!empty($arrHotelSearchResult['RateInfo']['!ApproximateMinimumAmount'])) {
				$objHotelDetails->MinimumAmount			= $arrHotelSearchResult['RateInfo']['!ApproximateMinimumAmount'];
			}else{
				$objHotelDetails->MinimumAmount			= $arrHotelSearchResult['RateInfo']['!MinimumAmount'];
			}


			if(!empty($arrHotelSearchResult['RateInfo']['!ApproximateMaximumAmount'])) {
				$objHotelDetails->MaximumAmount			= $arrHotelSearchResult['RateInfo']['!ApproximateMaximumAmount'];
			}else{
				$objHotelDetails->MaximumAmount			= $arrHotelSearchResult['RateInfo']['!MaximumAmount'];
			}
			
			

			$objHotelDetails->MinimumAmountNum		= preg_replace('/[a-z]/i', '', $objHotelDetails->MinimumAmount);

			$objHotelDetails->TotalMinAmountNum		= number_format($objHotelDetails->MinimumAmountNum * $this->durationNumDays, 2);

			$items[$hotelCode] = $objHotelDetails;
		}		

		return $items;	
	}

}