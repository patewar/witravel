'use strict';

/**
 * @ngdoc function
 * @name ngWitravelApp.controller:HotelsCtrl
 * @description
 * # HotelsCtrl
 * Controller of the ngWitravelApp
 */
angular.module('ngWitravelApp')
    .controller('PaymentCtrl', ['$http', '$state', '$timeout', 'wiConfig', 'lowFareSearchService', 'hotelSearchService', 'randomString', 'util',
        function ($http, $state, $timeout, wiConfig, lowFareSearchService, hotelSearchService, randomString, util) {

            var vm = this;
var searchStatus = false; 

            vm.selectedDestination = lowFareSearchService.getSelectedDestination();
            vm.selectedAirSegment = lowFareSearchService.getSelectedAirSegment();
            vm.selectedHotel = lowFareSearchService.getSelectedHotel();
            
            vm.startDate = lowFareSearchService.getStartDate(); //Date object
            vm.endDate = lowFareSearchService.getEndDate(); //Date object

            vm.selectedTotalPrice = lowFareSearchService.getSelectedTotalPrice();
            
            vm.bookingCharges = lowFareSearchService.getBookingCharges();
            
           vm.transid = randomString(6);
            vm.status = 'authorized';
            vm.amount = '395.00';
            vm.first_name = 'Chetan';
            vm.last_name = 'Kumar';
            vm.email = 'Chetan@gmail.com';
            vm.phone = '000012345';
            vm.maskedcc = '411111******1111';  
            vm.cc_num = '4111111111111111';
            vm.cc_type = 'visa';
            vm.cc_exp_mon = '07';
            vm.cc_exp_year = 's17';
            vm.cc_cvv = '123';
           
           // console.log(vm.selectedAirSegment.AirSegment.onward.CodeshareInfo.!OperatingFlightNumber);
      var data  ={ "transid" : vm.transid,
                        "status":vm.status,
                        "totalamount":vm.amount,
                        "first_name":vm.first_name,
                        "last_name":vm.last_name,
                        "email":vm.email,
                        "phone":vm.phone,
                        "maskedcc":vm.maskedcc,
                        "cc_num":vm.cc_num,
                        "cc_type":vm.cc_type,
                        "cc_exp_mon":vm.cc_exp_mon,
                        "cc_exp_year":vm.cc_exp_year,
                        "cc_cvv":vm.cc_cvv,
                        "startDate":vm.startDate,
                        "endDate":vm.endDate,
                        "AirSegment":vm.selectedAirSegment
                        } 
                        //console.log('checking');
           $http({
                //method: 'GET',
                method: 'POST',
                type : 'json',
                data: data,
                url: wiConfig.serviceURL + '/flights/flight-pricing'
                /*url: wiConfig.serviceURL + '/flights/flight-reservation'+'/transid/' + vm.transid + 
                        '/status/'+vm.status+'/totalamount/'+vm.amount+'/first_name/'+vm.first_name+'/last_name/'+vm.last_name
                        +'/email/'+vm.email+'/phone/'+vm.phone+'/maskedcc/'+vm.maskedcc+'/cc_num/'+vm.cc_num+
                        '/cc_type/'+vm.cc_type+'/cc_exp_mon/'+vm.cc_exp_mon+'/cc_exp_year/'+vm.cc_exp_year+
                        '/cc_cvv/'+vm.cc_cvv+'/startDate/'+vm.startDate+'/endDate/'+vm.endDate+'/AirSegment/'+vm.selectedAirSegment
                 */       
           }).then(function successFunction(response) {

                vm.AirItinerary=response.data.AirItinerary;
                vm.AirPriceResult=response.data.AirPriceResult;
                //searchResults = response.data;
                //parseFlights(response.data);
                searchStatus = false;
                return searchStatus;
            }, function failureFunction(response) {
                return searchStatus;
            });

            //return false;
           
            
            

            vm.doPayment = function doPayment() {
               
             var data  ={ "transid" : vm.transid,
                        "status":vm.status,
                        "totalamount":vm.amount,
                        "first_name":vm.first_name,
                        "last_name":vm.last_name,
                        "email":vm.email,
                        "phone":vm.phone,
                        "maskedcc":vm.maskedcc,
                        "cc_num":vm.cc_num,
                        "cc_type":vm.cc_type,
                        "cc_exp_mon":vm.cc_exp_mon,
                        "cc_exp_year":vm.cc_exp_year,
                        "cc_cvv":vm.cc_cvv,
                        "startDate":vm.startDate,
                        "endDate":vm.endDate,
                        "AirSegment":vm.selectedAirSegment,
                        "AirItinerary":vm.AirItinerary,
                        "AirPriceResult":vm.AirPriceResult,
                        "SelectedHotel":vm.selectedHotel
                        } 
               vm.flightstatus=false;
               vm.hotelstatus=false;
            	$http({
                //method: 'GET',
                method: 'POST',
                type : 'json',
                data: data,
                url: wiConfig.serviceURL + '/flights/flight-reservation'

                /*url: wiConfig.serviceURL + '/flights/flight-reservation'+'/transid/' + vm.transid + 
                        '/status/'+vm.status+'/totalamount/'+vm.amount+'/first_name/'+vm.first_name+'/last_name/'+vm.last_name
                        +'/email/'+vm.email+'/phone/'+vm.phone+'/maskedcc/'+vm.maskedcc+'/cc_num/'+vm.cc_num+
                        '/cc_type/'+vm.cc_type+'/cc_exp_mon/'+vm.cc_exp_mon+'/cc_exp_year/'+vm.cc_exp_year+
                        '/cc_cvv/'+vm.cc_cvv+'/startDate/'+vm.startDate+'/endDate/'+vm.endDate+'/AirSegment/'+vm.selectedAirSegment
                 */       
            }).then(function successFunction(response) {
                //console.log('test')
                //searchResults = response.data;
                //parseFlights(response.data);
                 
                    $state.go("payment_success");
                
                searchStatus = true;
                return searchStatus;
            }, function failureFunction(response) {
                return searchStatus;
            });

            $http({
                //method: 'GET',
                method: 'POST',
                type : 'json',
                data: data,
                url: wiConfig.serviceURL + '/hotels/hotel-reservation'

                /*url: wiConfig.serviceURL + '/flights/flight-reservation'+'/transid/' + vm.transid + 
                        '/status/'+vm.status+'/totalamount/'+vm.amount+'/first_name/'+vm.first_name+'/last_name/'+vm.last_name
                        +'/email/'+vm.email+'/phone/'+vm.phone+'/maskedcc/'+vm.maskedcc+'/cc_num/'+vm.cc_num+
                        '/cc_type/'+vm.cc_type+'/cc_exp_mon/'+vm.cc_exp_mon+'/cc_exp_year/'+vm.cc_exp_year+
                        '/cc_cvv/'+vm.cc_cvv+'/startDate/'+vm.startDate+'/endDate/'+vm.endDate+'/AirSegment/'+vm.selectedAirSegment
                 */       
           }).then(function successFunction(response) {
               // console.log(response.data)
                //searchResults = response.data;
                //parseFlights(response.data);
                searchStatus = false;
                
                return searchStatus;
            }, function failureFunction(response) {
            searchStatus = false;
                return searchStatus;
            });
            
                //vm.loading = true;
                //if(vm.flightstatus==true && vm.hotelstatus=true){ 
              
               // return false;
            //}
            }
          
            //return false;
        }]);
        
