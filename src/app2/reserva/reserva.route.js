(function () {

    'use strict';

    angular
            .module('app')
            .config(routes);

    routes.$inject = ['$routeProvider', '$locationProvider'];

    function routes($routeProvider, $locationProvider) {
        $routeProvider
                .when('/publico/cliente', {
                    templateUrl: 'src/app2/reserva/reserva-clientes.html?' + new Date().getTime(),
                    controller: 'ReservaClientes',
                    controllerAs: 'vm',
                    notSecured: true,
                    titulo: 'Lista de Clientes',
                    cabecalho: {
                        h1: 'Lista de Clientes',
                        breadcrumbs: [
                            {
                                nome: 'Clientes',
                                link: 'publico/cliente',
                                ativo: true
                            }
                        ]
                    }
                })
                .when('/publico/quadra/cliente/:id', {
                    templateUrl: 'src/app2/reserva/reserva-quadras.html?' + new Date().getTime(),
                    controller: 'ReservaQuadras',
                    controllerAs: 'vm',
                    notSecured: true,
                    titulo: 'Lista de Quadras',
                    cabecalho: {
                        h1: 'Lista de Quadras',
                        breadcrumbs: [
                            {
                                nome: 'Clientes',
                                link: 'publico/cliente'
                            },
                            {
                                nome: 'Quadras',
                                link: 'publico/quadra/cliente/:id',
                                ativo: true
                            }
                        ]
                    }
                })
                .when('/publico/quadra/:id/cliente/:idCliente', {
                    templateUrl: 'src/app2/reserva/reserva-form.html?' + new Date().getTime(),
                    controller: 'ReservaForm',
                    controllerAs: 'vm',
                    notSecured: true,
                    titulo: 'Reserva de Quadra',
                    cabecalho: {
                        h1: 'Reserva de Quadra',
                        breadcrumbs: [
                            {
                                nome: 'Clientes',
                                link: 'publico/cliente'
                            },
                            {
                                nome: 'Quadras',
                                link: 'publico/quadra/cliente/:idCliente'
                            },
                            {
                                nome: 'Reserva',
                                link: 'publico/quadra/:id/cliente/:idCliente',
                                ativo: true
                            }
                        ]
                    }
                });
    }

})();