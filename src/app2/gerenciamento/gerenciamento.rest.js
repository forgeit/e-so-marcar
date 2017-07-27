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
            alterarSenha: alterarSenha,
            estados: estados,
            cidades: cidades,
            desativar: desativar
        };

        return service;

        function salvar(data) {
            return $http.post(configuracaoREST.url + configuracaoREST.usuario + "atualizar", data);
        }

        function alterarSenha(data) {
            return $http.post(configuracaoREST.url + configuracaoREST.usuario + "alterarSenha", data);
        }

        function desativar(data) {
            return $http.post(configuracaoREST.url + configuracaoREST.usuario + "desativarConta", data);
        }

        function buscar() {
            return $http.get(configuracaoREST.url + configuracaoREST.usuario + "buscar");
        }
        
        function estados() {
            return $http.get(configuracaoREST.url + "estado/buscarTodos");
        }
        
        function cidades(estado) {
            return $http.get(configuracaoREST.url + "cidade/buscar/" + estado);
        }

    }
})();