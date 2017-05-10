(function () {

    'use strict';

    angular
            .module('app.login')
            .controller('Login', Login);

    Login.$inject = ['loginRest', 'controllerUtils', 'AuthToken', '$rootScope', 'jwtHelper'];

    function Login(loginRest, controllerUtils, AuthToken, $rootScope, jwtHelper) {
        var vm = this;

        vm.logar = logar;
        vm.areaUsuario = areaUsuario;
        vm.usuario = {};

        function logar(formulario) {
            $rootScope.usuarioLogado = {};
            loginRest.logar(vm.usuario).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao entrar no sistema.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);
                if (response.data.status == 'true') {
                    AuthToken.setar(response.data.data.token);

                    var payload = jwtHelper.decodeToken(response.data.data.token);
                    $rootScope.usuarioLogado.nome = payload.nome;
                    $rootScope.usuarioLogado.cargo = payload.cargo;
                    $rootScope.usuarioLogado.imagem = payload.imagem;

                    controllerUtils.$location.path('/');
                }
            }
        }

        function areaUsuario() {
            controllerUtils.$window.location.href = 'index.html';
        }
    }

})();