(function () {
	'use strict';

	angular
		.module('core.utils')
		.factory('multiPromise', multiPromise);

	multiPromise.$inject = ['$q'];

	function multiPromise($q) {
		var isPrimed = false;
		var primePromise;

		var service = {
			ready: ready
		};

		return service;

		function prime() {
            if (primePromise) {
                return primePromise;
            }

            primePromise = $q.when(true).then(success);
            return primePromise;

            function success() {
                isPrimed = true;
            }
        }

		function ready(nextPromises) {
            var readyPromise = primePromise || prime();
            return readyPromise.then(success).catch(error);

            function error() {
                return $q.reject();
            }

            function success() {
                return $q.all(nextPromises); 
            }
        }
	}
})();