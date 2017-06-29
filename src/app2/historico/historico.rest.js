(function () {
    'use strict';

    angular
            .module('app.historico')
            .factory('historicoRest', dataservice);

    dataservice.$inject = ['$http', '$location', '$q', 'configuracaoREST', '$httpParamSerializer'];

    function dataservice($http, $location, $q, configuracaoREST, $httpParamSerializer) {
        var service = {
            buscar: buscar,
            cancelar: cancelar
        };

        return service;

        function buscar() {
            return $http.get(configuracaoREST.url + configuracaoREST.usuario + "buscarReservas");
        }
        
        function cancelar(id) {
            return $http.post(configuracaoREST.url + configuracaoREST.usuario + "cancelarReserva/" + id);
        }

    }
})();