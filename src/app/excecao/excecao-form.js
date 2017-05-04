(function () {

    'use strict';

    angular.module('app.excecao')
            .controller('ExcecaoForm', ExcecaoForm);

    ExcecaoForm.$inject = [
        'controllerUtils',
        'excecaoRest',
        '$scope',
        'configuracaoREST',
        'dateRangeLocale'];

    function ExcecaoForm(controllerUtils, dataservice, $scope, configuracaoREST, dateRangeLocale) {
        /* jshint validthis: true */
        var vm = this;

        vm.atualizar = atualizar;
        vm.excecao = {valor: null, flag_pode_jogar: false};
        vm.preview = 0;
        vm.editar = false;
        vm.filtrar = null;
        vm.salvar = salvar;
        vm.voltar = voltar;

        iniciar();

        function atualizar(formulario) {
            vm.excecao.id_quadra = vm.quadra.id;
            vm.excecao.dia_semana = vm.dia_semana.id;

            dataservice.atualizar(vm.excecao.id, vm.excecao).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao atualizar a excecao.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);

                if (response.data.status === 'true') {
                    voltar();
                }
            }
        }

        function carregarExcecao(data) {
            return dataservice.buscar(data).then(success).catch(error);

            function error(response) {
                return controllerUtils.promise.criar(false, {});
            }

            function success(response) {
                vm.excecao = controllerUtils.getData(response, 'dto');

                if (vm.excecao.id_quadra) {
                    $scope.$watch('vm.quadraList', function () {
                        if (vm.quadraList.length > 0) {
                            angular.forEach(vm.quadraList, function (value, index) {
                                if (value.id === vm.excecao.id_quadra) {
                                    vm.quadra = value;
                                }
                            });
                        }
                    });
                }
                
                $('#data_hora_inicial').data('daterangepicker').setStartDate(vm.excecao.data_hora_inicial);
                $('#data_hora_final').data('daterangepicker').setStartDate(vm.excecao.data_hora_final);

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
        }

        function iniciar() {

            $('#data_hora_inicial, #data_hora_final').daterangepicker({timePicker24Hour: true, singleDatePicker: true, timePicker: true, timePickerIncrement: 30, locale: dateRangeLocale});

            var promises = [];

            promises.push(carregarQuadraList());

            if (editarObjeto()) {
                promises.push(carregarExcecao(controllerUtils.$routeParams.id));
            }

            return controllerUtils.ready(promises).then(function (values) {
                inicializarObjetos(values);
            });
        }

        function salvar(formulario) {
            vm.excecao.id_quadra = vm.quadra.id;

            if (formulario.$valid) {
                dataservice.salvar(vm.excecao).then(success).catch(error);
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Dados inválidos.');
            }

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao registrar a excecao.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);

                if (response.data.status === 'true') {
                    voltar();
                }
            }
        }

        function voltar() {
            controllerUtils.$location.path('excecao');
        }
    }

})();