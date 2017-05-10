(function () {

    'use strict';

    angular
            .module('app')
            .config(routes);

    routes.$inject = ['$routeProvider', '$locationProvider'];

    function routes($routeProvider, $locationProvider) {
        $routeProvider
                .when('/privado/excecao', {
                    templateUrl: 'src/app/excecao/excecao-lista.html?' + new Date().getTime(),
                    controller: 'ExcecaoLista',
                    controllerAs: 'vm',
                    titulo: 'Horários de Exceção',
                    cabecalho: {
                        h1: 'Horários de Exceção',
                        breadcrumbs: [
                            {
                                nome: 'Horários de Exceção',
                                link: 'excecao',
                                ativo: true
                            }
                        ]
                    }
                })
                .when('/privado/nova-excecao', {
                    templateUrl: 'src/app/excecao/excecao-form.html?' + new Date().getTime(),
                    controller: 'ExcecaoForm',
                    controllerAs: 'vm',
                    titulo: 'Cadastro de Horários de Exceção',
                    cabecalho: {
                        h1: 'Cadastro de Horários de Exceção',
                        breadcrumbs: [
                            {
                                nome: 'Horários de Exceção',
                                link: 'excecao'
                            },
                            {
                                nome: 'Cadastro',
                                link: 'nova-excecao',
                                ativo: true
                            }
                        ]
                    }
                })
                .when('/privado/nova-excecao/:id', {
                    templateUrl: 'src/app/excecao/excecao-form.html?' + new Date().getTime(),
                    controller: 'ExcecaoForm',
                    controllerAs: 'vm',
                    titulo: 'Cadastro de Horários de Exceção',
                    cabecalho: {
                        h1: 'Cadastro de Horários de Exceção',
                        breadcrumbs: [
                            {
                                nome: 'Horários de Exceção',
                                link: 'excecao'
                            },
                            {
                                nome: 'Cadastro',
                                link: 'nova-excecao',
                                ativo: true
                            }
                        ]
                    }
                });
    }

})();