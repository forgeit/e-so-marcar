(function () {
    'use strict';

    angular
            .module('usuario.login')
            .factory('loginRest', dataservice);

    dataservice.$inject = ['$http', '$location', '$q', 'configuracaoREST', '$httpParamSerializer'];

    function dataservice($http, $location, $q, configuracaoREST, $httpParamSerializer) {
        var service = {
            logar: logar
        };

        return service;

        function logar(data) {
            return $http.post(configuracaoREST.url + 'login/entrar', data);
        }
    }
})();