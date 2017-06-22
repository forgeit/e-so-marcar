(function () {
    'use strict';

    angular
            .module('app.senha')
            .factory('senhaRest', dataservice);

    dataservice.$inject = ['$http', '$location', '$q', 'configuracaoREST', '$httpParamSerializer'];

    function dataservice($http, $location, $q, configuracaoREST, $httpParamSerializer) {
        var service = {
            salvar: salvar
        };

        return service;

        function salvar(data) {
            return $http.post(configuracaoREST.url + configuracaoREST.dados + "senha", data);
        }

    }
})();