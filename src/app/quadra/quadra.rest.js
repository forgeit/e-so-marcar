(function () {
    'use strict';

    angular
            .module('app.quadra')
            .factory('quadraRest', dataservice);

    dataservice.$inject = ['$http', '$location', '$q', 'configuracaoREST', '$httpParamSerializer'];

    function dataservice($http, $location, $q, configuracaoREST, $httpParamSerializer) {
        var service = {
            atualizar: atualizar,
            buscar: buscar,
            buscarCombo: buscarCombo,
            buscarComboFiltro: buscarComboFiltro,
            buscarComboTipoQuadra: buscarComboTipoQuadra,
            buscarComboTipoLocal: buscarComboTipoLocal,
            buscarTodos: buscarTodos,
            remover: remover,
            salvar: salvar
        };

        return service;

        function atualizar(id, data) {
            return $http.put(configuracaoREST.url + configuracaoREST.quadra + 'atualizar/' + id, data);
        }

        function buscar(data) {
            return $http.get(configuracaoREST.url + configuracaoREST.quadra + data);
        }

        function buscarComboTipoQuadra() {
            return $http.get(configuracaoREST.url + 'tipo-quadra');
        }
        
        function buscarComboTipoLocal() {
            return $http.get(configuracaoREST.url + 'tipo-local');
        }

        function buscarCombo() {
            return $http.get(configuracaoREST.url + 'quadra/combo');
        }

        function buscarComboFiltro(filtro) {
            return $http.post(configuracaoREST.url + 'quadra/filtro', filtro);
        }

        function buscarTodos(data, id) {
            if (id !== null) {
                return $http.get(configuracaoREST.url + configuracaoREST.quadra + 'tipo-quadra/' + id);
            } else {
                return $http.get(configuracaoREST.url + configuracaoREST.quadra);
            }
        }

        function remover(data) {
            return $http.delete(configuracaoREST.url + configuracaoREST.quadra + 'excluir/' + data);
        }

        function salvar(data) {
            return $http.post(configuracaoREST.url + configuracaoREST.quadra + 'salvar', data);
        }
    }
})();