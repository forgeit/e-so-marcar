(function () {

    'use strict';

    angular.module('app.anuncio')
            .controller('AnuncioForm', AnuncioForm);

    AnuncioForm.$inject = [
        'controllerUtils',
        'anuncioRest',
        'FileUploader',
        '$scope',
        'configuracaoREST'];

    function AnuncioForm(controllerUtils, dataservice, FileUploader, $scope, configuracaoREST) {
        /* jshint validthis: true */
        var vm = this;

        vm.atualizar = atualizar;
        vm.anuncio = {valor: 0, ativo: false};
        vm.editar = false;
        vm.filtrar = null;
        vm.tipoAnuncioList = [];
        vm.salvar = salvar;
        vm.voltar = voltar;
        vm.uploader = new FileUploader({url: configuracaoREST.url + 'upload'});

        vm.uploader.onAfterAddingFile = function (fileItem) {
            vm.uploader.uploadAll();
        };

        vm.uploader.onSuccessItem = function (fileItem, response, status, headers) {
            if (response.exec) {
                controllerUtils.feed(controllerUtils.messageType.SUCCESS, response.message);
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, response.message);
            }
            vm.anuncio.imagem = response.nome;
        };

        iniciar();

        function atualizar(formulario) {
            vm.anuncio.id_tipo_anuncio = vm.tipoAnuncio.id;

            dataservice.atualizar(vm.anuncio.id, vm.anuncio).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao atualizar a anuncio.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);

                if (response.data.status == 'true') {
                    voltar();
                }
            }
        }

        function carregarAnuncio(data) {
            return dataservice.buscar(data).then(success).catch(error);

            function error(response) {
                return controllerUtils.promise.criar(false, {});
            }

            function success(response) {
                vm.anuncio = controllerUtils.getData(response, 'dto');
                vm.anuncio.ativo = vm.anuncio.ativo === '1';

                if (vm.anuncio.id_tipo_anuncio) {
                    $scope.$watch('vm.tipoAnuncioList', function () {
                        if (vm.tipoAnuncioList.length > 0) {
                            angular.forEach(vm.tipoAnuncioList, function (value, index) {
                                if (value.id === vm.anuncio.id_tipo_anuncio) {
                                    vm.tipoAnuncio = value;
                                }
                            });
                        }
                    });
                }



                return controllerUtils.promise.criar(true, response);
            }
        }

        function carregarTipoAnuncioList() {
            return dataservice.buscarComboTipoAnuncio().then(success).catch(error);

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
                vm.tipoAnuncioList = values[0].objeto;
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar os tipos.');
            }
        }

        function iniciar() {
            $('#data_inicial').datepicker({autoclose: true, format: 'dd/mm/yyyy', language: 'pt-BR'});
            $('#data_final').datepicker({autoclose: true, format: 'dd/mm/yyyy', language: 'pt-BR'});

            var promises = [];

            promises.push(carregarTipoAnuncioList());

            if (editarObjeto()) {
                promises.push(carregarAnuncio(controllerUtils.$routeParams.id));
            }

            return controllerUtils.ready(promises).then(function (values) {
                inicializarObjetos(values);
            });
        }

        function salvar(formulario) {
            vm.anuncio.id_tipo_anuncio = vm.tipoAnuncio.id;

            if (formulario.$valid) {
                dataservice.salvar(vm.anuncio).then(success).catch(error);
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Dados inválidos.');
            }

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao registrar a anuncio.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);

                if (response.data.status === 'true') {
                    voltar();
                }
            }
        }

        function voltar() {
            controllerUtils.$location.path('anuncio');
        }
    }

})();