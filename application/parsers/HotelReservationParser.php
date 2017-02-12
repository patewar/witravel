<?php

require_once("BaseParser.php");

class HotelReservationParser extends BaseParser{
	protected $startDate, $endDate;
	protected $durationNumDays = 1;

	protected $hotelReservRespObj;

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
		$this->hotelReservRespObj = new HotelReservationResp();

		if(!empty($jsonResponse)) {
			$this->setJsonResponse($jsonResponse);
		}

		$bookingStatus = $this->arrResponse['UniversalRecord']['HotelReservation']['!Status'];
		$this->hotelReservRespObj->setBookingStatus($bookingStatus);

		$providerLocatorCode = $this->arrResponse['UniversalRecord']['ProviderReservationInfo']['!LocatorCode'];

		$this->hotelReservRespObj->setProviderLocatorCode($providerLocatorCode);		

		return $this->hotelReservRespObj;	
	}


	public function getHotelReservationRespObj() {
		return $this->hotelReservRespObj;
	}

} //class