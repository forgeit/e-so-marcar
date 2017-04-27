(function () {

    'use strict';

    angular.module('app.quadra')
            .controller('QuadraForm', QuadraForm);

    QuadraForm.$inject = [
        'controllerUtils',
        'quadraRest',
        'FileUploader',
        '$scope',
        'configuracaoREST'];

    function QuadraForm(controllerUtils, dataservice, FileUploader, $scope, configuracaoREST) {
        /* jshint validthis: true */
        var vm = this;

        vm.atualizar = atualizar;
        vm.quadra = {valor: 0, ativo: true};
        vm.preview = 0;
        vm.editar = false;
        vm.filtrar = null;
        vm.tipoQuadraList = [];
        vm.salvar = salvar;
        vm.voltar = voltar;
        vm.uploader = new FileUploader({
            url: configuracaoREST.url + 'upload',
            queueLimit: 1
        });

        vm.uploader.onCompleteItem = function (fileItem, response, status, headers) {
            if (response.exec) {
                controllerUtils.feed(controllerUtils.messageType.SUCCESS, response.message);
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, response.message);
            }
            vm.quadra.imagem = response.nome;
        };

        iniciar();

        function atualizar(formulario) {
            vm.quadra.id_tipo_quadra = vm.tipoQuadra.id;

            dataservice.atualizar(vm.quadra.id, vm.quadra).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao atualizar a quadra.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);

                if (response.data.status == 'true') {
                    voltar();
                }
            }
        }

        function carregarQuadra(data) {
            return dataservice.buscar(data).then(success).catch(error);

            function error(response) {
                return controllerUtils.promise.criar(false, {});
            }

            function success(response) {
                vm.quadra = controllerUtils.getData(response, 'dto');
                vm.quadra.ativo = vm.quadra.ativo === '1';
                vm.preview = vm.quadra.imagem;

                if (vm.quadra.id_tipo_quadra) {
                    $scope.$watch('vm.tipoQuadraList', function () {
                        if (vm.tipoQuadraList.length > 0) {
                            angular.forEach(vm.tipoQuadraList, function (value, index) {
                                if (value.id === vm.quadra.id_tipo_quadra) {
                                    vm.tipoQuadra = value;
                                }
                            });
                        }
                    });
                }



                return controllerUtils.promise.criar(true, response);
            }
        }

        function carregarTipoQuadraList() {
            return dataservice.buscarComboTipoQuadra().then(success).catch(error);

            function error(response) {
                return controllerUtils.promise.criar(false, []);
            }

            function success(response) {
                var array = controllerUtils.getData(response, 'ArrayList');
                return controllerUtils.promise.criar(true, array);
            }
        }

        function carregarTipoLocalList() {
            return dataservice.buscarComboTipoLocal().then(success).catch(error);

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
                vm.tipoQuadraList = values[0].objeto;
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar os tipos de quadra.');
            }
            
            if (values[1].exec) {
                vm.tipoLocalList = values[1].objeto;
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar os tipos de local.');
            }
        }

        function iniciar() {
            $('#data_inicial').datepicker({autoclose: true, format: 'dd/mm/yyyy', language: 'pt-BR', startDate: "dateToday"})
                    .on('changeDate', function (e) {
                        var dt = new Date(e.date);
                        dt.setDate(dt.getDate() + 30);
                        $('#data_final').datepicker('update', dt);
                        $('#data_final').datepicker('setStartDate', e.date);
                    });
            $('#data_final').datepicker({autoclose: true, format: 'dd/mm/yyyy', language: 'pt-BR'});

            var promises = [];

            promises.push(carregarTipoQuadraList());
            promises.push(carregarTipoLocalList());

            if (editarObjeto()) {
                promises.push(carregarQuadra(controllerUtils.$routeParams.id));
            }

            return controllerUtils.ready(promises).then(function (values) {
                inicializarObjetos(values);
            });
        }

        function salvar(formulario) {
            vm.quadra.id_tipo_quadra = vm.tipoQuadra.id;
            if (vm.uploader.queue.length === 0) {
                vm.quadra.imagem = '';
            }

            if (formulario.$valid) {
                dataservice.salvar(vm.quadra).then(success).catch(error);
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Dados inválidos.');
            }

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao registrar a quadra.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);

                if (response.data.status === 'true') {
                    voltar();
                }
            }
        }

        function voltar() {
            controllerUtils.$location.path('quadra');
        }
    }

})();