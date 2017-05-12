(function () {

    'use strict';

    angular
            .module('app')
            .config(routes);

    routes.$inject = ['$routeProvider', '$locationProvider'];

    function routes($routeProvider, $locationProvider) {
        $routeProvider
                .when('/publico/galeria', {
                    templateUrl: 'src/app2/galeria/galeria.html',
                    controller: 'Galeria',
                    controllerAs: 'vm',
                    titulo: 'Galeria do Cliente',
                    cabecalho: {
                        h1: 'Galeria do Cliente',
                        breadcrumbs: [
                            {
                                nome: 'Galeria do Cliente',
                                link: 'galeria',
                                ativo: false
                            }
                        ]
                    }
                });
    }

})();