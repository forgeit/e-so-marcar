(function () {
	'use strict';

	angular
		.module('usuario.home')
		.controller('Home', Home);

	Home.$inject = ['homeRest'];

	function Home(homeRest) {

		var vm = this;
		vm.cartoes = [];

		homeRest.buscar().then(success).catch(error);

		function error(response) {
			console.log(response);
		}

		function success(response) {
			vm.cartoes = response.data;
		}

	}
})();