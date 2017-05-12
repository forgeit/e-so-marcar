(function () {

    'use strict';

    angular
            .module('app')
            .config(routes);

    routes.$inject = ['$routeProvider', '$locationProvider'];

    function routes($routeProvider, $locationProvider) {
        $routeProvider
                .when('/privado/quadra', {
                    templateUrl: 'src/app/quadra/quadra-lista.html?' + new Date().getTime(),
                    controller: 'QuadraLista',
                    controllerAs: 'vm',
                    titulo: 'Quadras',
                    cabecalho: {
                        h1: 'Quadras',
                        breadcrumbs: [
                            {
                                nome: 'Quadras',
                                link: 'quadra',
                                ativo: true
                            }
                        ]
                    }
                })
                .when('/privado/nova-quadra', {
                    templateUrl: 'src/app/quadra/quadra-form.html?' + new Date().getTime(),
                    controller: 'QuadraForm',
                    controllerAs: 'vm',
                    titulo: 'Cadastro de Quadra',
                    cabecalho: {
                        h1: 'Cadastro de Quadra',
                        breadcrumbs: [
                            {
                                nome: 'Quadras',
                                link: 'quadra'
                            },
                            {
                                nome: 'Cadastro',
                                link: 'nova-quadra',
                                ativo: true
                            }
                        ]
                    }
                })
                .when('/privado/nova-quadra/:id', {
                    templateUrl: 'src/app/quadra/quadra-form.html?' + new Date().getTime(),
                    controller: 'QuadraForm',
                    controllerAs: 'vm',
                    titulo: 'Cadastro de Quadra',
                    cabecalho: {
                        h1: 'Cadastro de Quadra',
                        breadcrumbs: [
                            {
                                nome: 'Quadras',
                                link: 'quadra'
                            },
                            {
                                nome: 'Cadastro',
                                link: 'nova-quadra',
                                ativo: true
                            }
                        ]
                    }
                });
    }

})();