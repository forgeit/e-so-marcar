(function () {

    'use strict';

    angular
            .module('app')
            .config(routes);

    routes.$inject = ['$routeProvider', '$locationProvider'];

    function routes($routeProvider, $locationProvider) {
        $routeProvider
                .when('/publico/reserva', {
                    templateUrl: 'src/app2/reserva/reserva-form.html',
                    controller: 'ReservaForm',
                    controllerAs: 'vm',
                    titulo: 'Reserva do Cliente',
                    cabecalho: {
                        h1: 'Reserva do Cliente',
                        breadcrumbs: [
                            {
                                nome: 'Reserva do Cliente',
                                link: 'reserva',
                                ativo: false
                            }
                        ]
                    }
                });
    }

})();