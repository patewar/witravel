'use strict';

/**
 * @ngdoc function
 * @name ngWitravelApp.service:hotelImagesService
 * @description
 * # hotelImagesService service
 * Service of the ngWitravelApp
 */
angular.module('ngWitravelApp')
  .service('hotelImagesService', ['$http', '$q', 'wiConfig', function ($http, $q, wiConfig) {
    
    var searchStatus = false;
    var respResults = [];

    function successFunction(thisResponse)  {
        var media = thisResponse.data['HotelPropertyWithMediaItems'];
        var hotelcode = media['HotelProperty']['!HotelCode'];

        var thisResult = {};
        thisResult['carouselIndex'] = 0;
        thisResult['images'] = media['MediaItem'];
        thisResult['HotelCode'] = hotelcode;

        respResults.push(thisResult);
        return true;
                 
    }    
    function failureFunction() {
      return false;
    }

    function setSearchStatus(status) {
      searchStatus = status;
    }

    function getSearchStatus() {
      return searchStatus;
    }
    
    var getHotelImageResults = function(hotelCode) {

      // for (var i = respResults.length - 1; i >= 0; i--) {
      //   if(respResults[i]['HotelCode'] == hotelCode) {
      //     console.log('--------- cache results');
      //     var deferred = $q.defer();
      //     deferred.resolve(respResults[i]);
      //     return deferred.promise;

      //   }
      // };
      console.log('--------- new results');

      return $http({
        method: 'GET',
        url: wiConfig.serviceURL+'/hotels/hotel-media-links/hotelCode/'+hotelCode
      }).then(successFunction, failureFunction);
      
    };

    var getSavedResults = function() {
      return searchResults;
    };


    var getHotelIimages = function() {
      return respResults;
    };


    var getCachedResults = function() {
      return respResults;
    };

    var getCachedResultsForHotel = function(hotelCode) {
      for (var i = respResults.length - 1; i >= 0; i--) {
        if(parseInt(respResults[i]['HotelCode']) == parseInt(hotelCode)) {      
            return respResults[i];
        }
      }
      return {};
    };

    return {
      setSearchStatus: setSearchStatus,
      getSearchStatus: getSearchStatus,
      getHotelImageResults: getHotelImageResults,
      getCachedResults: getCachedResults,
      getCachedResultsForHotel: getCachedResultsForHotel
      
    };

  }]);
