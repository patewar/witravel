'use strict';

/**
 * @ngdoc function
 * @name ngWitravelApp.controller:HotelsCtrl
 * @description
 * # HotelsCtrl
 * Controller of the ngWitravelApp
 */
angular.module('ngWitravelApp')
    .controller('PaymentCompleteCtrl', ['$http', '$state', '$timeout', 'wiConfig', 'PaymentCtrl','lowFareSearchService', 'hotelSearchService', 'randomString', 'util',
        function ($http, $state, $timeout, wiConfig, paymentCrtl, lowFareSearchService, hotelSearchService, randomString, util) {

            var vm = this;
var searchStatus = false; 


            vm.selectedDestination = lowFareSearchService.getSelectedDestination();
            vm.selectedAirSegment = lowFareSearchService.getSelectedAirSegment();
            //vm.selectedHotel = lowFareSearchService.getSelectedHotel();
            
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
                        }; 
                        
            

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
                        "AirItinerary":vm.AirItinerary
                        //"SelectedHotel":vm.selectedHotel
                        } 
               vm.flightstatus=false;
               vm.hotelstatus=false;
              
                return false;
           
            }
         
            return false;
        }]);
        
