(function () {

    'use strict';

    angular
            .module('cliente')
            .config(routes);

    routes.$inject = ['$routeProvider', '$locationProvider'];

    function routes($routeProvider, $locationProvider) {
        $routeProvider
                .when('/cliente/login', {
                    templateUrl: 'cliente/src/app/login/login.html?' + new Date().getTime(),
                    controller: 'Login',
                    controllerAs: 'vm',
                    notSecured: true
                });
    }

})();