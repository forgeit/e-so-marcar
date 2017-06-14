(function () {
    'use strict';

    angular
            .module('app.gerenciamento')
            .factory('gerenciamentoRest', dataservice);

    dataservice.$inject = ['$http', '$location', '$q', 'configuracaoREST', '$httpParamSerializer'];

    function dataservice($http, $location, $q, configuracaoREST, $httpParamSerializer) {
        var service = {
            salvar: salvar,
            buscar: buscar,
            alterarSenha: alterarSenha
        };

        return service;

        function salvar(data) {
            return $http.post(configuracaoREST.url + configuracaoREST.usuario + "atualizar", data);
        }
        
        function alterarSenha(data) {
            return $http.post(configuracaoREST.url + configuracaoREST.usuario + "alterarSenha", data);
        }

        function buscar() {
            return $http.get(configuracaoREST.url + configuracaoREST.usuario + "buscar");
        }

    }
})();