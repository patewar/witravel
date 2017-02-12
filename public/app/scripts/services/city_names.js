'use strict';

/**
 * @ngdoc function
 * @name ngWitravelApp.service:hotelImagesService
 * @description
 * # hotelImagesService service
 * Service of the ngWitravelApp
 */
angular.module('ngWitravelApp')
  .service('cityNamesService', ['$http', function ($http) {
    
    var cityNameCodes = {};

    var arrCityCodeNames = {
      'AMS': 'Amsterdam',
      'BER': 'Berlin',
      'BCN': 'Barcelona',
      'IST': 'Istanbul',
      'LIS': 'Lisbon',
      'LON': 'London',
      'AGP': 'Malaga',
      'PAR': 'Paris',
      'CPH': 'Copenhagen',
      'ROM': 'Rome',
      'MAD': 'Madrid',
      'PRG': 'Prague',
      'BRU': 'Brussels',
      'VLC': 'Valencia',
      'SVQ': 'Sevilla',
      'MIL': 'Milan',
      'ATH': 'Athens',
      'LED': 'St.Petersburg'
    };


    var cityCodeAlias = {
      'RTM': 'AMS', 'EIN': 'AMS', 'DUS': 'AMS', 
      'SXF': 'BER', 'THF': 'BER', 'TXL': 'BER',
      'BLA': 'BCN', 'GRO': 'BCN', 
      'LCY': 'LON', 'LGW': 'LON', 'LHR': 'LON', 'LOZ': 'LON', 'STN': 'LON', 'LTN': 'LON', 'SEN': 'LON', 'LYX': 'LON', 
      'MMX': 'CPH', 
      'LBG': 'PAR', 'ORY': 'PAR', 'PHT': 'PAR', 'PRX': 'PAR', 'XCR': 'PAR', 'CDG': 'PAR', 'POX': 'PAR', 'BVA': 'PAR',
      'SAW': 'IST', 'TEQ': 'IST', 'YEI': 'IST', 
      'ANR': 'BRU', 'CRL': 'BRU', 
      'GRX': 'AGP', 'GIB': 'AGP',
      'CIA': 'ROM', 'FCO': 'ROM', 'PEG': 'ROM', 
      'KTW': 'KRK', 
      'HEL': 'TLL', 'TAY': 'TLL', 'TKU': 'TLL',
      'FAO': 'LIS',
      'PED': 'PRG',
      'XRY': 'SVQ',
      'LIN': 'MIL', 'MXP': 'MIL', 'BGY': 'MIL', 'LUG': 'MIL'
    };


    // $http.get('city_names_codes.json')
    //   .then(function(res) {
    //     cityNameCodes = res.data;                
    // });

    var getCityCodeAlias = function(code) {
      if(cityCodeAlias[code] != undefined) {
        return cityCodeAlias[code];
      }else{
        return code;
      }      
    };

  	var getCityNameForCode = function(code) {
      if(arrCityCodeNames[code] != undefined) {
        return arrCityCodeNames[code];
      }else if(cityCodeAlias[code] != undefined) {
        return getCityNameForCode(cityCodeAlias[code])
      }else{
        return code;
      }      
  	};

    var getCityCodeForName = function(name) {
      if(cityNameCodes[name] != undefined && cityNameCodes[name] != null) {
          return cityNameCodes[name];
      }else{
        return 'UNKNOWN';
      }
    };

    var getDestinationCityCodes = function() {
      var cityCodes = Object.keys(arrCityCodeNames);
      return cityCodes;
    };


    return {
      getCityCodeAlias: getCityCodeAlias,
      getCityNameForCode: getCityNameForCode,
      getDestinationCityCodes: getDestinationCityCodes     
    };

  }]);    