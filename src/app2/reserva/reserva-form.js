(function () {

    'use strict';
    angular.module('app.reserva')
            .controller('ReservaForm', ReservaForm);
    ReservaForm.$inject = [
        'controllerUtils',
        'reservaRest',
        'configuracaoREST',
        '$window'];
    function ReservaForm(controllerUtils, dataservice, configuracaoREST, $window) {
        /* jshint validthis: true */
        var vm = this;

        vm.reserva = {};
        vm.cliente;
        vm.quadra;
        vm.confirmar = confirmar;
        vm.voltar = voltar;
        vm.preview;

        buscarQuadra();
        buscarCliente();
        buscarReservas();
        buscarBanner();

        function buscarQuadra() {
            dataservice.buscarQuadra(controllerUtils.$routeParams.id).then(success).catch(error);

            function success(response) {
                vm.quadra = controllerUtils.getData(response, 'dto');
                vm.reserva.id_quadra = vm.quadra.id;
            }

            function error() {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar os reserva.');
            }

        }

        function buscarCliente() {
            dataservice.buscarCliente(controllerUtils.$routeParams.idCliente).then(success).catch(error);

            function success(response) {
                vm.cliente = controllerUtils.getData(response, 'dto');
                vm.reserva.id_cliente = vm.cliente.id;
            }

            function error() {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar os reserva.');
            }

        }

        function buscarReservas() {
            dataservice.buscarReservas(controllerUtils.$routeParams.id).then(success).catch(error);

            function success(response) {
                $('#calendar').fullCalendar('addEventSource', controllerUtils.getData(response, 'ArrayList'));
            }

            function error() {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar os reserva.');
            }
        }

        function buscarBanner() {
            return dataservice.buscarBanner(controllerUtils.$routeParams.idCliente).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar banner.');
            }

            function success(response) {
                vm.banner = controllerUtils.getData(response, 'dto');
            }
        }

        function confirmar(aData) {
            $.confirm({
                text: "Você tem certeza que deseja reservar este horário?",
                title: "Confirmação",
                confirm: function (button) {
                    salvar(aData);
                },
                confirmButtonClass: "btn-success btn-flat",
                cancelButtonClass: "btn-default btn-flat",
                confirmButton: "Sim, tenho certeza!",
                cancelButton: "Não",
                dialogClass: "modal-dialog modal-lg jconfirm-kickoff"
            });
        }

        function salvar() {

            dataservice.salvar(vm.reserva).then(success).catch(error);

            function error(response) {
                if (response.status === 401) {
                    controllerUtils.feed(controllerUtils.messageType.WARNING, 'Você deve estar logado no sistema para realizar uma reserva.');
                } else {
                    controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao salvar os reserva.');
                }
            }

            function success(response) {
                controllerUtils.feedMessage(response);
                $('#calendar').fullCalendar('removeEvents');
                buscarReservas();
            }
        }

        function voltar() {
            controllerUtils.$location.path('/publico');
        }
    }

})();