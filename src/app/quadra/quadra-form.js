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
        vm.quadra = {
            situacao: true,
            flag_dia_chuva: true,
            flag_marcacao_mensal: true,
            flag_tamanho_oficial: false
        };
        vm.range = function (min, max, step) {
            step = step || 1;
            var input = [];
            for (var i = min; i <= max; i += step) {
                input.push(i);
            }
            return input;
        };
        vm.toggleHorario = function (ele, dia, hora) {
            if ($(ele.target).hasClass('btn-success')) {
                $(ele.target).removeClass('btn-success');
                vm.quadra.funcionamento.splice(vm.quadra.funcionamento.indexOf({dia: dia, hora: hora}), 1);
            } else {
                $(ele.target).addClass('btn-success');
                vm.quadra.funcionamento.push({dia: dia, hora: hora});
            }
        };
        vm.esportes = [];
        vm.esportesAux = [];
        vm.editar = false;
        vm.filtrar = null;
        vm.tipoQuadraList = [];
        vm.salvar = salvar;
        vm.voltar = voltar;
        vm.uploader0 = new FileUploader({
            url: configuracaoREST.url + 'upload',
            queueLimit: 1
        });
        vm.uploader1 = new FileUploader({
            url: configuracaoREST.url + 'upload',
            queueLimit: 1
        });
        vm.uploader2 = new FileUploader({
            url: configuracaoREST.url + 'upload',
            queueLimit: 1
        });
        vm.uploader3 = new FileUploader({
            url: configuracaoREST.url + 'upload',
            queueLimit: 1
        });
        
        vm.uploader0.onCompleteItem = function (fileItem, response, status, headers) {
            if (response.exec) {
                controllerUtils.feed(controllerUtils.messageType.SUCCESS, response.message);
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, response.message);
            }
            vm.quadra.imagem = response.nome;
        };
        vm.uploader1.onCompleteItem = function (fileItem, response, status, headers) {
            if (response.exec) {
                controllerUtils.feed(controllerUtils.messageType.SUCCESS, response.message);
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, response.message);
            }
            vm.quadra.imagem1 = response.nome;
        };
        vm.uploader2.onCompleteItem = function (fileItem, response, status, headers) {
            if (response.exec) {
                controllerUtils.feed(controllerUtils.messageType.SUCCESS, response.message);
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, response.message);
            }
            vm.quadra.imagem2 = response.nome;
        };
        vm.uploader3.onCompleteItem = function (fileItem, response, status, headers) {
            if (response.exec) {
                controllerUtils.feed(controllerUtils.messageType.SUCCESS, response.message);
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, response.message);
            }
            vm.quadra.imagem3 = response.nome;
        };

        iniciar();

        function atualizar(formulario) {
            vm.quadra.id_tipo_quadra = vm.tipoQuadra.id;
            vm.quadra.id_tipo_quadra = vm.tipoQuadra ? vm.tipoQuadra.id : null;
            vm.quadra.id_tipo_local = vm.tipoLocal ? vm.tipoLocal.id : null;
            vm.quadra.esportes = vm.esportes;

            dataservice.atualizar(vm.quadra.id, vm.quadra).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao atualizar a quadra.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);

                if (response.data.status === 'true') {
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
                if (response.data.status) {
                    controllerUtils.feedMessage(response);
                    voltar();
                }

                vm.quadra = controllerUtils.getData(response, 'dto');
                vm.quadra.situacao = vm.quadra.situacao === '1';
                vm.quadra.flag_tamanho_oficial = vm.quadra.flag_tamanho_oficial === '1';
                vm.quadra.flag_dia_chuva = vm.quadra.flag_dia_chuva === '1';
                vm.quadra.flag_marcacao_mensal = vm.quadra.flag_marcacao_mensal === '1';
                
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

                if (vm.quadra.id_tipo_local) {
                    $scope.$watch('vm.tipoLocalList', function () {
                        if (vm.tipoLocalList.length > 0) {
                            angular.forEach(vm.tipoLocalList, function (value, index) {
                                if (value.id === vm.quadra.id_tipo_local) {
                                    vm.tipoLocal = value;
                                }
                            });
                        }
                    });
                }

                if (vm.quadra.esportes) {
                    angular.forEach(vm.quadra.esportes, function (value, index) {
                        vm.esportesAux.push(value.id_esporte);
                    });
                    $scope.$watch('vm.tipoEsporteList', function () {
                        if (vm.tipoEsporteList.length > 0) {
                            var aux = [];
                            angular.forEach(vm.tipoEsporteList, function (value, index) {
                                if (vm.esportesAux.indexOf(value.id) >= 0) {
                                    aux.push(value);
                                }
                            });
                            vm.esportes = aux;
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

        function carregarTipoEsporteList() {
            return dataservice.buscarComboTipoEsporte().then(success).catch(error);

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

            if (values[2].exec) {
                vm.tipoEsporteList = values[2].objeto;
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar os tipos de esporte.');
            }
        }

        function iniciar() {
            $('#esportes').select2({
                placeholder: 'Selecione'
            });

            var promises = [];

            promises.push(carregarTipoQuadraList());
            promises.push(carregarTipoLocalList());
            promises.push(carregarTipoEsporteList());

            if (editarObjeto()) {
                promises.push(carregarQuadra(controllerUtils.$routeParams.id));
            }

            return controllerUtils.ready(promises).then(function (values) {
                inicializarObjetos(values);
            });
        }

        function salvar(formulario) {
            vm.quadra.id_tipo_quadra = vm.tipoQuadra ? vm.tipoQuadra.id : null;
            vm.quadra.id_tipo_local = vm.tipoLocal ? vm.tipoLocal.id : null;
            vm.quadra.esportes = vm.esportes;

            if (vm.uploader1.queue.length === 0) {
                vm.quadra.imagem = '';
            }
            if (vm.uploader2.queue.length === 0) {
                //vm.quadra.imagem = '';
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
            controllerUtils.$location.path('privado/quadra');
        }
    }

})();