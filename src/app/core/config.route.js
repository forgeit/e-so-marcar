(function () {

    'use strict';

    angular
            .module('app')
            .run(appRun)
            .config(routes)
            .config(loading)
            .config(recaptcha);

    appRun.$inject = ['$rootScope', '$location', '$route', 'AuthToken'];
    loading.$inject = ['cfpLoadingBarProvider'];
    routes.$inject = ['$routeProvider', '$locationProvider'];
    recaptcha.$inject = ['vcRecaptchaServiceProvider'];

    function appRun($rootScope, $location, $route, AuthToken) {
        setRouteEvents();

        function routeChangeError() {
            // console.log('Route Change Error');
        }

        function routeChangeStart(event, next, current) {
            if (!next.notSecured) {
                if (!AuthToken.ler()) {
                    $rootScope.$evalAsync(function () {
                        $location.path('/privado/login');
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
        cfpLoadingBarProvider.spinnerTemplate = '<div id="loader-wrapper"><h4><img style="width: 150px;" src="src/app2/layout/img/logo-lg.png" /><br/><img src="src/app2/layout/img/loader.gif"/></h4></div>';
    }

    function recaptcha(vcRecaptchaServiceProvider) {
        vcRecaptchaServiceProvider.setDefaults({
            key: '6LfuQycUAAAAADiRK_lhH80niSyIwdoOtdTXYTDU',
            theme: 'light',
            stoken: '',
            size: 'normal',
            type: 'image',
            lang: 'pt-BR'
        });
    }

    function routes($routeProvider, $locationProvider) {
        $routeProvider
                .when('/privado/cliente', {
                    templateUrl: 'src/app/home/home.html?' + new Date().getTime(),
                    controller: 'Home',
                    controllerAs: 'vm',
                    titulo: 'Página Inicial',
                    cabecalho: {
                        h1: 'Página Inicial',
                        breadcrumbs: [
                            {
                                nome: 'Página Inicial',
                                link: '/cliente',
                                ativo: true
                            }
                        ]
                    }
                })
                .otherwise({
                    redirectTo: '/privado/cliente'
                });

        $locationProvider.html5Mode(true);
    }

})();