(function () {

    'use strict';
    angular.module('app.reserva')
            .controller('ReservaQuadras', ReservaQuadras);
    ReservaQuadras.$inject = [
        'controllerUtils',
        'reservaRest',
        'configuracaoREST'];
    function ReservaQuadras(controllerUtils, dataservice, configuracaoREST) {
        /* jshint validthis: true */
        var vm = this;

        vm.reserva = {};
        vm.voltar = voltar;
        vm.preview;

        buscar();

        function buscar() {
            dataservice.buscar().then(success).catch(error);

            function success(response) {
                vm.reserva = controllerUtils.getData(response, 'dto');
                vm.preview = vm.reserva.logo;
            }

            function error() {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar os reserva.');
            }

        }

        function voltar() {
            controllerUtils.$location.path('/publico/cliente');
        }
    }

})();