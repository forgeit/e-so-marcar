(function () {
    'use strict';

    angular
            .module('app.init')
            .controller('InitController', InitController);

    InitController.$inject = ['controllerUtils', 'initRest', 'AuthTokenApp2', 'jwtHelper', '$rootScope'];

    function InitController(controllerUtils, dataService, AuthTokenApp2, jwtHelper, $rootScope) {

        var vm = this;

        vm.areaCliente = areaCliente;
        vm.newsletter = newsletter;
        vm.cadastrar = cadastrar;
        vm.logar = logar;
        vm.senha = senha;

        function areaCliente() {
            controllerUtils.$window.location.href = 'index-cliente.html';
        }

        function newsletter() {

            dataService.newsletter(vm.newsletterForm).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao registrar o e-mail.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);
                if (response.data.status === 'true') {
                    vm.newsletterForm.email = null;
                }
            }
        }

        function cadastrar() {

            dataService.cadastrar(vm.cadastro).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao cadastrar.');
            }

            function success(response) {
                if (response.data.status === 'true') {
                    $('#myModalTwo').modal('hide');
                    setTimeout(function () {
                        $('#modalMessage').modal('show');
                    }, 500);
                } else {
                    controllerUtils.feedMessage(response);
                }
            }
        }

        function logar() {

            dataService.logar(vm.login).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao acessar o sistema.');
            }

            function success(response) {
                if (response.data.status === 'true') {
                    AuthTokenApp2.setar(response.data.data.token);

                    var payload = jwtHelper.decodeToken(response.data.data.token);
                    $rootScope.usuarioSistema = {};
                    $rootScope.usuarioSistema.id = payload.id;
                    $rootScope.usuarioSistema.nome = payload.nome;
                    $rootScope.usuarioSistema.email = payload.email;
                    $rootScope.usuarioSistema.nomeExibir = ((payload.nome) ? payload.nome : payload.email);

                    vm.login = {};

                    $('#myModal').modal('hide');
                }

                $('#loading-bar-container').html('<div id="loader-wrapper"><h4><img style="width: 100px;" src="src/app/layout/img/core/logo.png" /><br/><img src="src/app/layout/img/core/loader.gif"/></h4></div>');
                setTimeout(function () {
                    $('#loading-bar-container').html('');
                }, 500);
                setTimeout(function () {
                    controllerUtils.feedMessage(response);
                }, 600);
            }
        }

        function senha(email) {

            dataService.senha(email).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Erro ao recuperar senha.');
            }

            function success(response) {
                if (response.data.status === 'true') {
                    $('#myModal').modal('hide');
                    $('#myModalTwo').modal('hide');
                }
                controllerUtils.feedMessage(response);
            }
        }

    }
})();