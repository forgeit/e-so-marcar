(function () {

    'use strict';

    angular
            .module('app')
            .run(appRun)
            .config(routes)
            .config(loading);

    appRun.$inject = ['$rootScope', '$location', '$route', 'AuthToken'];
    loading.$inject = ['cfpLoadingBarProvider'];
    routes.$inject = ['$routeProvider', '$locationProvider'];

    function appRun($rootScope, $location, $route, AuthToken) {
        setRouteEvents();

        function routeChangeError() {
            // console.log('Route Change Error');
        }

        function routeChangeStart(event, next, current) {
            if (!next.notSecured) {
                if (!AuthToken.ler()) {
                    $rootScope.$evalAsync(function () {
                        $location.path('/login');
                    });
                }
            }
        }

        function routeChangeSuccess(event, current) {
            $rootScope.cabecalho = current.cabecalho;
            $rootScope.titulo = current.titulo;
        }

        function setRouteEvents() {
            $rootScope.$on('$routeChangeError', routeChangeError);
            $rootScope.$on('$routeChangeStart', routeChangeStart);
            $rootScope.$on('$routeChangeSuccess', routeChangeSuccess);
        }
    }

    function loading(cfpLoadingBarProvider) {
        cfpLoadingBarProvider.parentSelector = '#loading-bar-container';
        cfpLoadingBarProvider.spinnerTemplate = '<div id="loader-wrapper"><h4><img style="width: 100px;" src="src/app/layout/img/core/logo.png" /><br/><img src="src/app/layout/img/core/loader.gif"/></h4></div>';
    }


    function routes($routeProvider, $locationProvider) {
        $routeProvider
                .when('/', {
                    templateUrl: 'src/app/home/home.html?' + new Date().getTime(),
                    controller: 'Home',
                    controllerAs: 'vm',
                    titulo: 'Página Inicial',
                    cabecalho: {
                        h1: 'Página Inicial',
                        breadcrumbs: [
                            {
                                nome: 'Página Inicial',
                                link: '/',
                                ativo: true
                            }
                        ]
                    }
                })
                .otherwise({
                    redirectTo: '/'
                });

        $locationProvider.html5Mode(true);
    }

})();