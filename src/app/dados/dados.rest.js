(function () {
    'use strict';

    angular
            .module('app.dados')
            .factory('dadosRest', dataservice);

    dataservice.$inject = ['$http', '$location', '$q', 'configuracaoREST', '$httpParamSerializer'];

    function dataservice($http, $location, $q, configuracaoREST, $httpParamSerializer) {
        var service = {
            salvar: salvar,
            buscar: buscar
        };

        return service;

        function salvar(id, data) {
            return $http.post(configuracaoREST.url + configuracaoREST.dados + id, data);
        }

        function buscar() {
            return $http.get(configuracaoREST.url + configuracaoREST.dados + "buscar");
        }

    }
})();