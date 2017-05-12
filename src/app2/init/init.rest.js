(function () {
    'use strict';

    angular
            .module('app.init')
            .factory('initRest', dataservice);

    dataservice.$inject = ['$http', '$location', '$q', 'configuracaoREST', '$httpParamSerializer'];

    function dataservice($http, $location, $q, configuracaoREST, $httpParamSerializer) {
        var service = {
            newsletter: newsletter,
            cadastrar: cadastrar
        };

        return service;

        function newsletter(data) {
            return $http.post(configuracaoREST.url + configuracaoREST.home + 'newsletter', data);
        }

        function cadastrar(data) {
            return $http.post(configuracaoREST.url + 'usuario/salvar', data);
        }
    }
})();