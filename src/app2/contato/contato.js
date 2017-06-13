(function () {
    'use strict';

    angular
            .module('app.contato')
            .controller('Contato', Contato);

    Contato.$inject = ['controllerUtils', 'contatoRest'];

    function Contato(controllerUtils, dataservice) {

        var vm = this;

        vm.enviar = enviar;

        function enviar() {

            dataservice.enviar(vm.contato).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao enviar mensagem.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);
                if (response.data.status === 'true') {
                    vm.contato = {};
                }
            }
        }

    }
})();