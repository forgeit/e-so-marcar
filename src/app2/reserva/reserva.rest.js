(function () {
    'use strict';

    angular
            .module('app.reserva')
            .factory('reservaRest', dataservice);

    dataservice.$inject = ['$http', '$location', '$q', 'configuracaoREST', '$httpParamSerializer'];

    function dataservice($http, $location, $q, configuracaoREST, $httpParamSerializer) {
        var service = {
            salvar: salvar,
            buscarClientes: buscarClientes,
            buscarCliente: buscarCliente,
            buscarQuadras: buscarQuadras,
            buscarQuadra: buscarQuadra
        };

        return service;

        function salvar(data) {
            return $http.post(configuracaoREST.url + configuracaoREST.reserva + "salvar", data);
        }
        
        function buscarCliente(data) {
            return $http.get(configuracaoREST.url + configuracaoREST.cliente + "buscar/" + data);
        }

        function buscarClientes() {
            return $http.get(configuracaoREST.url + configuracaoREST.cliente + "buscarTodos");
        }
        
        function buscarQuadra(data) {
            return $http.get(configuracaoREST.url + configuracaoREST.quadra + "buscarQuadra/" + data);
        }
        
        function buscarQuadras(data) {
            return $http.get(configuracaoREST.url + configuracaoREST.quadra + "buscarTodosNativo/" + data);
        }

    }
})();