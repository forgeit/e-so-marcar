(function () {

    'use strict';

    angular
            .module('app')
            .config(routes);

    routes.$inject = ['$routeProvider', '$locationProvider'];

    function routes($routeProvider, $locationProvider) {
        $routeProvider
                .when('/privado/dados', {
                    templateUrl: 'src/app/dados/dados-form.html',
                    controller: 'DadosForm',
                    controllerAs: 'vm',
                    titulo: 'Dados do Cliente',
                    cabecalho: {
                        h1: 'Dados do Cliente',
                        breadcrumbs: [
                            {
                                nome: 'Dados do Cliente',
                                link: '/privado/dados',
                                ativo: false
                            }
                        ]
                    }
                });
    }

})();