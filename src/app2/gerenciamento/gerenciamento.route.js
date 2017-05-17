(function () {

    'use strict';

    angular
            .module('app')
            .config(routes);

    routes.$inject = ['$routeProvider', '$locationProvider'];

    function routes($routeProvider, $locationProvider) {
        $routeProvider
                .when('/publico/gerenciamento', {
                    templateUrl: 'src/app2/gerenciamento/gerenciamento-form.html',
                    controller: 'GerenciamentoForm',
                    controllerAs: 'vm',
                    titulo: 'Gerenciamento do Usuário',
                    cabecalho: {
                        h1: 'Gerenciamento do Usuário',
                        breadcrumbs: [
                            {
                                nome: 'Gerenciamento do Usuário',
                                link: 'gerenciamento',
                                ativo: false
                            }
                        ]
                    }
                });
    }

})();