(function () {
    'use strict';

    angular
            .module('app.contato')
            .factory('contatoRest', dataservice);

    dataservice.$inject = ['$http', '$location', '$q', 'configuracaoREST', '$httpParamSerializer'];

    function dataservice($http, $location, $q, configuracaoREST, $httpParamSerializer) {
        var service = {
            enviar: enviar
        };

        return service;

        function enviar(data) {
            return $http.post(configuracaoREST.url + configuracaoREST.publico + "contato", data);
        }
    }
})();