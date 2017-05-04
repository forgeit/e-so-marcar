(function () {

    'use strict';

    angular.module('app.reserva')
            .controller('ReservaForm', ReservaForm);

    ReservaForm.$inject = [
        'controllerUtils',
        'reservaRest',
        '$scope',
        'dateRangeLocale',
        'configuracaoREST'];

    function ReservaForm(controllerUtils, dataservice, $scope, dateRangeLocale, configuracaoREST) {
        /* jshint validthis: true */
        var vm = this;

        vm.atualizar = atualizar;
        vm.reserva = {};
        vm.preview = 0;
        vm.editar = false;
        vm.filtrar = null;
        vm.quadraList = [];
        vm.salvar = salvar;
        vm.voltar = voltar;
        vm.buscarValor = buscarValor;

        iniciar();

        function atualizar(formulario) {
            vm.reserva.id_quadra = vm.quadra.id;
            vm.reserva.id_usuario = vm.usuario.id;

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

                if (vm.reserva.id_quadra) {
                    $scope.$watch('vm.quadraList', function () {
                        if (vm.quadraList.length > 0) {
                            angular.forEach(vm.quadraList, function (value, index) {
                                if (value.id === vm.reserva.id_quadra) {
                                    vm.quadra = value;
                                }
                            });
                        }
                    });
                }

                if (vm.reserva.id_usuario) {
                    $scope.$watch('vm.usuarioList', function () {
                        if (vm.usuarioList.length > 0) {
                            angular.forEach(vm.usuarioList, function (value, index) {
                                if (value.id === vm.reserva.id_usuario) {
                                    vm.usuario = value;
                                }
                            });
                        }
                    });
                }

                $('#data_hora_reserva').data('daterangepicker').setStartDate(vm.reserva.data_hora_reserva);

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
            $('#data_hora_reserva').daterangepicker({timePicker24Hour: true, singleDatePicker: true, timePicker: true, timePickerIncrement: 30, locale: dateRangeLocale});

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
            vm.reserva.id_quadra = vm.quadra.id;
            vm.reserva.id_usuario = vm.usuario.id;

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

        function buscarValor(formulario) {
            if (vm.quadra && vm.reserva.data_hora_reserva) {
                vm.reserva.id_quadra = vm.quadra.id;
                dataservice.buscarValor(vm.reserva).then(success).catch(error);
            }

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar valor.');
                vm.reserva.valor = '';
            }

            function success(response) {
                if (response.data.status) {
                    controllerUtils.feedMessage(response);
                    vm.reserva.valor = '';
                } else {
                    var horario = controllerUtils.getData(response, 'dto');
                    vm.reserva.valor = horario.valor;
                }
            }
        }


        function voltar() {
            controllerUtils.$location.path('reserva');
        }
    }

})();