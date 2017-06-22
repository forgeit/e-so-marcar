(function () {

    'use strict';

    angular
            .module('app')
            .config(routes);

    routes.$inject = ['$routeProvider', '$locationProvider'];

    function routes($routeProvider, $locationProvider) {
        $routeProvider
                .when('/privado/anuncio', {
                    templateUrl: 'src/app/anuncio/anuncio-lista.html?' + new Date().getTime(),
                    controller: 'AnuncioLista',
                    controllerAs: 'vm',
                    titulo: 'Anúncios',
                    cabecalho: {
                        h1: 'Anúncios',
                        breadcrumbs: [
                            {
                                nome: 'Anúncios',
                                link: '/privado/anuncio',
                                ativo: true
                            }
                        ]
                    }
                })
                .when('/privado/novo-anuncio', {
                    templateUrl: 'src/app/anuncio/anuncio-form.html?' + new Date().getTime(),
                    controller: 'AnuncioForm',
                    controllerAs: 'vm',
                    titulo: 'Cadastro de Anúncio',
                    cabecalho: {
                        h1: 'Cadastro de Anúncio',
                        breadcrumbs: [
                            {
                                nome: 'Anúncios',
                                link: '/privado/anuncio'
                            },
                            {
                                nome: 'Cadastro',
                                link: '/privado/novo-anuncio',
                                ativo: true
                            }
                        ]
                    }
                })
                .when('/privado/novo-anuncio/:id', {
                    templateUrl: 'src/app/anuncio/anuncio-form.html?' + new Date().getTime(),
                    controller: 'AnuncioForm',
                    controllerAs: 'vm',
                    titulo: 'Cadastro de Anúncio',
                    cabecalho: {
                        h1: 'Cadastro de Anúncio',
                        breadcrumbs: [
                            {
                                nome: 'Anúncios',
                                link: '/privado/anuncio'
                            },
                            {
                                nome: 'Cadastro',
                                link: '/privado/novo-anuncio',
                                ativo: true
                            }
                        ]
                    }
                });
    }

})();