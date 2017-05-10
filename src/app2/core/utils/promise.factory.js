(function () {
	'use strict';

	angular
		.module('core.utils')
		.factory('promise', promise);

	function promise() {
		var service = {
			criar: criar
		};

		return service;

		function criar(exec, objeto) {
			var retorno = {};
			retorno.exec = exec;
			retorno.objeto = objeto;
			return retorno;
		}
	}

})();