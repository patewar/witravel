'use strict';

/**
 * @ngdoc function
 * @name ngWitravelApp.service:lowFareSearchService
 * @description
 * # lowFareSearchService service
 * Service of the ngWitravelApp
 */
angular.module('ngWitravelApp')
    .service('lowFareSearchService', ['$http', '$q', 'wiConfig', 'cityNamesService', 'hotelSearchService', 'util',
        function ($http, $q, wiConfig, cityNamesService, hotelSearchService, util) {
        var origin = 'Madrid';

        //var allDestinations = ['AMS', 'BER', 'BCN', 'LON', 'CPH', 'PAR', 'IST', 'BRU', 'AGP', 'ROM', 'KRK', 'TLL', 'LIS'];
        var allDestinations = cityNamesService.getDestinationCityCodes();
        var searchDestinations;

        var budget = 500, numTravellers = 1, twoWay = false;
        var startDate = util.nextFridayDate(), endDate = util.nextSundayDate(); //Date object 
        var distance = 20;
        var dispDestinations = {};

        var bookingCharges = 10;

        var sortBy = 'price';

        var searchStatus = false; //, searchResults = null;
        var respResults = [];
        var resAirSegments = {};

        var selectedDestination = null, selectedAirSegment = null, selectedHotel = null, selectedTotalPrice = 0;

        var setOrigin = function (o) {
            origin = o;
        }        

        var getOrigin = function () {
            //TODO: get location based on IP address
            return origin;
        }

        var setBudget = function (b) {
            budget = b;
        }

        var getBudget = function () {
            return budget;
        };

        var setTwoWay = function (t) {
            twoWay = t;
        }

        var getTwoWay = function () {
            return twoWay;
        };        

        var getBookingCharges = function() {
            return bookingCharges;
        };

        var getResultDestinations = function () {
            return Object.keys(resAirSegments);
        };


        var setDispDestinations = function (dest) {
            dispDestinations = dest;
        };


        var getDispDestinations = function () {
            return dispDestinations;
        };        

        var setDistance = function (d) {
            distance = d;
        }

        var getDistance = function () {
            return distance;
        };
        var setNumTravellers = function (n) {
            numTravellers = n;
        };

        var getNumTravellers = function () {
            return numTravellers;
        };

        var setStartDate = function (d) {
            startDate = d;
        }

        var getStartDate = function () {
            return startDate;
        };  

        var setEndDate = function (d) {
            endDate = d;
        }

        var getEndDate = function () {
            return endDate;
        };               

        var getAllDestinations = function() {
            return allDestinations;
        };

        var setSearchDestinations = function(destinations) {
            searchDestinations = destinations;
        };

        var getSearchDestinationsAsString = function(destinations) {
            return searchDestinations.join();;
        };        


        var newSearchRequest = function(successFunction, failureFunction) {
            clearResults();
            var allDestinations = getAllDestinations();

            var dest1 = allDestinations.slice(0, 6);
            var dest2 = allDestinations.slice(6, 12);
            var dest3 = allDestinations.slice(12);
           
            // var defer = $q.defer();
            var promises = [];                

 //           promises.push(hotelSearchService.getCacheHotelSearchResults());

            setSearchDestinations(dest1);
            promises.push(getLowFareSearchResults());

            setSearchDestinations(dest2);
            promises.push(getLowFareSearchResults());

            setSearchDestinations(dest3);
            promises.push(getLowFareSearchResults());   
            
                            
            $q.all(promises).then(successFunction, failureFunction);

        };


        var getLowFareSearchResults = function () {
           
            return $http({
                method: 'GET',
               url: wiConfig.serviceURL + '/flights/low-fare-search/origin/' + getOrigin() + 
                        '/budget/' + getBudget() + '/travellers/' + getNumTravellers() + 
                        '/startDate/' + util.convertDate2PhpString(getStartDate()) + '/endDate/' + util.convertDate2PhpString(getEndDate()) + 
                        '/destinations/'+getSearchDestinationsAsString() +
                        '/twoWay/'+!getTwoWay()
                        /*url: wiConfig.serviceURL + '/flights/low-fare-search/origin/' + getOrigin() + 
                        '/budget/' + getBudget() + '/travellers/' + getNumTravellers() + 
                        '/startDate/2016-08-05/endDate/2016-08-07'+ 
                        '/destinations/'+getSearchDestinationsAsString() +
                        '/twoWay/'+!getTwoWay()*/
            }).then(function successFunction(response) {
                //console.log("getLowFareSearchResults ---> success", response.data)
                //searchResults = response.data;
                parseFlights(response.data);
                searchStatus = true;
                return searchStatus;
            }, function failureFunction(response) {
                return searchStatus;
            });
        };

        var getSavedResults = function () {
            return searchResults;
        };


        var getAirSegment = function (searchResults, segKey) {
            var airSegment = null;
            if (searchResults != null) {
                var arrAirSegments = searchResults['AirSegmentList']['AirSegment'];
                for (var i = 0; i < arrAirSegments.length; i++) {
                    if (arrAirSegments[i]['!Key'] == segKey) {
                        airSegment = arrAirSegments[i];
                        break;
                    }
                }
            }
            return airSegment;
        };

        var getAllFlights = function () {
            //clear prev. results, if any  
            respResults = [];          
            angular.forEach(resAirSegments, function (segments, dest) {
                //console.log("dest", dest);
                util.sortObjects(segments, 'TotalAirPrice');
                var thisDestination = {"destination": dest, "segments": segments};
                respResults.push(thisDestination);
            });

            return respResults;
        };

        var getFlights = function (destination) {
            var flightsForDestination = [];
            for (var i = 0; i < respResults.length; i++) {
                if (destination == undefined || destination == null || respResults[i].destination == destination) {
                    flightsForDestination = respResults[i];
                    break;
                }
            }
            util.sortObjects(flightsForDestination['segments'], 'TotalAirPrice');
            return flightsForDestination;
        };

        var getFlightsForDestination = function (destination) {
            var res = [];

            var airSegments = filterResults();
            // vm.selectedDestAirSegments = lowFareSearchService.getFlights(vm.selectedDestination);
            if(airSegments.length > 0) {
                for (var i = airSegments.length - 1; i >= 0; i--) {
                    if(cityNamesService.getCityNameForCode(airSegments[i]['destination']) == cityNamesService.getCityNameForCode(selectedDestination)) {
                        res = airSegments[i];
                        break;
                    }
                }
            }
            util.sortObjects(res['segments'], 'TotalAirPrice');
            return res;


            // var flightsForDestination = [];
            // for (var i = 0; i < respResults.length; i++) {
            //     if (destination == undefined || destination == null || respResults[i].destination == destination) {
            //         flightsForDestination = respResults[i];
            //         break;
            //     }
            // }
            // util.sortObjects(flightsForDestination['segments'], 'TotalAirPrice');
            // return flightsForDestination;
        };        

        var clearResults = function() {
            respResults = [];
            resAirSegments = {};

        };

        var parseFlights = function (searchResults) {
            //console.log("parseFlights(): ENTER --> resAirSegments = ", resAirSegments);
            //var resAirSegments = {}, arrDestinations = [];

            if (searchResults != null && searchResults['AirPricingSolution'] != undefined) {
                var airPricingSolutions = searchResults['AirPricingSolution'];

                if (airPricingSolutions.length > 0) {
                    for (var i = 0; i < airPricingSolutions.length; i++) {
//console.log('airPricingSolutions -->', i);
                        var thisAirPricing = airPricingSolutions[i];


                        var thisTotalPrice = thisAirPricing['!TotalPrice'];
                        if(thisTotalPrice && thisTotalPrice.substring(0, 3) === 'EUR') {
                            thisTotalPrice = util.string2Num(thisTotalPrice.substring(3, thisTotalPrice.length));
                            // if(thisTotalPrice > getBudget()) {
                            //     continue;
                            // }
                        }else if(getBudget()){
                            thisTotalPrice = 0; //getBudget(); //TODO: 
                        }
                        

                        var arrFlights = {};
                        arrFlights['TotalAirPrice'] = thisTotalPrice;
                        var airSegmentKey1, airSegmentKey2;
                        if (thisAirPricing['Journey'][0] != undefined) { //!twoWay && 
                            airSegmentKey1 = thisAirPricing['Journey'][0]['AirSegmentRef']['!Key'];
                            airSegmentKey2 = thisAirPricing['Journey'][1]['AirSegmentRef']['!Key'];
                        } else if(thisAirPricing['Journey']['AirSegmentRef']){
                            airSegmentKey1 = thisAirPricing['Journey']['AirSegmentRef']['!Key'];
                        }

                        var airSegment1 = getAirSegment(searchResults, airSegmentKey1);

                        if (airSegment1 != null) {
                            // airSegment1['TotalPrice'] = thisTotalPrice;
                            // arrFlights['TotalAirPrice'] += airSegment1['TotalPrice'];
                            airSegment1['DestinationDisplay'] = cityNamesService.getCityNameForCode(airSegment1['!Destination']);
                            
                            arrFlights['onward'] = airSegment1;
                            
                            // resAirSegments.push(airSegment1);
                        }

                        if (airSegmentKey2) { //!twoWay && 
                            var airSegment2 = getAirSegment(searchResults, airSegmentKey2);
                            if (airSegment2 != null) {
                                // airSegment2['TotalPrice'] = thisTotalPrice;
                                // arrFlights['TotalAirPrice'] += airSegment2['TotalPrice'];
                                airSegment2['DestinationDisplay'] = cityNamesService.getCityNameForCode(airSegment2['!Destination']);
                                arrFlights['return'] = airSegment2;                                
                                // resAirSegments.push(airSegment2);
                            }
                        }

                        if (airSegment1 != null) {
                            // if (arrDestinations.indexOf(airSegment1['!Destination']) < 0) {
                            //     arrDestinations.push(airSegment1['!Destination']);
                            //     resAirSegments[airSegment1['!Destination']] = [];
                            // }
                            // if(resAirSegments[airSegment1['!Destination']] == undefined) {
                            //   resAirSegments[airSegment1['!Destination']] = [];
                            // }
                            if(!(airSegment1['!Destination'] in resAirSegments)) {
                                resAirSegments[airSegment1['!Destination']] = [];
                            }
                            //console.log('destination ----> '+airSegment1['!Destination']);
                            resAirSegments[airSegment1['!Destination']].push(arrFlights);
                        }
                    }//for
                } //have AirPricingSolutions
            } //have SearchResults

            var arrDispDestinations = Object.keys(resAirSegments);
            for (var i = arrDispDestinations.length - 1; i >= 0; i--) {
                dispDestinations[arrDispDestinations[i]] = false;
            };
            
            //console.log("resAirSegments", resAirSegments);

            //console.log("parseFlights(): END --> resAirSegments = ", resAirSegments);
            // respResults = resAirSegments;
        };

        var setSelectedDestination = function (destination) {
            selectedDestination = destination;
            updateSelectedTotalPrice();
        };
        var setSelectedAirSegment = function (airSegment) {
            selectedAirSegment = airSegment;
            updateSelectedTotalPrice();
        };
        var setSelectedHotel = function (hotel) {
            selectedHotel = hotel;
            updateSelectedTotalPrice();
        };

        var getSelectedDestination = function () {
            return selectedDestination;
        };
        var getSelectedAirSegment = function () {
            return selectedAirSegment;
        };
        var getSelectedHotel = function () {
            return selectedHotel;
        };



        var filterResults = function() {
            var allAirSegments = [];
            angular.copy(getAllFlights(), allAirSegments);
            // var allAirSegments = getAllFlights();

            var allHotelResults = hotelSearchService.getParsedCacheResults();

            var airSegments = [];
            // vm.hotelResults = [];

            for (var i = 0; i < allAirSegments.length; i++) {                
                var thisSegment = allAirSegments[i];

                var airPrice = 0;

                //2-way / 1-way filter
                // if(!!getTwoWay()) {                    
                //     for (var j = thisSegment['segments'].length - 1; j >= 0; j--) {
                //         // var thisFlight = thisSegment['segments'][i];
                //         thisSegment['segments'][j]['return'] = {}
                //     }
                //     // thisSegment['segments'][0]['return'] = {};
                //     // airPrice = util.string2Num(thisSegment['segments'][0]['TotalAirPrice']);
                // }else{
                //     // airPrice = util.string2Num(thisSegment['segments'][0]['TotalAirPrice']);
                // }
                airPrice = util.string2Num(thisSegment['segments'][0]['TotalAirPrice']);
                allAirSegments[i]['hotels'] = [];

                //city strike filter
                if(!!dispDestinations[thisSegment['destination']] == true) {
                    continue;
                }

                var destCityCode = cityNamesService.getCityCodeAlias(thisSegment['destination']);
                // console.log('destCityCode', destCityCode);


                
                // if(thisSegment['segments'][0]['return'] != undefined && thisSegment['segments'][0]['return'] != null) {
                //     airPrice += util.string2Num(thisSegment['segments'][0]['return']['TotalPrice']);
                // }
                var thisHotels = [], thisFlightHotelMin=0;
                if(allHotelResults[destCityCode] != undefined  && allHotelResults[destCityCode] != null) { //twoWay && 

                    //budget filter
                    for (var j = 0; j < allHotelResults[destCityCode].length; j++) {
                        var thisHotel = allHotelResults[destCityCode][j];
                        var hotelPrice = util.string2Num(thisHotel['TotalMinAmountNum']);

                        var total = airPrice+hotelPrice;
                        // console.log('destCityCode='+destCityCode+', airPrice='+airPrice+', hotelPrice='+hotelPrice+', total='+total+', budget='+budget);

                        if(  total+bookingCharges >  budget || thisHotel['Distance'] > distance) {
                            // console.log('-----> skip');
                            continue;
                        }else{
                            // console.log('-----> add');
                            if(total < thisFlightHotelMin || thisFlightHotelMin == 0) {
                                thisFlightHotelMin = total;
                            }
                            thisHotels.push(thisHotel);
                        }
                    }
                    if(thisHotels.length > 0) {
                        util.sortObjects(thisHotels, 'TotalMinAmountNum');                                
                    }
                    
                }else{
                    continue;
                }
                if(thisHotels.length > 0) {
                    thisSegment['hotels'] = thisHotels;
                    thisSegment['MinTotalPrice'] = thisFlightHotelMin;
                    airSegments.push(thisSegment);
                }
            }//for
            
            util.sortObjects(airSegments, 'MinTotalPrice');
            return airSegments;
        };

        var updateRelativePricings = function(sortedSegments) {
            if(sortedSegments && sortedSegments.length > 0) {
                var minPrice = util.string2Num(sortedSegments[0]['TotalAirPrice']);
                sortedSegments[0]['relativeAirPrice'] = minPrice;
                sortedSegments[0]['relativeAirPriceDisplay'] = minPrice + ' €';

                for (var i = sortedSegments.length - 1; i > 0; i--) {
                    var thisHotelPrice = util.string2Num(sortedSegments[i]['TotalAirPrice']);
                    var thisRelativePrice = (thisHotelPrice - minPrice);
                    
                    sortedSegments[i]['relativeAirPrice'] = thisRelativePrice;
                    sortedSegments[i]['relativeAirPriceDisplay'] = ' +' + thisRelativePrice + ' € más';
                };
            }
        };


        var updateSelectedTotalPrice = function() {
            // filterResults(getBudget());

            if(selectedDestination) {
                var flights = getFlightsForDestination(selectedDestination);
                selectedTotalPrice = flights['MinTotalPrice'];
            }
            if(selectedAirSegment) {
                var hotelPrice = 0;
                var airPrice = util.string2Num(selectedAirSegment['TotalAirPrice']);
                if(flights['hotels'] && flights['hotels'][0]) {
                    hotelPrice = util.string2Num(flights['hotels'][0]['TotalMinAmountNum']);
                }
                selectedTotalPrice = airPrice + hotelPrice;
            }
            if(selectedHotel) {
                var airPrice = util.string2Num(selectedAirSegment['TotalAirPrice']);
                var hotelPrice = util.string2Num(selectedHotel['TotalMinAmountNum']);
                selectedTotalPrice = airPrice + hotelPrice;                
            }
            selectedTotalPrice += bookingCharges;

        };

        var getSelectedTotalPrice = function() {
            return selectedTotalPrice;
        };


        return {
            setBudget: setBudget,
            getBudget: getBudget,
            setDistance: setDistance,
            getDistance: getDistance,
            getTwoWay: getTwoWay,
            setTwoWay: setTwoWay,
            newSearchRequest: newSearchRequest,
            getResultDestinations: getResultDestinations,
            getDispDestinations: getDispDestinations,
            setDispDestinations: setDispDestinations,
            setNumTravellers: setNumTravellers,
            setOrigin: setOrigin,
            getNumTravellers: getNumTravellers,
            setStartDate: setStartDate,
            getStartDate: getStartDate,
            setEndDate: setEndDate,
            getEndDate: getEndDate,
            getLowFareSearchResults: getLowFareSearchResults,
            getSavedResults: getSavedResults,
            //parseFlights: parseFlights,
            getAllFlights: getAllFlights,
            // getFlights: getFlights,
            getFlightsForDestination: getFlightsForDestination,
            setSelectedDestination: setSelectedDestination,
            setSelectedAirSegment: setSelectedAirSegment,
            setSelectedHotel: setSelectedHotel,
            getSelectedDestination: getSelectedDestination,
            getSelectedAirSegment: getSelectedAirSegment,
            getSelectedHotel: getSelectedHotel,
            getAllDestinations: getAllDestinations,
            setSearchDestinations: setSearchDestinations,
            clearResults: clearResults,
            filterResults: filterResults,
            updateRelativePricings: updateRelativePricings,
            getSelectedTotalPrice: getSelectedTotalPrice,
            getBookingCharges: getBookingCharges

        };

    }]);
