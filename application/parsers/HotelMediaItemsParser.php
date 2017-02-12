<?php

require_once("BaseParser.php");

class HotelMediaItemsParser extends BaseParser {
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

		$items = array();
		foreach($this->arrResponse['HotelPropertyWithMediaItems']['MediaItem'] as $mediaItem) {						
			$items[] = $mediaItem['!url'];
		}		

		return $items;	
	}

}