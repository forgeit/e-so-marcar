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

        vm.cliente;
        vm.quadras;
        vm.voltar = voltar;
        vm.preview;

        buscarCliente();
        buscarQuadras();
        buscarBanner();

        function buscarCliente() {
            dataservice.buscarCliente(controllerUtils.$routeParams.id).then(success).catch(error);

            function success(response) {
                vm.cliente = controllerUtils.getData(response, 'dto');
            }

            function error() {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar os reserva.');
            }
        }

        function buscarQuadras() {
            dataservice.buscarQuadras(controllerUtils.$routeParams.id).then(success).catch(error);

            function success(response) {
                vm.quadras = controllerUtils.getData(response, 'ArrayList');
            }

            function error() {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar os reserva.');
            }
        }
        
        function buscarBanner() {
            return dataservice.buscarBanner(controllerUtils.$routeParams.id).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar banner.');
            }

            function success(response) {
                vm.banner = controllerUtils.getData(response, 'dto');
            }
        }

        function voltar() {
            controllerUtils.$location.path('/publico/cliente');
        }
    }

})();