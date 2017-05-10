(function () {
	'use strict';

	angular
		.module('core.auth')
		.factory('AuthInterceptor', AuthInterceptor)
		.config(http);

	AuthInterceptor.$inject = ['$location', '$q', 'AuthToken', 'toastr', '$timeout'];
	http.$inject = ['$httpProvider'];

	function AuthInterceptor($location, $q, AuthToken, toastr, $timeout) {
		var service = {
			request: request,
			responseError: responseError
		};

		return service;

		function request(config) {
			config.headers = config.headers || {};
			if (AuthToken.ler()) {
				config.headers.Authorization = 'Bearer ' + AuthToken.ler();
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