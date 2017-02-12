'use strict';

/**
 * @ngdoc function
 * @name ngWitravelApp.controller:DestinationsCtrl
 * @description
 * # DestinationsCtrl
 * Controller of the ngWitravelApp
 */
angular.module('ngWitravelApp')
    .controller('DestinationsCtrl', ['$http', '$state', 'wiConfig', 'lowFareSearchService', 'hotelSearchService', 'cityNamesService', 'util',
        function ($http, $state, wiConfig, lowFareSearchService, hotelSearchService, cityNamesService, util) {

            var vm = this;


            vm.budgetChange = function() {
                //console.log('budgetChange', vm.budget);
                lowFareSearchService.setBudget(vm.budget);
                vm.airSegments = lowFareSearchService.filterResults();
            };
            

            vm.distanceChange = function() {
                //console.log('budgetChange', vm.budget);
                lowFareSearchService.setDistance(vm.distance);
                vm.airSegments = lowFareSearchService.filterResults();
            };

            vm.detinationsChange = function() {
                //console.log(vm.arrDispDestinations);
                console.log(vm.dispDestinations);
                lowFareSearchService.setDispDestinations(vm.dispDestinations);
                vm.airSegments = lowFareSearchService.filterResults();

            };

            vm.twoWayChange = function() {
                util.showNewSearchConfirm(
                    function() {
                        vm.loading = true;
                        console.log('twoWayChange', vm.twoWay);
                        lowFareSearchService.setTwoWay(vm.twoWay);
                        lowFareSearchService.newSearchRequest(
                            function() {
                                vm.loading = false;
                                // refreshFlights();
                                vm.airSegments = lowFareSearchService.filterResults();
                            }, 
                            function(){
                                vm.loading = false;
                            }
                        );                    
                    }, 
                    function(){
                        vm.twoWay = !vm.twoWay;
                    }
                );                
                // vm.airSegments = lowFareSearchService.filterResults();
                // console.log(vm.airSegments);
            };   

            vm.datesChange = function() {
                util.showNewSearchConfirm(
                    function() {
                        vm.loading = true;
                        lowFareSearchService.setStartDate(vm.startDate);
                        lowFareSearchService.setEndDate(vm.endDate);
                        lowFareSearchService.newSearchRequest(
                            function() {
                                vm.loading = false;
                                // refreshFlights();
                                vm.airSegments = lowFareSearchService.filterResults();
                            }, 
                            function(){
                                vm.loading = false;
                            }
                        );                    
                    }, 
                    function(){
                        // vm.startDate = lowFareSearchService.getStartDate();
                        // vm.endDate = lowFareSearchService.getEndDate();
                    }
                );                
                // vm.airSegments = lowFareSearchService.filterResults();
                // console.log(vm.airSegments);
            };                       

            
            //vm.results = lowFareSearchService.searchResults

            //console.log('DestinationsCtrl, searchResults', lowFareSearchService.getSavedResults());

            //lowFareSearchService.parseFlights();
            //console.log('DestinationsCtrl, getAllFlights', lowFareSearchService.getAllFlights());

            // vm.user = {name: 'guest'};
            function successFunction(response) {
                // console.log('successFunction', response)
                // vm.results = response.data;
            }

            function failureFunction(response) {
                console.log('failureFunction', response)
            }

            var destinationSelected = function (destination) {
                //alert("DestinationsCtrl::destinationSelected = "+destination);
                lowFareSearchService.setSelectedDestination(destination);
                lowFareSearchService.setSelectedHotel(null);
                lowFareSearchService.setSelectedAirSegment(null);

                $state.go('flightResults');
            };

            vm.destinationSelected = destinationSelected;

            vm.budget = lowFareSearchService.getBudget();
            vm.distance = lowFareSearchService.getDistance();

            vm.resultDestinations = lowFareSearchService.getResultDestinations();
            vm.dispDestinations = lowFareSearchService.getDispDestinations();
            vm.twoWay = lowFareSearchService.getTwoWay();

            vm.startMinDate = new Date();
            vm.endMinDate = new Date();
            vm.startDate = lowFareSearchService.getStartDate(); //Date object
            vm.endDate = lowFareSearchService.getEndDate(); //Date object



            vm.airSegments = lowFareSearchService.filterResults();

            
        }]);
