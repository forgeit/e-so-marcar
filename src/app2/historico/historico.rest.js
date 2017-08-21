(function () {
    'use strict';

    angular
            .module('app.historico')
            .factory('historicoRest', dataservice);

    dataservice.$inject = ['$http', '$location', '$q', 'configuracaoREST', '$httpParamSerializer'];

    function dataservice($http, $location, $q, configuracaoREST, $httpParamSerializer) {
        var service = {
            buscar: buscar,
            buscarMensal: buscarMensal,
            cancelar: cancelar,
            cancelarMensal: cancelarMensal
        };

        return service;

        function buscar() {
            return $http.get(configuracaoREST.url + configuracaoREST.usuario + "buscarReservas");
        }
        
        function buscarMensal() {
            return $http.get(configuracaoREST.url + configuracaoREST.usuario + "buscarReservasMensal");
        }
        
        function cancelar(id) {
            return $http.post(configuracaoREST.url + configuracaoREST.usuario + "cancelarReserva/" + id);
        }
        
        function cancelarMensal(id) {
            return $http.post(configuracaoREST.url + configuracaoREST.usuario + "cancelarReservaMensal/" + id);
        }

    }
})();