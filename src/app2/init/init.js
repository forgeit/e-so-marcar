(function () {
    'use strict';

    angular
            .module('app.init')
            .controller('InitController', InitController);

    InitController.$inject = ['controllerUtils', 'initRest'];

    function InitController(controllerUtils, dataService) {

        var vm = this;

        vm.areaCliente = areaCliente;
        vm.newsletter = newsletter;

        function areaCliente() {
            controllerUtils.$window.location.href = 'index-cliente.html';
        }

        function newsletter() {
            
            dataService.newsletter(vm.newsletterForm).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao salvar os reserva.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);
                if (response.data.status === 'true') {
                    vm.newsletterForm.email = null;
                }
            }
        }

    }
})();