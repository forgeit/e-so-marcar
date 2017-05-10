(function () {
    'use strict';

    angular
            .module('app.reserva')
            .factory('reservaRest', dataservice);

    dataservice.$inject = ['$http', '$location', '$q', 'configuracaoREST', '$httpParamSerializer'];

    function dataservice($http, $location, $q, configuracaoREST, $httpParamSerializer) {
        var service = {
            salvar: salvar,
            buscar: buscar
        };

        return service;

        function salvar(data) {
            return $http.post(configuracaoREST.url + configuracaoREST.reserva + "salvar", data);
        }

        function buscar() {
            return $http.get(configuracaoREST.url + configuracaoREST.reserva + "buscar");
        }

    }
})();