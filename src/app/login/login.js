(function () {

    'use strict';

    angular
            .module('app.login')
            .controller('Login', Login);

    Login.$inject = ['loginRest', 'controllerUtils', 'AuthToken', '$rootScope', 'jwtHelper', 'vcRecaptchaService'];

    function Login(loginRest, controllerUtils, AuthToken, $rootScope, jwtHelper, vcRecaptchaService) {
        var vm = this;

        vm.logar = logar;
        vm.areaUsuario = areaUsuario;
        vm.usuario = {};

        function logar(formulario) {

            if (vm.response) {
                vm.usuario.hash = vm.response;
                $rootScope.usuarioLogado = {};
                loginRest.logar(vm.usuario).then(success).catch(error);
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'O reCaptcha não está resolvido.');
            }

            function error(response) {
                vcRecaptchaService.reload(vm.widgetId);
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao entrar no sistema.');
            }

            function success(response) {
                vcRecaptchaService.reload(vm.widgetId);
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

        vm.response = null;
        vm.widgetId = null;
        vm.model = {
            key: '6LfuQycUAAAAADiRK_lhH80niSyIwdoOtdTXYTDU'
        };
        vm.setResponse = function (response) {
            vm.response = response;
        };
        vm.setWidgetId = function (widgetId) {
            vm.widgetId = widgetId;
        };
        vm.cbExpiration = function () {
            vcRecaptchaService.reload(vm.widgetId);
            vm.response = null;
        };
        
    }

})();