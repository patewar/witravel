<?php
	require_once __DIR__ . '/init.php';
	require_once __DIR__ . '/../Bootstrap.php';
	$wiconfig = $application->getBootstrap()->getOption('wiconfig');


	$citiesTable = new DbTable_Cities();
	$cities = $citiesTable->fetchAll();
	
	foreach ($cities as $cityRow) {
		$cityRow->code;
// continue;
	    $location = $cityRow->code;	    
	    $adults = 1;

	    $startDate = Utils::getNextFriday();
	    $endDate = Utils::getNextSunday();

	    //TODO: read the req params
	    $search = new HotelSearchCriteria();
	    $search->location = $location;
	    $search->numAdults = $adults;
	    $search->checkinDate = $startDate;
	    $search->checkoutDate = $endDate;
	    $search->numRooms = ceil($search->numAdults/2);

	    //send request
	    $gds = GdsFactory::getGdsProvider($wiconfig, 'TRAVELPORT');
	    $gds->setRequest($search);
 	    $results_json = $gds->getHotelResults();

	    if($results_json !== false) {
		    $cityRow->hotels_json = $results_json;
		    $cityRow->save();

		    //parse
		    $obj =  new HotelSearchParser();
		    $obj->setJsonResponse($results_json);
			$hotels = $obj->parse();	
		// echo count($hotels); 
		// echo "<br>";
			foreach ($hotels as $hotelCode => $hotelRow) {
				// echo "<pre>";
				// var_dump($hotelRow); 
				$hotelRow->city_code = $cityRow->code;
				$hotelRow->save();
			}
		}else{
			echo $gds->getMessage()."\n";
		}

	}
	echo 'Done';
