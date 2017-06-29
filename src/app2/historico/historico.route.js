(function () {

    'use strict';

    angular
            .module('app')
            .config(routes);

    routes.$inject = ['$routeProvider', '$locationProvider'];

    function routes($routeProvider, $locationProvider) {
        $routeProvider
                .when('/publico/historico', {
                    templateUrl: 'src/app2/historico/historico-form.html',
                    controller: 'HistoricoForm',
                    controllerAs: 'vm',
                    titulo: 'Histórico de Reservas',
                    cabecalho: {
                        h1: 'Histórico de Reservas',
                        breadcrumbs: [
                            {
                                nome: 'Histórico de Reservas',
                                link: 'historico',
                                ativo: false
                            }
                        ]
                    }
                });
    }

})();