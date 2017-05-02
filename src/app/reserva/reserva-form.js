(function () {

    'use strict';

    angular.module('app.reserva')
            .controller('ReservaForm', ReservaForm);

    ReservaForm.$inject = [
        'controllerUtils',
        'reservaRest',
        '$scope',
        'configuracaoREST'];

    function ReservaForm(controllerUtils, dataservice, $scope, configuracaoREST) {
        /* jshint validthis: true */
        var vm = this;

        vm.atualizar = atualizar;
        vm.reserva = {valor: 0, ativo: true};
        vm.preview = 0;
        vm.editar = false;
        vm.filtrar = null;
        vm.quadraList = [];
        vm.salvar = salvar;
        vm.voltar = voltar;

        iniciar();

        function atualizar(formulario) {
            vm.reserva.id_tipo_reserva = vm.tipoReserva.id;

            dataservice.atualizar(vm.reserva.id, vm.reserva).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao atualizar a reserva.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);

                if (response.data.status === 'true') {
                    voltar();
                }
            }
        }

        function carregarReserva(data) {
            return dataservice.buscar(data).then(success).catch(error);

            function error(response) {
                return controllerUtils.promise.criar(false, {});
            }

            function success(response) {
                vm.reserva = controllerUtils.getData(response, 'dto');

                if (vm.reserva.id_tipo_reserva) {
                    $scope.$watch('vm.quadraListList', function () {
                        if (vm.quadraListList.length > 0) {
                            angular.forEach(vm.tipoReservaList, function (value, index) {
                                if (value.id === vm.reserva.id_quadra) {
                                    vm.quadra = value;
                                }
                            });
                        }
                    });
                }



                return controllerUtils.promise.criar(true, response);
            }
        }

        function carregarQuadraList() {
            return dataservice.buscarComboQuadra().then(success).catch(error);

            function error(response) {
                return controllerUtils.promise.criar(false, []);
            }

            function success(response) {
                var array = controllerUtils.getData(response, 'ArrayList');
                return controllerUtils.promise.criar(true, array);
            }
        }

        function carregarUsuarioList() {
            return dataservice.buscarComboUsuario().then(success).catch(error);

            function error(response) {
                return controllerUtils.promise.criar(false, []);
            }

            function success(response) {
                var array = controllerUtils.getData(response, 'ArrayList');
                return controllerUtils.promise.criar(true, array);
            }
        }

        function editarObjeto() {
            vm.editar = !angular.equals({}, controllerUtils.$routeParams);
            return !angular.equals({}, controllerUtils.$routeParams);
        }

        function inicializarObjetos(values) {
            if (values[0].exec) {
                vm.quadraList = values[0].objeto;
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar as quadras.');
            }

            if (values[1].exec) {
                vm.usuarioList = values[1].objeto;
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar os usuários.');
            }
        }

        function iniciar() {
            $('#data_hora').datepicker({autoclose: true, format: 'dd/mm/yyyy', language: 'pt-BR'});
            $("#timepicker").timepicker({
                showMeridian: false,
                showInputs: false
            });

            var promises = [];

            promises.push(carregarQuadraList());
            promises.push(carregarUsuarioList());

            if (editarObjeto()) {
                promises.push(carregarReserva(controllerUtils.$routeParams.id));
            }

            return controllerUtils.ready(promises).then(function (values) {
                inicializarObjetos(values);
            });
        }

        function salvar(formulario) {
            vm.reserva.id_tipo_reserva = vm.tipoReserva.id;
            if (vm.uploader.queue.length === 0) {
                vm.reserva.imagem = '';
            }

            if (formulario.$valid) {
                dataservice.salvar(vm.reserva).then(success).catch(error);
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Dados inválidos.');
            }

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao registrar a reserva.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);

                if (response.data.status === 'true') {
                    voltar();
                }
            }
        }

        function voltar() {
            controllerUtils.$location.path('reserva');
        }
    }

})();