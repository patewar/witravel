'use strict';

/**
 * @ngdoc function
 * @name ngWitravelApp.controller:FlightsCtrl
 * @description
 * # FlightsCtrl
 * Controller of the ngWitravelApp
 */
angular.module('ngWitravelApp')
  .controller('FlightsCtrl', ['$http', '$state','wiConfig', 'lowFareSearchService', 'util', 'cityNamesService', 
  function ($http, $state, wiConfig, lowFareSearchService, util, cityNamesService) {

    var vm = this;
    
    var flightSelected = function (flight) {
        lowFareSearchService.setSelectedAirSegment(flight);
        vm.selectedTotalPrice = lowFareSearchService.getSelectedTotalPrice();
    };
    

    var redirectToHotels = function(flight) {
        flightSelected(flight);
        $state.go('hotelResults');     
      // $state.go('payment');
    };

    vm.budgetChange = function() {
        //console.log('budgetChange', vm.budget);
        lowFareSearchService.setBudget(vm.budget);
        refreshFlights(); 
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
                    refreshFlights();
                    }, function(){vm.loading = false;}
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


    var refreshFlights = function() {
        vm.selectedDestAirSegments = [];
        vm.airSegments = lowFareSearchService.filterResults();
        for (var i = vm.airSegments.length - 1; i >= 0; i--) {
            if(cityNamesService.getCityCodeAlias(vm.selectedDestination) == cityNamesService.getCityCodeAlias(vm.airSegments[i]['destination'])) {
                vm.selectedDestAirSegments = vm.airSegments[i];
                break;
            }
        }

        if(!lowFareSearchService.getSelectedAirSegment() && vm.selectedDestAirSegments && vm.selectedDestAirSegments.length > 0) {
            flightSelected(vm.selectedDestAirSegments['segments'][0]);
        }    
        if(vm.selectedDestAirSegments && vm.selectedDestAirSegments['segments']) {
            lowFareSearchService.updateRelativePricings(vm.selectedDestAirSegments['segments']);
        }

        lowFareSearchService.setSelectedDestination(vm.selectedDestination);
         lowFareSearchService.setSelectedHotel(null);
         lowFareSearchService.setSelectedAirSegment(null);
        vm.selectedTotalPrice = lowFareSearchService.getSelectedTotalPrice();
    };
    
    //var flightReserve =function(){
     // vm.
    //}; 

    vm.flightSelected = flightSelected;
    vm.budget = lowFareSearchService.getBudget();
    vm.twoWay = lowFareSearchService.getTwoWay();

    vm.startMinDate = new Date();
    vm.endMinDate = new Date();
    vm.startDate = lowFareSearchService.getStartDate(); //Date object
    vm.endDate = lowFareSearchService.getEndDate(); //Date object


    
    vm.redirectToHotels = redirectToHotels;
    
    
    vm.selectedDestination = lowFareSearchService.getSelectedDestination();
    // vm.selectedTotalPrice = lowFareSearchService.getSelectedTotalPrice();
    
    
    refreshFlights();
    // if(!lowFareSearchService.getSelectedAirSegment() && vm.selectedDestAirSegments && vm.selectedDestAirSegments.length > 0) {
    //     flightSelected(vm.selectedDestAirSegments['segments'][0]);
    // }    
    // if(vm.selectedDestAirSegments && vm.selectedDestAirSegments['segments']) {
    //     lowFareSearchService.updateRelativePricings(vm.selectedDestAirSegments['segments']);
    // }
    
    
  }]);
