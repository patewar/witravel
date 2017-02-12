'use strict';

/**
 * @ngdoc function
 * @name ngWitravelApp.service:geolocationService
 * @description
 * # geolocationService service
 * Service of the ngWitravelApp
 */
angular.module('ngWitravelApp')
  .service('geolocationService', ['$http', '$window', '$q', 'wiConfig', function ($http, $window, $q, wiConfig) {
    
    var res=null;
    var getGeoLocation = function() {
          return $http({
            method: 'JSONP',
            url: 'https://geoip-db.com/jsonp/geoip.php?json=JSON_CALLBACK',
          
          }).then(function successFunction(response){            
            res = response.data;            
            return res;
          }, function failureFunction(response){
            return false;
          });
    };

    var getGeocoder = function() {
      var defer = $q.defer();

      var result = {
        'country': '',
        'city': '',
      };
 
      if($window.google != undefined) {   
      
        var geocoder = new $window.google.maps.Geocoder();  
        
        if ($window.navigator.geolocation) {        
          $window.navigator.geolocation.getCurrentPosition(function successFunction(position) {
            // console.log('position', position)  
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;





            var foundCountry = false, foundCity = false;
            var latlng = new google.maps.LatLng(lat, lng);

            geocoder.geocode({'latLng': latlng}, function(results, status) {

              if (status == google.maps.GeocoderStatus.OK) {
              
                if (results[1]) {
                  //formatted address
                  //alert(results[0].formatted_address)
                  //find country name
                  for (var i=0; i<results.length; i++) {
                     for (var j=0; j<results[i].address_components.length; j++) {
                    //for (var b=0;b<results[0].address_components[i].types.length;b++) {
                      var address_component = results[i].address_components[j];
                      if(foundCity && foundCountry) {
                        break;
                      }
                      //there are different types that might hold a city admin_area_lvl_1 usually does in come cases looking for sublocality type will be more appropriate
                      if (!foundCity && address_component.types[0] == "locality"){
                          foundCity = true;
                          //console.log("town:"+address_component.long_name);
                          result['city'] = address_component.long_name;
                      }

                      if (!foundCountry &&  address_component.types[0] == "country"){ 
                        foundCountry = true;
                          // console.log("country:"+address_component.long_name); 
                          result['country'] = address_component.long_name;
                      }

                    //}
                    }
                  }
                  // console.log(result);
                  defer.resolve(result);
                                  
                } else {
                  //alert("No results found");
                  defer.resolve(false);
                }
              } else {
                //alert("Geocoder failed due to: " + status);
                defer.resolve(false);
              }
            });

            // codeLatLng(lat, lng, function(res) {
            //   if(res !== false) {
            //     document.getElementById('result').innerHTML = '<b>City:</b>'+res.city+'<br><b>Country:</b>'+res.country;
            //   }
            // });
          }, 
          function errorFunction(err) {
            console.log(err);
            alert("Geocoder failed");
          },{
  enableHighAccuracy: true,
  timeout: 5000,
  maximumAge: 0       
});
        }else{
          alert("navigator.geolocation is null");
        } 
      }else{
        defer.resolve(false);     
      }
      return defer.promise;
    };



    return {
      getLocation: getGeoLocation,
      getGeocoder: getGeocoder  
    };

  }]);
