(function () {
    'use strict';

    angular
            .module('app.home')
            .factory('homeRest', dataservice);

    dataservice.$inject = ['$http', '$location', '$q', 'configuracaoREST', '$httpParamSerializer'];

    function dataservice($http, $location, $q, configuracaoREST, $httpParamSerializer) {
        var service = {
            buscar: buscar,
            cadastrar: cadastrar
        };

        return service;

        function buscar() {
            return $http.get(configuracaoREST.url + configuracaoREST.home + 'count');
        }

        function cadastrar(data) {
            return $http.get(configuracaoREST.url + 'usuario/salvar', data);
        }
    }
})();