(function () {

    'use strict';
    angular.module('app.dados')
            .controller('DadosForm', DadosForm);
    DadosForm.$inject = [
        'controllerUtils',
        'dadosRest',
        'FileUploader',
        'configuracaoREST'];
    function DadosForm(controllerUtils, dataservice, FileUploader, configuracaoREST) {
        /* jshint validthis: true */
        var vm = this;

        vm.dados = {};
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
            vm.dados.logo = response.nome;
        };

        buscar();

        function buscar() {
            dataservice.buscar().then(success).catch(error);

            function success(response) {
                vm.dados = controllerUtils.getData(response, 'dto');
                vm.preview = vm.dados.logo;
            }

            function error() {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar os dados.');
            }

        }

        function salvar() {

            dataservice.salvar(vm.dados).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao salvar os dados.');
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
    }

})();