'use strict';

/**
 * @ngdoc function
 * @name ngWitravelApp.service:hotelDetailsService
 * @description
 * # hotelDetailsService service
 * Service of the ngWitravelApp
 */
angular.module('ngWitravelApp')
  .service('hotelDetailsService', ['$http', '$q', 'wiConfig', 'lowFareSearchService', 'util', function ($http, $q, wiConfig, lowFareSearchService, util) {
    
    var respResults = [];

    function getHotelDetailsPromise(details, successFunction, failureFunction) {
      var deferred = $q.defer();
      deferred.resolve(details);
      return deferred.promise.then(function(response) {
         successFunction(response);
        }, function() { 
          failureFunction(); 
        });

    }

    var getHotelDetails = function(hotelCode, successFunction, failureFunction) {
      for (var i = respResults.length - 1; i >= 0; i--) {
        if(parseInt(respResults[i]['RequestedHotelDetails']['HotelProperty']['!HotelCode']) == parseInt(hotelCode)) {
          return getHotelDetailsPromise(respResults[i], successFunction, failureFunction);
        }
      }

      return $http({
        method: 'GET',
        url: wiConfig.serviceURL+'/hotels/hotel-details/hotelCode/'+ hotelCode + '/adults/' + lowFareSearchService.getNumTravellers() + '/startDate/' + util.convertDate2PhpString(lowFareSearchService.getStartDate()) + '/endDate/' + util.convertDate2PhpString(lowFareSearchService.getEndDate())
      }).then(function(response) {
        if(response.data.RequestedHotelDetails) {
          respResults.push(response.data);
          successFunction(response.data);
        }else{
          successFunction(false);
        }
         
      }, function() { 
        failureFunction(); 
      });
    };

    return {
      getHotelDetails: getHotelDetails
    };

  }]);
