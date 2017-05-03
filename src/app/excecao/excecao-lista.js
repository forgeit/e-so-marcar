(function () {
    'use strict';

    angular
            .module('app.excecao')
            .controller('ExcecaoLista', ExcecaoLista);

    ExcecaoLista.$inject = [
        '$scope',
        'excecaoRest',
        'tabelaUtils',
        'controllerUtils'];

    function ExcecaoLista($scope, dataservice, tabelaUtils, controllerUtils) {
        /* jshint validthis: true */
        var vm = this;
        vm.alterarExcecaosLista = alterarExcecaosLista;
        vm.filtro = null;
        vm.tabela = {};
        vm.tipoExcecaoList = [];
        vm.instancia = {};

        iniciar();

        function alterarExcecaosLista() {
            tabelaUtils.recarregarDados(vm.instancia);
        }

        function carregarTipoExcecaoList() {
            return dataservice.buscarComboTipoExcecao().then(success).catch(error);

            function error(response) {
                return [];
            }

            function success(response) {
                vm.tipoExcecaoList = controllerUtils.getData(response, 'ArrayList');
            }
        }

        function iniciar() {
            montarTabela();
            //carregarTipoExcecaoList();
        }

        function montarTabela() {
            criarOpcoesTabela();

            function carregarObjeto(aData) {
                controllerUtils.$location.path('nova-excecao/' + aData.id);
                $scope.$apply();
            }

            function criarColunasTabela() {
                vm.tabela.colunas = tabelaUtils.criarColunas([
                    ['quadra', 'Quadra'],
                    ['data_hora_inicial', 'Data e Hora Inicial'],
                    ['data_hora_final', 'Data e Hora Final'],
                    ['id', 'Ações', tabelaUtils.criarBotaoPadrao]
                ]);
            }

            function criarOpcoesTabela() {
                vm.tabela.opcoes = tabelaUtils.criarTabela(ajax, vm, remover, 'data', carregarObjeto, [0, 'asc']);
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