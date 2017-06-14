(function () {
    'use strict';

    angular
            .module('app.layout')
            .controller('MenuTopoController', MenuTopoController);

    MenuTopoController.$inject = ['AuthTokenApp2', 'controllerUtils', 'jwtHelper', '$rootScope'];

    function MenuTopoController(AuthTokenApp2, controllerUtils, jwtHelper, $rootScope) {
        var vm = this;
        vm.isLogged = isLogged;
        vm.sair = sair;

        carregar();

        function carregar() {

            if (AuthTokenApp2.ler()) {
                var payload = jwtHelper.decodeToken(AuthTokenApp2.ler());
                $rootScope.usuarioSistema = {};
                $rootScope.usuarioSistema.id = payload.id;
                $rootScope.usuarioSistema.nome = payload.nome;
                $rootScope.usuarioSistema.email = payload.email;
                $rootScope.usuarioSistema.nomeExibir = ((payload.nome) ? payload.nome : payload.email);
            }
        }

        function isLogged() {
            return !!AuthTokenApp2.ler();
        }

        function sair() {
            AuthTokenApp2.remover();
            $rootScope.usuarioSistema = null;
            $('#loading-bar-container').html('<div id="loader-wrapper"><h4><img style="width: 100px;" src="src/app/layout/img/core/logo.png" /><br/><img src="src/app/layout/img/core/loader.gif"/></h4></div>');
            setTimeout(function () {
                $('#loading-bar-container').html('');
            }, 500);
            controllerUtils.$location.path('/');
        }
    }

})();