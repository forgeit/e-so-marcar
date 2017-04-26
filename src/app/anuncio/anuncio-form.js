(function () {

    'use strict';

    angular.module('app.anuncio')
            .controller('AnuncioForm', AnuncioForm);

    AnuncioForm.$inject = [
        'controllerUtils',
        'anuncioRest',
        'datepicker',
        'FileUploader',
        '$scope',
        'configuracaoREST'];

    function AnuncioForm(controllerUtils, dataservice, datepicker, FileUploader, $scope, configuracaoREST) {
        /* jshint validthis: true */
        var vm = this;

        vm.atualizar = atualizar;
        vm.anuncio = {};
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
            dataservice.atualizar(vm.anuncio.id_anuncio, vm.anuncio).then(success).catch(error);

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
                console.log(response);
                return controllerUtils.promise.criar(false, {});
            }

            function success(response) {
                var departamento = controllerUtils.getData(response, 'AnuncioDto');
                var retorno = departamento;


                $scope.$watch('vm.cidadeList', function () {
                    if (vm.cidadeList.length > 0) {
                        angular.forEach(vm.cidadeList, function (value, index) {
                            if (retorno.cidade) {
                                if (value.id_cidade === retorno.cidade.id_cidade) {
                                    vm.carregarBairroList(vm.anuncio.cidade.id_cidade);
                                }
                            }
                        });
                    }
                });

                $scope.$watch('vm.bairroList', function () {
                    if (vm.bairroList.length > 0) {
                        angular.forEach(vm.bairroList, function (value, index) {
                            if (retorno.bairro) {
                                if (value.id_bairro === retorno.bairro.id_bairro) {
                                    vm.carregarLogradouroList(vm.anuncio.bairro.id_bairro);
                                }
                            }
                        });
                    }
                });


                return controllerUtils.promise.criar(true, retorno);
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

            if (editarObjeto()) {
                if (values[2].exec) {
                    vm.anuncio = values[2].objeto;
                } else {
                    controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar os dados da anuncio.');
                }
            }
        }

        function iniciar() {
            $('#data_inicial').datepicker(datepicker);
            $('#data_final').datepicker(datepicker);

            var promises = [];

            promises.push(carregarTipoAnuncioList());

            if (editarObjeto()) {
                promises.push(carregarAnuncio(controllerUtils.$routeParams.id));
            }

            return controllerUtils.ready(promises).then(function (values) {
                vm.anuncio.fgTipoAnuncio = "F";
                inicializarObjetos(values);
            });
        }

        function salvar(formulario) {
            if (formulario.$valid) {
                dataservice.salvar(vm.anuncio).then(success).catch(error);
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Dados inválidos.');
            }

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao registrar a anuncio.');
            }

            function success(response) {
                console.log(response);
                controllerUtils.feedMessage(response);

                if (response.data.status == 'true') {
                    voltar();
                }
            }
        }

        function voltar() {
            controllerUtils.$location.path('anuncio');
        }
    }

})();