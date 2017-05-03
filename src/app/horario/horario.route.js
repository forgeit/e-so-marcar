(function () {

    'use strict';

    angular
            .module('app')
            .config(routes);

    routes.$inject = ['$routeProvider', '$locationProvider'];

    function routes($routeProvider, $locationProvider) {
        $routeProvider
                .when('/horario', {
                    templateUrl: 'src/app/horario/horario-lista.html?' + new Date().getTime(),
                    controller: 'HorarioLista',
                    controllerAs: 'vm',
                    titulo: 'Horários',
                    cabecalho: {
                        h1: 'Horários',
                        breadcrumbs: [
                            {
                                nome: 'Horários',
                                link: 'horario',
                                ativo: true
                            }
                        ]
                    }
                })
                .when('/novo-horario', {
                    templateUrl: 'src/app/horario/horario-form.html?' + new Date().getTime(),
                    controller: 'HorarioForm',
                    controllerAs: 'vm',
                    titulo: 'Cadastro de Horários',
                    cabecalho: {
                        h1: 'Cadastro de Horários',
                        breadcrumbs: [
                            {
                                nome: 'Horários',
                                link: 'horario'
                            },
                            {
                                nome: 'Cadastro',
                                link: 'novo-horario',
                                ativo: true
                            }
                        ]
                    }
                })
                .when('/novo-horario/:id', {
                    templateUrl: 'src/app/horario/horario-form.html?' + new Date().getTime(),
                    controller: 'HorarioForm',
                    controllerAs: 'vm',
                    titulo: 'Cadastro de Horários',
                    cabecalho: {
                        h1: 'Cadastro de Horários',
                        breadcrumbs: [
                            {
                                nome: 'Horários',
                                link: 'horario'
                            },
                            {
                                nome: 'Cadastro',
                                link: 'novo-horario',
                                ativo: true
                            }
                        ]
                    }
                });
    }

})();