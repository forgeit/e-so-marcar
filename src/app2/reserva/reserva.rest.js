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
            buscarQuadra: buscarQuadra,
            buscarReservas: buscarReservas,
            buscarBanner: buscarBanner
        };

        return service;

        function salvar(data) {
            return $http.post(configuracaoREST.url + configuracaoREST.reserva + "salvarUsuario", data);
        }
        
        function buscarCliente(data) {
            return $http.get(configuracaoREST.url + configuracaoREST.publico + "buscar/" + data);
        }

        function buscarClientes() {
            return $http.get(configuracaoREST.url + configuracaoREST.publico + "buscarTodos");
        }
        
        function buscarQuadra(data) {
            return $http.get(configuracaoREST.url + configuracaoREST.publico + "buscarQuadra/" + data);
        }
        
        function buscarQuadras(data) {
            return $http.get(configuracaoREST.url + configuracaoREST.publico + "buscarTodosNativo/" + data);
        }
        
        function buscarReservas(data) {
            return $http.get(configuracaoREST.url + configuracaoREST.publico + "buscarReservas/" + data);
        }
        
        function buscarBanner(data) {
            return $http.get(configuracaoREST.url + configuracaoREST.publico + 'buscarBanner/cliente/' + data);
        }

    }
})();