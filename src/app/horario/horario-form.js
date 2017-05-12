(function () {

    'use strict';

    angular.module('app.horario')
            .controller('HorarioForm', HorarioForm);

    HorarioForm.$inject = [
        'controllerUtils',
        'horarioRest',
        '$scope',
        'configuracaoREST'];

    function HorarioForm(controllerUtils, dataservice, $scope, configuracaoREST) {
        /* jshint validthis: true */
        var vm = this;

        vm.atualizar = atualizar;
        vm.horario = {valor: 0};
        vm.preview = 0;
        vm.editar = false;
        vm.filtrar = null;
        vm.salvar = salvar;
        vm.voltar = voltar;

        iniciar();

        function atualizar(formulario) {
            vm.horario.id_quadra = vm.quadra.id;
            vm.horario.dia_semana = vm.dia_semana.id;

            dataservice.atualizar(vm.horario.id, vm.horario).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao atualizar a horario.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);

                if (response.data.status === 'true') {
                    voltar();
                }
            }
        }

        function carregarHorario(data) {
            return dataservice.buscar(data).then(success).catch(error);

            function error(response) {
                return controllerUtils.promise.criar(false, {});
            }

            function success(response) {
                vm.horario = controllerUtils.getData(response, 'dto');

                if (vm.horario.id_quadra) {
                    $scope.$watch('vm.quadraList', function () {
                        if (vm.quadraList.length > 0) {
                            angular.forEach(vm.quadraList, function (value, index) {
                                if (value.id === vm.horario.id_quadra) {
                                    vm.quadra = value;
                                }
                            });
                        }
                    });
                }

                if (vm.horario.dia_semana) {
                    $scope.$watch('vm.diaSemanaList', function () {
                        if (vm.diaSemanaList.length > 0) {
                            angular.forEach(vm.diaSemanaList, function (value, index) {
                                if (value.id === vm.horario.dia_semana) {
                                    vm.dia_semana = value;
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

        function carregarDiaSemanaList() {
            return dataservice.buscarComboDiaSemana().then(success).catch(error);

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
                vm.diaSemanaList = values[1].objeto;
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar os dias da semana.');
            }
        }

        function iniciar() {

            var promises = [];

            promises.push(carregarQuadraList());
            promises.push(carregarDiaSemanaList());

            if (editarObjeto()) {
                promises.push(carregarHorario(controllerUtils.$routeParams.id));
            }

            return controllerUtils.ready(promises).then(function (values) {
                inicializarObjetos(values);
            });
        }

        function salvar(formulario) {
            vm.horario.id_quadra = vm.quadra.id;
            vm.horario.dia_semana = vm.dia_semana.id;

            if (formulario.$valid) {
                dataservice.salvar(vm.horario).then(success).catch(error);
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Dados inválidos.');
            }

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao registrar a horario.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);

                if (response.data.status === 'true') {
                    voltar();
                }
            }
        }

        function voltar() {
            controllerUtils.$location.path('privado/horario');
        }
    }

})();