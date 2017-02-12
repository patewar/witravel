'use strict';

/**
 * @ngdoc overview
 * @name ngWitravelApp
 * @description
 * # ngWitravelApp
 *
 * Main module of the application.
 */
angular
    .module('ngWitravelApp', [
        'ngAnimate',
        'ngCookies',
        'ngResource',
        'ui.router',
        'ngSanitize',
        'ngTouch',
        'angularRandomString',
        'ngMaterial',
        'angular-carousel'
    ])
    .config(function($mdThemingProvider) {
        $mdThemingProvider.theme('default')
        .primaryPalette('indigo')
        .accentPalette('orange');
    })
    .config(function ($stateProvider, $urlRouterProvider) {

        /**
         *  Routes
         */
            // For any unmatched url, redirect to /home
        $urlRouterProvider.otherwise("/");

        $stateProvider
            .state('home', {
                url: "/",
                templateUrl: "views/home.html",
                controller: 'HomeCtrl',
                controllerAs: 'home'
            }).state('destinations', {
            url: "/destinations",
            templateUrl: "views/destinations.html",
            controller: 'DestinationsCtrl',
            controllerAs: 'destinations'
        }).state('flightResults', {
            url: "/flight_results",
            templateUrl: "views/flight_results.html",
            controller: 'FlightsCtrl',
            controllerAs: 'flights'
        }).state('hotelResults', {
            url: "/hotel_results",
            templateUrl: "views/hotel_results.html",
            controller: "HotelsCtrl",
            controllerAs: 'hotels'
        }).state('payment', {
            url: "/payment",
            templateUrl: "views/payment.html",
            controller: "PaymentCtrl",
            controllerAs: 'payment'
        });
        


        // $routeProvider
        //   .when('/', {
        //     templateUrl: 'views/main.html',
        //     controller: 'MainCtrl',
        //     controllerAs: 'main'
        //   })
        //   .when('/about', {
        //     templateUrl: 'views/about.html',
        //     controller: 'AboutCtrl',
        //     controllerAs: 'about'
        //   })
        //   .otherwise({
        //     redirectTo: '/'
        //   });
    })
    .constant("wiConfig", {
        "env": "local",
        "serviceURL": "/qa/public",
        //"serviceURL": "/witravel/public",
    });

