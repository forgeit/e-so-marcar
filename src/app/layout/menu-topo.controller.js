(function () {
    'use strict';

    angular
            .module('app.layout')
            .controller('MenuTopoController', MenuTopoController);

    MenuTopoController.$inject = ['AuthToken', 'controllerUtils', 'jwtHelper', '$rootScope'];

    function MenuTopoController(AuthToken, controllerUtils, jwtHelper, $rootScope) {
        var vm = this;
        vm.isLogged = isLogged;
        vm.sair = sair;

        carregar();

        function carregar() {
            if (AuthToken.ler()) {
                var payload = jwtHelper.decodeToken(AuthToken.ler());
                $rootScope.usuarioLogado = {};
                $rootScope.usuarioLogado.nome = payload.nome;
                $rootScope.usuarioLogado.cargo = payload.cargo;
                $rootScope.usuarioLogado.imagem = payload.imagem;
            }
        }

        function isLogged() {
            return !!AuthToken.ler();
        }

        function sair() {
            AuthToken.remover();
            controllerUtils.$location.path('/privado/login');
        }
    }

})();