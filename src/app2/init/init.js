(function () {
    'use strict';

    angular
            .module('app.init')
            .controller('InitController', InitController);

    InitController.$inject = ['controllerUtils', 'initRest'];

    function InitController(controllerUtils, dataService) {

        var vm = this;
        vm.message = {
            title: 'REGISTRO EFETUADO'
        };
        vm.areaCliente = areaCliente;
        vm.newsletter = newsletter;
        vm.cadastrar = cadastrar;

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
                    $('#modalMessage').modal('show');
                } else {
                    controllerUtils.feedMessage(response);
                }
            }
        }

    }
})();