(function () {

    'use strict';
    angular.module('app.gerenciamento')
            .controller('GerenciamentoForm', GerenciamentoForm);
    GerenciamentoForm.$inject = [
        'controllerUtils',
        'gerenciamentoRest',
        'FileUploader',
        'configuracaoREST',
        'AuthTokenApp2',
        '$rootScope'];
    function GerenciamentoForm(controllerUtils, dataservice, FileUploader, configuracaoREST, AuthTokenApp2, $rootScope) {
        /* jshint validthis: true */
        var vm = this;

        vm.usuario = {};
        vm.salvar = salvar;
        vm.alterarSenha = alterarSenha;
        vm.desativarConta = desativarConta;
        vm.voltar = voltar;
        vm.preview;
        vm.uploader = new FileUploader({
            url: configuracaoREST.url + 'upload',
            queueLimit: 1
        });

        iniciar();

        vm.buscarCidades = function buscarCidades(idEstado) {

            dataservice.cidades(idEstado).then(success).catch(error);

            function success(response) {
                vm.cidades = controllerUtils.getData(response, 'ArrayList');
            }

            function error() {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar cidades.');
            }

        };

        vm.uploader.onSuccessItem = function (fileItem, response, status, headers) {
            if (response.exec) {
                controllerUtils.feed(controllerUtils.messageType.SUCCESS, response.message);
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, response.message);
            }
            vm.usuario.imagem = response.nome;
        };

        function iniciar() {
            var promises = [];

            promises.push(buscar());
            promises.push(buscarEstados());

            return controllerUtils.ready(promises).then(function (values) {
                if (values[0].exec) {
                    vm.usuario = values[0].objeto;
                    vm.preview = vm.usuario.imagem;
                } else {
                    controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar dados.');
                }
                if (values[1].exec) {
                    vm.estados = values[1].objeto;
                } else {
                    controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar estados.');
                }

                vm.buscarCidades(vm.usuario.id_uf);
                vm.usuario.cidade = {id: vm.usuario.id_cidade};

                angular.forEach(vm.estados, function (value, index) {
                    if (value.id === vm.usuario.id_uf) {
                        vm.usuario.estado = value;
                    }
                });
                
            });
        }

        function buscar() {
            return dataservice.buscar().then(success).catch(error);

            function success(response) {
                var array = controllerUtils.getData(response, 'dto');
                return controllerUtils.promise.criar(true, array);
            }

            function error() {
                return controllerUtils.promise.criar(false, []);
            }

        }

        function buscarEstados() {
            return dataservice.estados().then(success).catch(error);

            function success(response) {
                var array = controllerUtils.getData(response, 'ArrayList');
                return controllerUtils.promise.criar(true, array);
            }

            function error() {
                return controllerUtils.promise.criar(false, []);
            }

        }

        function salvar() {

            dataservice.salvar(vm.usuario).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao atualizar as informações.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);
                if (response.data.status === 'true') {
                    $rootScope.usuarioSistema.nomeExibir = vm.usuario.nome;
                    voltar();
                }
            }
        }

        function alterarSenha() {

            dataservice.alterarSenha(vm.novaSenha).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao alterar senha.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);
                if (response.data.status === 'true') {
                    voltar();
                }
            }
        }

        function desativarConta(aData) {
            $.confirm({
                text: "Você tem certeza que deseja desativar sua conta?",
                title: "Confirmação",
                confirm: function (button) {
                    desativar(aData);
                },
                confirmButtonClass: "btn-danger btn-flat",
                cancelButtonClass: "btn-default btn-flat",
                confirmButton: "Sim, tenho certeza!",
                cancelButton: "Não",
                dialogClass: "modal-dialog modal-lg"
            });
        }

        function desativar(data) {

            dataservice.desativar(data).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao alterar senha.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);
                if (response.data.status === 'true') {
                    AuthTokenApp2.remover();
                    $rootScope.usuarioSistema = null;
                    voltar();
                }
            }
        }

        function voltar() {
            controllerUtils.$location.path('/');
        }

        $('#data_nascimento').datepicker({autoclose: true, format: 'dd/mm/yyyy', language: 'pt-BR'});
    }

})();