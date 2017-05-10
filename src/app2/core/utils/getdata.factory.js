(function () {
	'use strict';

	angular
		.module('core.utils')
		.factory('getData', getData);

	function getData() {
		var service = {
			get: get
		};

		return service;

		function get(data, value) {

			if (value === undefined) {
				return data.data.data;
			}

			return data.data.data[value];
		}
	}
})();