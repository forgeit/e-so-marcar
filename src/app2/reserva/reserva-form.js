(function () {

    'use strict';
    angular.module('app.reserva')
            .controller('ReservaForm', ReservaForm);
    ReservaForm.$inject = [
        'controllerUtils',
        'reservaRest',
        'configuracaoREST'];
    function ReservaForm(controllerUtils, dataservice, configuracaoREST) {
        /* jshint validthis: true */
        var vm = this;

        vm.reserva = {};
        vm.salvar = salvar;
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

        function salvar() {

            dataservice.salvar(vm.reserva).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao salvar os reserva.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);
                if (response.data.status === 'true') {
                    voltar();
                }
            }
        }

        function voltar() {
            controllerUtils.$location.path('/publico');
        }
    }

})();