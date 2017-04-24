(function () {

	'use strict';

	angular
		.module('app.layout')
		.directive('menuTopo', menuTopo);

	function menuTopo() {
		var directive = {
			restrict: 'E',
			templateUrl: 'src/app/layout/menu-topo.html'
		};

		return directive;
	}

})();