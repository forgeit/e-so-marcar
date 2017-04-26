(function () {

    'use strict';

    angular
            .module('app')
            .config(routes);

    routes.$inject = ['$routeProvider', '$locationProvider'];

    function routes($routeProvider, $locationProvider) {
        $routeProvider
                .when('/anuncio', {
                    templateUrl: 'src/app/anuncio/anuncio-lista.html?' + new Date().getTime(),
                    controller: 'AnuncioLista',
                    controllerAs: 'vm',
                    titulo: 'Anúncios',
                    cabecalho: {
                        h1: 'Anúncios',
                        breadcrumbs: [
                            {
                                nome: 'Anúncios',
                                link: 'anuncio',
                                ativo: true
                            }
                        ]
                    }
                })
                .when('/novo-anuncio', {
                    templateUrl: 'src/app/anuncio/anuncio-form.html?' + new Date().getTime(),
                    controller: 'AnuncioForm',
                    controllerAs: 'vm',
                    titulo: 'Cadastro de Anúncio',
                    cabecalho: {
                        h1: 'Cadastro de Anúncio',
                        breadcrumbs: [
                            {
                                nome: 'Anúncios',
                                link: 'anuncio'
                            },
                            {
                                nome: 'Cadastro',
                                link: 'novo-anuncio',
                                ativo: true
                            }
                        ]
                    }
                })
                .when('/novo-anuncio/:id', {
                    templateUrl: 'src/app/anuncio/anuncio-form.html?' + new Date().getTime(),
                    controller: 'AnuncioForm',
                    controllerAs: 'vm',
                    titulo: 'Cadastro de Anúncio',
                    cabecalho: {
                        h1: 'Cadastro de Anúncio',
                        breadcrumbs: [
                            {
                                nome: 'Anúncios',
                                link: 'anuncio'
                            },
                            {
                                nome: 'Cadastro',
                                link: 'novo-anuncio',
                                ativo: true
                            }
                        ]
                    }
                });
    }

})();