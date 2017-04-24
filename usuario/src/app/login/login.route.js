(function () {

    'use strict';

    angular
            .module('usuario')
            .config(routes);

    routes.$inject = ['$routeProvider', '$locationProvider'];

    function routes($routeProvider, $locationProvider) {
        $routeProvider
                .when('/usuario/login', {
                    templateUrl: 'usuario/src/app/login/login.html?' + new Date().getTime(),
                    controller: 'Login',
                    controllerAs: 'vm',
                    notSecured: true
                });
    }

})();