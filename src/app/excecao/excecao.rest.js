(function () {
    'use strict';

    angular
            .module('app.excecao')
            .factory('excecaoRest', dataservice);

    dataservice.$inject = ['$http', '$location', '$q', 'configuracaoREST', '$httpParamSerializer'];

    function dataservice($http, $location, $q, configuracaoREST, $httpParamSerializer) {
        var service = {
            atualizar: atualizar,
            buscar: buscar,
            buscarComboDiaSemana: buscarComboDiaSemana,
            buscarComboQuadra: buscarComboQuadra,
            buscarTodos: buscarTodos,
            remover: remover,
            salvar: salvar
        };

        return service;

        function atualizar(id, data) {
            return $http.put(configuracaoREST.url + configuracaoREST.excecao + 'atualizar/' + id, data);
        }

        function buscar(data) {
            return $http.get(configuracaoREST.url + configuracaoREST.excecao + data);
        }

        function buscarComboQuadra() {
            return $http.get(configuracaoREST.url + 'quadra/combo');
        }

        function buscarComboDiaSemana() {
            return $http.get(configuracaoREST.url + 'dia-semana/combo');
        }
        
        function buscarTodos(data, id) {
            if (id !== null) {
                return $http.get(configuracaoREST.url + configuracaoREST.excecao + 'tipo-excecao/' + id);
            } else {
                return $http.get(configuracaoREST.url + configuracaoREST.excecao);
            }
        }

        function remover(data) {
            return $http.delete(configuracaoREST.url + configuracaoREST.excecao + 'excluir/' + data);
        }

        function salvar(data) {
            return $http.post(configuracaoREST.url + configuracaoREST.excecao + 'salvar', data);
        }
    }
})();