(function () {
    'use strict';

    angular
            .module('app.horario')
            .factory('horarioRest', dataservice);

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
            return $http.put(configuracaoREST.url + configuracaoREST.horario + 'atualizar/' + id, data);
        }

        function buscar(data) {
            return $http.get(configuracaoREST.url + configuracaoREST.horario + data);
        }

        function buscarComboQuadra() {
            return $http.get(configuracaoREST.url + 'quadra/combo');
        }

        function buscarComboDiaSemana() {
            return $http.get(configuracaoREST.url + 'dia-semana/combo');
        }
        
        function buscarTodos(data, id) {
            if (id !== null) {
                return $http.get(configuracaoREST.url + configuracaoREST.horario + 'tipo-horario/' + id);
            } else {
                return $http.get(configuracaoREST.url + configuracaoREST.horario);
            }
        }

        function remover(data) {
            return $http.delete(configuracaoREST.url + configuracaoREST.horario + 'excluir/' + data);
        }

        function salvar(data) {
            return $http.post(configuracaoREST.url + configuracaoREST.horario + 'salvar', data);
        }
    }
})();