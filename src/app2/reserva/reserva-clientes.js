(function () {

    'use strict';
    angular.module('app.reserva')
            .controller('ReservaClientes', ReservaClientes);
    ReservaClientes.$inject = [
        'controllerUtils',
        'reservaRest',
        'configuracaoREST'];
    function ReservaClientes(controllerUtils, dataservice, configuracaoREST) {
        /* jshint validthis: true */
        var vm = this;

        vm.reserva = {};
        vm.voltar = voltar;
        vm.preview;

        buscarClientes();

        function buscarClientes() {
            dataservice.buscarClientes().then(success).catch(error);

            function success(response) {
                vm.reserva = controllerUtils.getData(response, 'dto');
            }

            function error() {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar os reserva.');
            }

        }

        function voltar() {
            controllerUtils.$location.path('/');
        }
    }

})();