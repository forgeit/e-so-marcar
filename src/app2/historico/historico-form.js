(function () {

    'use strict';
    angular.module('app.historico')
            .controller('HistoricoForm', HistoricoForm);
    HistoricoForm.$inject = [
        'controllerUtils',
        'historicoRest',
        'FileUploader',
        'configuracaoREST',
        'AuthTokenApp2',
        '$rootScope'];
    function HistoricoForm(controllerUtils, dataservice, FileUploader, configuracaoREST, AuthTokenApp2, $rootScope) {
        /* jshint validthis: true */
        var vm = this;

        vm.mensagemVazio;
        vm.reservas = [];
        vm.confirmar = confirmar;
        vm.voltar = voltar;

        buscar();

        function buscar() {
            dataservice.buscar().then(success).catch(error);

            function success(response) {
                vm.reservas = controllerUtils.getData(response, 'ArrayList');
                if(vm.reservas == null) {
                    vm.mensagemVazio = 'Nenhuma reserva em aberto.';
                }
            }

            function error() {
                vm.reservas = null;
                vm.mensagemVazio = 'Erro ao carregar lista de reservas.';
            }

        }
        
        function confirmar(aData) {
            $.confirm({
                text: "Você tem certeza que deseja cancelar esta reserva?",
                title: "Confirmação",
                confirm: function (button) {
                    cancelar(aData);
                },
                confirmButtonClass: "btn-danger btn-flat",
                cancelButtonClass: "btn-default btn-flat",
                confirmButton: "Sim, tenho certeza!",
                cancelButton: "Não",
                dialogClass: "modal-dialog modal-lg"
            });
        }

        function cancelar(id) {

            dataservice.cancelar(id).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao cancelar reserva.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);
                if (response.data.status === 'true') {
                    buscar();
                }
            }
        }

        function voltar() {
            controllerUtils.$location.path('/');
        }

    }

})();