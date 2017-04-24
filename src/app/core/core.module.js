(function () {
	
	'use strict';

	angular.module('app.core', [
		'ngRoute', 
		'ngCpfCnpj', 
		'ui.mask', 
		'datatables', 
		'datatables.bootstrap', 
		'core.utils', 
		'angular-loading-bar',
		'ngStorage',
		'angular-jwt',
		'core.auth']);

})();