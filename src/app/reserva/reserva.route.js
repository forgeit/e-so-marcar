(function () {

    'use strict';

    angular
            .module('app')
            .config(routes);

    routes.$inject = ['$routeProvider', '$locationProvider'];

    function routes($routeProvider, $locationProvider) {
        $routeProvider
                .when('/privado/reserva', {
                    templateUrl: 'src/app/reserva/reserva-lista.html?' + new Date().getTime(),
                    controller: 'ReservaLista',
                    controllerAs: 'vm',
                    titulo: 'Reservas',
                    cabecalho: {
                        h1: 'Reservas',
                        breadcrumbs: [
                            {
                                nome: 'Reservas',
                                link: 'reserva',
                                ativo: true
                            }
                        ]
                    }
                })
                .when('/privado/nova-reserva', {
                    templateUrl: 'src/app/reserva/reserva-form.html?' + new Date().getTime(),
                    controller: 'ReservaForm',
                    controllerAs: 'vm',
                    titulo: 'Cadastro de Reserva',
                    cabecalho: {
                        h1: 'Cadastro de Reserva',
                        breadcrumbs: [
                            {
                                nome: 'Reservas',
                                link: 'reserva'
                            },
                            {
                                nome: 'Cadastro',
                                link: 'nova-reserva',
                                ativo: true
                            }
                        ]
                    }
                })
                .when('/privado/nova-reserva/:id', {
                    templateUrl: 'src/app/reserva/reserva-form.html?' + new Date().getTime(),
                    controller: 'ReservaForm',
                    controllerAs: 'vm',
                    titulo: 'Cadastro de Reserva',
                    cabecalho: {
                        h1: 'Cadastro de Reserva',
                        breadcrumbs: [
                            {
                                nome: 'Reservas',
                                link: 'reserva'
                            },
                            {
                                nome: 'Cadastro',
                                link: 'nova-reserva',
                                ativo: true
                            }
                        ]
                    }
                });
    }

})();