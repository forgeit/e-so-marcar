(function () {

	'use strict';

	angular
		.module('app.layout')
		.directive('btnRemover', btnRemover);

	function btnRemover() {
		var directive = {
			restrict: 'E',
			templateUrl: 'src/app/layout/btn-remover.html'
		};

		return directive;
	}

})();