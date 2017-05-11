(function () {

    'use strict';

    angular
            .module('app')
            .config(routes);

    routes.$inject = ['$routeProvider', '$locationProvider'];

    function routes($routeProvider, $locationProvider) {
        $routeProvider
                .when('/publico/contato', {
                    templateUrl: 'src/app2/contato/contato.html',
                    controller: 'Contato',
                    controllerAs: 'vm',
                    titulo: 'Contato do Cliente',
                    cabecalho: {
                        h1: 'Contato do Cliente',
                        breadcrumbs: [
                            {
                                nome: 'Contato do Cliente',
                                link: 'contato',
                                ativo: false
                            }
                        ]
                    }
                });
    }

})();