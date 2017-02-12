<?php

class HotelReservationResp {
	protected $bookingStatusCode, $bookingStatusDisplay;
	protected $providerLocatorCode;

	public function setBookingStatus($bookingStatus) {
		
		switch ($bookingStatus) {
			case 'NO':
				$this->bookingStatusCode = $bookingStatus;
				$this->bookingStatusDisplay = 'No action';
				break;

			case 'UC':
				$this->bookingStatusCode = $bookingStatus;
				$this->bookingStatusDisplay = 'Unable to confirm';
				break;

			case 'KK':
				$this->bookingStatusCode = $bookingStatus;
				$this->bookingStatusDisplay = 'Can Confirm';
				break;

			case 'PN':
				$this->bookingStatusCode = $bookingStatus;
				$this->bookingStatusDisplay = 'Pending';
				break;
			case 'HK':			
				$this->bookingStatusCode = $bookingStatus;
				$this->bookingStatusDisplay = 'Confirmed';
				break;
																							
			default:
				$this->bookingStatusCode = 'UNKNOWN';
				$this->bookingStatusDisplay = 'Unknown';
				break;
		}					
	}

	public function getBookingStatus() {
		return $this->bookingStatusCode;
	}

	public function getBookingStatusDisplay() {
		return $this->bookingStatusDisplay;
	}

	public function setProviderLocatorCode($providerLocatorCode) {
		$this->providerLocatorCode = $providerLocatorCode;
	}

	public function getProviderLocatorCode() {
		return $this->providerLocatorCode;
	}

}
