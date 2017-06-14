(function () {
    'use strict';

    angular
            .module('app.init')
            .factory('initRest', dataservice);

    dataservice.$inject = ['$http', '$location', '$q', 'configuracaoREST', '$httpParamSerializer'];

    function dataservice($http, $location, $q, configuracaoREST, $httpParamSerializer) {
        var service = {
            newsletter: newsletter,
            cadastrar: cadastrar,
            logar: logar,
            senha: senha
        };

        return service;

        function newsletter(data) {
            return $http.post(configuracaoREST.url + configuracaoREST.home + 'newsletter', data);
        }

        function cadastrar(data) {
            return $http.post(configuracaoREST.url + configuracaoREST.home + 'cadastrar', data);
        }

        function logar(data) {
            return $http.post(configuracaoREST.url + configuracaoREST.home + 'logar', data);
        }
        
        function senha(data) {
            return $http.post(configuracaoREST.url + configuracaoREST.home + 'senha', data);
        }
    }
})();