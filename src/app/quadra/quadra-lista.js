(function () {
    'use strict';

    angular
            .module('app.quadra')
            .controller('QuadraLista', QuadraLista);

    QuadraLista.$inject = [
        '$scope',
        'quadraRest',
        'tabelaUtils',
        'controllerUtils'];

    function QuadraLista($scope, dataservice, tabelaUtils, controllerUtils) {
        /* jshint validthis: true */
        var vm = this;
        vm.alterarQuadrasLista = alterarQuadrasLista;
        vm.filtro = null;
        vm.tabela = {};
        vm.tipoQuadraList = [];
        vm.instancia = {};

        iniciar();

        function alterarQuadrasLista() {
            tabelaUtils.recarregarDados(vm.instancia);
        }

        function carregarTipoQuadraList() {
            return dataservice.buscarComboTipoQuadra().then(success).catch(error);

            function error(response) {
                return [];
            }

            function success(response) {
                vm.tipoQuadraList = controllerUtils.getData(response, 'ArrayList');
            }
        }

        function iniciar() {
            montarTabela();
            //carregarTipoQuadraList();
        }

        function montarTabela() {
            criarOpcoesTabela();

            function carregarObjeto(aData) {
                controllerUtils.$location.path('novo-quadra/' + aData.id);
                $scope.$apply();
            }

            function criarColunasTabela() {
                vm.tabela.colunas = tabelaUtils.criarColunas([
                    ['titulo', 'Título'],
                    ['situacao', 'Situação Atual'],
                    ['id', 'Ações', tabelaUtils.criarBotaoPadrao]
                ]);
            }

            function criarOpcoesTabela() {
                vm.tabela.opcoes = tabelaUtils.criarTabela(ajax, vm, remover, 'data', carregarObjeto, [1, 'desc']);
                criarColunasTabela();

                function ajax(data, callback, settings) {
                    dataservice.buscarTodos(tabelaUtils.criarParametrosGet(data), vm.filtro).then(success).catch(error);

                    function error(response) {
                        controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao carregar a lista.');
                    }

                    function success(response) {
                        callback(controllerUtils.getData(response, 'datatables'));
                    }
                }
            }

            function remover(aData) {
                dataservice.remover(aData.id).then(success).catch(error);

                function error(response) {
                    controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao remover.');
                }

                function success(response) {
                    controllerUtils.feedMessage(response);
                    if (response.data.status === 'true') {
                        tabelaUtils.recarregarDados(vm.instancia);
                    }
                }
            }
        }
    }
})();