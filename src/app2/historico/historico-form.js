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
        vm.confirmarMensal = confirmarMensal;
        vm.voltar = voltar;

        buscar();
        buscarMensal();

        function buscar() {
            dataservice.buscar().then(success).catch(error);

            function success(response) {
                vm.reservas = controllerUtils.getData(response, 'ArrayList');
                if (vm.reservas == null) {
                    vm.mensagemVazio = 'Nenhuma reserva em aberto.';
                }
            }

            function error() {
                vm.reservas = null;
                vm.mensagemVazio = 'Erro ao carregar lista de reservas.';
            }

        }

        function buscarMensal() {
            dataservice.buscarMensal().then(success).catch(error);

            function success(response) {
                vm.reservasMensal = controllerUtils.getData(response, 'ArrayList');
                if (vm.reservasMensal == null) {
                    vm.mensagemVazioMensal = 'Nenhuma reserva mensal em aberto.';
                }
            }

            function error() {
                vm.reservasMensal = null;
                vm.mensagemVazioMensal = 'Erro ao carregar lista de reservas mensais.';
            }

        }

        function confirmar(aData) {
            $.confirm({
                text: "Você tem certeza que deseja cancelar esta reserva?<br/>",
                title: "Confirmação",
                confirm: function (button) {
                    cancelar(aData);
                },
                confirmButtonClass: "btn-danger btn-flat",
                cancelButtonClass: "btn-default btn-flat",
                confirmButton: "Sim, tenho certeza!",
                cancelButton: "Não",
                dialogClass: "modal-dialog modal-lg jconfirm-kickoff"
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
        
        function confirmarMensal(aData) {
            $.confirm({
                text: "Você tem certeza que deseja cancelar esta reserva mensal?<br/>",
                title: "Confirmação",
                confirm: function (button) {
                    cancelarMensal(aData);
                },
                confirmButtonClass: "btn-danger btn-flat",
                cancelButtonClass: "btn-default btn-flat",
                confirmButton: "Sim, tenho certeza!",
                cancelButton: "Não",
                dialogClass: "modal-dialog modal-lg jconfirm-kickoff"
            });
        }
        
        function cancelarMensal(id) {

            dataservice.cancelarMensal(id).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao cancelar reserva mensal.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);
                if (response.data.status === 'true') {
                    buscarMensal();
                }
            }
        }

        function voltar() {
            controllerUtils.$location.path('/');
        }

    }

})();