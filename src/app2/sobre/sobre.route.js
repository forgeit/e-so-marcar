(function () {

    'use strict';

    angular
            .module('app')
            .config(routes);

    routes.$inject = ['$routeProvider', '$locationProvider'];

    function routes($routeProvider, $locationProvider) {
        $routeProvider
                .when('/publico/sobre', {
                    templateUrl: 'src/app2/sobre/sobre.html',
                    controller: 'Sobre',
                    controllerAs: 'vm',
                    notSecured: true,
                    titulo: 'Sobre do Cliente',
                    cabecalho: {
                        h1: 'Sobre do Cliente',
                        breadcrumbs: [
                            {
                                nome: 'Sobre do Cliente',
                                link: 'sobre',
                                ativo: false
                            }
                        ]
                    }
                });
    }

})();