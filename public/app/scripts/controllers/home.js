'use strict';

/**
 * @ngdoc function
 * @name ngWitravelApp.controller:HomeCtrl
 * @description
 * # HomeCtrl
 * Controller of the ngWitravelApp
 */
angular.module('ngWitravelApp')
    .controller('HomeCtrl', ['$http', '$state', '$q', 'wiConfig', 'lowFareSearchService', 'hotelSearchService', 'geolocationService', 
        function ($http, $state, $q, wiConfig, lowFareSearchService, hotelSearchService, geolocationService) {

            var vm = this;

            //console.log("home.js, geo=", geolocationService.getGeocoder());
            
            vm.origin = 'Loading...';
            vm.loading = true;
            /*geolocationService.getGeocoder().then(function(data){            
                 console.log('home.js, geo=', data);
                vm.origin = data['city'];
                vm.loading = false;
            }, function(data){
                vm.origin = 'MAD';
                vm.loading = false;
            });*/
            
            vm.origin = 'MADRID';
                vm.loading = false;
            function lowFareSearch() {
                // console.log('LowFareSearchCriteria');
                
                vm.loading = true;
                lowFareSearchService.clearResults();
                
                lowFareSearchService.setBudget(vm.budget);
                lowFareSearchService.setBudget(vm.budget);
                lowFareSearchService.setNumTravellers(vm.numTravellers);
                lowFareSearchService.setOrigin(vm.origin);
                

                lowFareSearchService.newSearchRequest(successFunction, failureFunction);

                // var allDestinations = lowFareSearchService.getAllDestinations();
                // // var source = 'PAR';
                // // var sourceIndex = allDestinations.indexOf(source);
                // // allDestinations.splice(sourceIndex, 1);
                // var dest1 = allDestinations.slice(0, 6);
                // var dest2 = allDestinations.slice(6, 12);
                // var dest3 = allDestinations.slice(12);

                // var defer = $q.defer();
                // var promises = [];                

                // promises.push(hotelSearchService.getCacheHotelSearchResults());

                // lowFareSearchService.setSearchDestinations(dest1);
                // promises.push(lowFareSearchService.getLowFareSearchResults());

                // lowFareSearchService.setSearchDestinations(dest2);
                // promises.push(lowFareSearchService.getLowFareSearchResults());

                // lowFareSearchService.setSearchDestinations(dest3);
                // promises.push(lowFareSearchService.getLowFareSearchResults());   
                
                                
                // $q.all(promises).then(successFunction, failureFunction);

                // lowFareSearchService.getLowFareSearchResults().then(successFunction, failureFunction);
                // $state.go("destinations");
            }

            // vm.user = {name: 'guest'};
            function successFunction(response) {
                vm.loading = false;
                // console.log('successFunction', response);
                if (response[0] === true || response[1] === true || response[2] === true) {
                    $state.go("destinations");
                }
            }

            function failureFunction(response) {
                vm.loading = false;
                console.log('failureFunction', response)
            }

            vm.budget = lowFareSearchService.getBudget();
            vm.numTravellers = lowFareSearchService.getNumTravellers();
            vm.lowFareSearch = lowFareSearch;

        }]);
