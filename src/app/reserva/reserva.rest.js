(function () {
    'use strict';

    angular
            .module('app.reserva')
            .factory('reservaRest', dataservice);

    dataservice.$inject = ['$http', '$location', '$q', 'configuracaoREST', '$httpParamSerializer'];

    function dataservice($http, $location, $q, configuracaoREST, $httpParamSerializer) {
        var service = {
            atualizar: atualizar,
            buscar: buscar,
            buscarValor: buscarValor,
            buscarComboUsuario: buscarComboUsuario,
            buscarComboQuadra: buscarComboQuadra,
            buscarTodos: buscarTodos,
            remover: remover,
            salvar: salvar
        };

        return service;

        function atualizar(id, data) {
            return $http.put(configuracaoREST.url + configuracaoREST.reserva + 'atualizar/' + id, data);
        }

        function buscar(data) {
            return $http.get(configuracaoREST.url + configuracaoREST.reserva + data);
        }
        
        function buscarValor(data) {
            return $http.post(configuracaoREST.url + configuracaoREST.reserva + 'buscar-valor', data);
        }

        function buscarComboQuadra() {
            return $http.get(configuracaoREST.url + 'quadra/combo');
        }

        function buscarComboUsuario() {
            return $http.get(configuracaoREST.url + 'usuario/combo');
        }
        
        function buscarTodos(data, id) {
            if (id !== null) {
                return $http.get(configuracaoREST.url + configuracaoREST.reserva + 'tipo-reserva/' + id);
            } else {
                return $http.get(configuracaoREST.url + configuracaoREST.reserva);
            }
        }

        function remover(data) {
            return $http.delete(configuracaoREST.url + configuracaoREST.reserva + 'excluir/' + data);
        }

        function salvar(data) {
            return $http.post(configuracaoREST.url + configuracaoREST.reserva + 'salvar', data);
        }
    }
})();