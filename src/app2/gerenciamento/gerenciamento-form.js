(function () {

    'use strict';
    angular.module('app.gerenciamento')
            .controller('GerenciamentoForm', GerenciamentoForm);
    GerenciamentoForm.$inject = [
        'controllerUtils',
        'gerenciamentoRest',
        'FileUploader',
        'configuracaoREST'];
    function GerenciamentoForm(controllerUtils, dataservice, FileUploader, configuracaoREST) {
        /* jshint validthis: true */
        var vm = this;

        vm.usuario = {};
        vm.salvar = salvar;
        vm.voltar = voltar;
        vm.preview;
        vm.uploader = new FileUploader({
            url: configuracaoREST.url + 'upload',
            queueLimit: 1
        });

        vm.uploader.onSuccessItem = function (fileItem, response, status, headers) {
            if (response.exec) {
                controllerUtils.feed(controllerUtils.messageType.SUCCESS, response.message);
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, response.message);
            }
            vm.usuario.imagem = response.nome;
        };

        buscar();

        function buscar() {
            dataservice.buscar().then(success).catch(error);

            function success(response) {
                vm.usuario = controllerUtils.getData(response, 'dto');
                vm.preview = vm.usuario.imagem;
            }

            function error() {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar as informações.');
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