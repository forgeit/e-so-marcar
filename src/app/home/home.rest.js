(function () {
    'use strict';

    angular
            .module('app.home')
            .factory('homeRest', dataservice);

    dataservice.$inject = ['$http', '$location', '$q', 'configuracaoREST', '$httpParamSerializer'];

    function dataservice($http, $location, $q, configuracaoREST, $httpParamSerializer) {
        var service = {
            buscar: buscar,
            buscarReservas: buscarReservas
        };

        return service;

        function buscar(data) {
            return $http.get(configuracaoREST.url + 'home/buscar');
        }

        function buscarReservas() {
            return $http.get(configuracaoREST.url + configuracaoREST.horario + "buscarSomenteReservas");
        }
    }
})();