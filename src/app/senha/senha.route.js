(function () {

    'use strict';

    angular
            .module('app')
            .config(routes);

    routes.$inject = ['$routeProvider', '$locationProvider'];

    function routes($routeProvider, $locationProvider) {
        $routeProvider
                .when('/privado/senha', {
                    templateUrl: 'src/app/senha/senha-form.html',
                    controller: 'SenhaForm',
                    controllerAs: 'vm',
                    titulo: 'Alterar Senha',
                    cabecalho: {
                        h1: 'Senha do Cliente',
                        breadcrumbs: [
                            {
                                nome: 'Senha do Cliente',
                                link: 'senha',
                                ativo: false
                            }
                        ]
                    }
                });
    }

})();