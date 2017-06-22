(function () {

    'use strict';
    angular.module('app.senha')
            .controller('SenhaForm', SenhaForm);
    SenhaForm.$inject = [
        'controllerUtils',
        'senhaRest',
        'configuracaoREST',
        '$rootScope'];
    function SenhaForm(controllerUtils, dataservice, configuracaoREST, $rootScope) {
        /* jshint validthis: true */
        var vm = this;

        vm.dados = {};
        vm.salvar = salvar;
        vm.voltar = voltar;

        function salvar() {

            dataservice.salvar(vm.dados).then(success).catch(error);

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

        function voltar() {
            controllerUtils.$location.path('privado/');
        }
    }

})();