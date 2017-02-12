'use strict';

angular.module('ngWitravelApp')
    .directive('flightTile', function () {
        return {
            restrict: 'E',
            scope: {
                flight: '=thisflight',
                twoway: '=roundtrip',
                'flightSelected': '&onSelected',
                'redirectToHotels': '&gotoHotels',
            },
            templateUrl: 'partials/flight_tile.html'
        };
    });
