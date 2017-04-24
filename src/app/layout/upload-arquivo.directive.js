(function () {

	'use strict';

	angular
		.module('app.layout')
		.directive('uploadArquivo', uploadArquivo);

	function uploadArquivo() {
		var directive = {
			restrict: 'E',
			templateUrl: 'src/app/layout/upload-arquivo.html'
		};

		return directive;
	}

})();