(function () {
    'use strict';

    angular
            .module('core.authApp2')
            .factory('AuthInterceptor', AuthInterceptor)
            .config(http);

    AuthInterceptor.$inject = ['$location', '$q', 'AuthTokenApp2', 'toastr', '$timeout'];
    http.$inject = ['$httpProvider'];

    function AuthInterceptor($location, $q, AuthTokenApp2, toastr, $timeout) {
        var service = {
            request: request,
            responseError: responseError
        };

        return service;

        function request(config) {
            config.headers = config.headers || {};
            if (AuthTokenApp2.ler()) {
                config.headers.Authorization = 'Bearer ' + AuthTokenApp2.ler();
            }

            return config;
        }

        function responseError(response) {


            return $q.reject(response);
        }
    }

    function http($httpProvider) {
        $httpProvider.interceptors.push('AuthInterceptor');
    }
})();