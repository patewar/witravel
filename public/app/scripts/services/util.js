'use strict';

/**
 * @ngdoc function
 * @name ngWitravelApp.service:util
 * @description
 * # util service
 * Service of the ngWitravelApp
 */
angular.module('ngWitravelApp')
  .service('util', ['$mdDialog', function ($mdDialog) {
    
    var res=null;

    var weekdays = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];

    var sortObjects = function(arrObjects, key) {
        if(arrObjects && arrObjects.length > 0) {
          arrObjects.sort(function(a, b) {
            var x = string2Num(a[key]), 
                y = string2Num(b[key])
            // console.log(x + ' > ' + y +' --> '+(x > y));
            return x - y;
          });
        }
    };

    var nextDayofWeekDate = function nextDayofWeekDate(dayOfWeek) {
      var ret = new Date();
      if(dayOfWeek == ret.getDay()) {
        return ret;
      }
      ret.setDate(ret.getDate() + (dayOfWeek - 1 - ret.getDay() + 7) % 7 + 1);      
      return ret;
    };

    var nextFridayDate = function nextFridayDate(dayOfWeek) {
      var ret = nextDayofWeekDate(5);
      return ret;
    };

    var nextSundayDate = function nextSundayDate(dayOfWeek) {
      var ret = nextDayofWeekDate(5);
      ret.setDate(ret.getDate() +  2);      
      return ret;
    };    

    var convertDate2JsString = function(objDate) {
      var res = objDate.getMonth()+1+'/'+objDate.getDate()+'/'+objDate.getFullYear();
      return res;
      // var arrDate = strDate.split("/");
      // return arrDate[2]+'-'+arrDate[0]+'-'+arrDate[1];
    };

    var convertDate2PhpString = function(objDate) {
      var res = objDate.getFullYear()+'-'+("0" + (objDate.getMonth() + 1)).slice(-2)+'-'+("0" + objDate.getDate()).slice(-2);
      return res;
      // var arrDate = strDate.split("/");
      // return arrDate[2]+'-'+arrDate[0]+'-'+arrDate[1];
    };

    var getDayNameForDateObj = function(objDate) {      
      return weekdays[objDate.getDay()];      
    };

    var string2Num = function(str) {
      if(str) {
        str = str.toString().replace(/,/g, '');
        
      }
      return parseInt(str, 10);
    };

    var showNewSearchConfirm = function(yesFunction, noFunction) {
        // Appending dialog to document.body to cover sidenav in docs app
        var confirm = $mdDialog.confirm()
          .title('Would you like to serch again?')
          .textContent('This will take time, do you want to continue?')
          .ariaLabel('Search')
          .ok('Please do it!')
          .cancel('No');
        $mdDialog.show(confirm).then(function() {
            // $scope.status = 'You decided to get rid of your debt.';
            yesFunction();
        }, function() {
            // $scope.status = 'You decided to keep your debt.';
            noFunction();
        });
    };

    return {
      sortObjects: sortObjects,
      nextDayofWeekDate: nextDayofWeekDate,
      nextFridayDate: nextFridayDate,
      nextSundayDate: nextSundayDate,
      convertDate2JsString: convertDate2JsString,
      convertDate2PhpString: convertDate2PhpString,
      string2Num: string2Num, 
      showNewSearchConfirm: showNewSearchConfirm,
      getDayNameForDateObj: getDayNameForDateObj
    }
  }]);
