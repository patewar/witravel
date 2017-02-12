'use strict';

angular.module('ngWitravelApp')
    .directive('destinationTile', function () {
        return {
            restrict: 'E',
            scope: {
                'destination': '=',
                'destinationSelected': '&onSelected'
            },
            templateUrl: 'partials/destination_tile.html'
        };
    });
