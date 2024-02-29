angular.module('app', ['app.service', 'app.controller', 'angular.filter'])
   
    .config(function ($interpolateProvider, $locationProvider) {

        $interpolateProvider.startSymbol('<%');
        $interpolateProvider.endSymbol('%>');
        $locationProvider.html5Mode({
            enabled: true,
            requireBase: false
        });

    })

    ;