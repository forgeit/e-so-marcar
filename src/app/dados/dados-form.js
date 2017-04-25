(function () {

    'use strict';
    angular.module('app.dados')
            .controller('DadosForm', DadosForm);
    DadosForm.$inject = [
        'controllerUtils',
        'dadosRest',
        'configuracaoREST'];
    function DadosForm(controllerUtils, dataservice) {
        /* jshint validthis: true */
        var vm = this;

        vm.dados = {};
        vm.salvar = salvar;
        vm.voltar = voltar;

        buscar();

        function buscar() {
            dataservice.buscar().then(success).catch(error);

            function success(response) {
                vm.dados = controllerUtils.getData(response, 'dto');
            }

            function error() {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar os dados.');
            }

        }

        function salvar() {

            dataservice.salvar(vm.dados).then(success).catch(error);

            function error(response) {
                console.log(response);
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao salvar od dados.');
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
            controllerUtils.$location.path('/');
        }
    }

})();