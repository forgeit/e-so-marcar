(function () {
    'use strict';

    angular
            .module('app.anuncio')
            .factory('anuncioRest', dataservice);

    dataservice.$inject = ['$http', '$location', '$q', 'configuracaoREST', '$httpParamSerializer'];

    function dataservice($http, $location, $q, configuracaoREST, $httpParamSerializer) {
        var service = {
            atualizar: atualizar,
            buscar: buscar,
            buscarCombo: buscarCombo,
            buscarComboFiltro: buscarComboFiltro,
            buscarComboTipoAnuncio: buscarComboTipoAnuncio,
            buscarTodos: buscarTodos,
            remover: remover,
            salvar: salvar
        };

        return service;

        function atualizar(id, data) {
            return $http.put(configuracaoREST.url + configuracaoREST.anuncio + 'atualizar/' + id, data);
        }

        function buscar(data) {
            return $http.get(configuracaoREST.url + configuracaoREST.anuncio + data);
        }

        function buscarComboTipoAnuncio() {
            return $http.get(configuracaoREST.url + 'tipo-anuncio');
        }

        function buscarCombo() {
            return $http.get(configuracaoREST.url + 'anuncio/combo');
        }

        function buscarComboFiltro(filtro) {
            return $http.post(configuracaoREST.url + 'anuncio/filtro', filtro);
        }

        function buscarTodos(data, id) {
            if (id !== null) {
                return $http.get(configuracaoREST.url + configuracaoREST.anuncio + 'tipo-anuncio/' + id);
            } else {
                return $http.get(configuracaoREST.url + configuracaoREST.anuncio);
            }
        }

        function remover(data) {
            return $http.delete(configuracaoREST.url + configuracaoREST.anuncio + 'excluir/' + data);
        }

        function salvar(data) {
            return $http.post(configuracaoREST.url + configuracaoREST.anuncio + 'salvar', data);
        }
    }
})();