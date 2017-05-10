(function () {

	angular
		.module('core.utils')
		.factory('controllerUtils', controllerUtils);

	controllerUtils.$inject = [
		'getData',
		'messageUtils',
		'$routeParams',
		'multiPromise',
		'$location',
		'$q',
                '$window',
		'promise'];

	function controllerUtils(getData, messageUtils, $routeParams, multiPromise, $location, $q, $window, promise) {
		var service = {
			getData: getData.get,
			feedMessage: messageUtils.feedMessage,
			feed: messageUtils.feed,
			messageType: messageUtils.type,
			feed: messageUtils.feed,
			$routeParams: $routeParams,
			ready: multiPromise.ready,
			$q: $q,
			promise: promise,
			$location: $location,
			$window: $window
		};	

		return service;
	}
})();