(function () {
    'use strict';

    angular
            .module('app.contato')
            .factory('contatoRest', dataservice);

    dataservice.$inject = ['$http', '$location', '$q', 'configuracaoREST', '$httpParamSerializer'];

    function dataservice($http, $location, $q, configuracaoREST, $httpParamSerializer) {
        var service = {
            buscar: buscar
        };

        return service;

        function buscar(data) {
            return $http.get(configuracaoREST.url + 'contato/buscar');
        }
    }
})();