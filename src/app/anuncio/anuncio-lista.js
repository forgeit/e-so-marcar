(function () {
    'use strict';

    angular
            .module('app.anuncio')
            .controller('AnuncioLista', AnuncioLista);

    AnuncioLista.$inject = [
        '$scope',
        'anuncioRest',
        'tabelaUtils',
        'controllerUtils'];

    function AnuncioLista($scope, dataservice, tabelaUtils, controllerUtils) {
        /* jshint validthis: true */
        var vm = this;
        vm.alterarAnunciosLista = alterarAnunciosLista;
        vm.filtro = null;
        vm.tabela = {};
        vm.tipoAnuncioList = [];
        vm.instancia = {};

        iniciar();

        function alterarAnunciosLista() {
            tabelaUtils.recarregarDados(vm.instancia);
        }

        function carregarTipoAnuncioList() {
            return dataservice.buscarComboTipoAnuncio().then(success).catch(error);

            function error(response) {
                return [];
            }

            function success(response) {
                vm.tipoAnuncioList = controllerUtils.getData(response, 'ArrayList');
            }
        }

        function iniciar() {
            montarTabela();
            //carregarTipoAnuncioList();
        }

        function montarTabela() {
            criarOpcoesTabela();

            function carregarObjeto(aData) {
                controllerUtils.$location.path('novo-anuncio/' + aData.id);
                $scope.$apply();
            }

            function criarColunasTabela() {
                vm.tabela.colunas = tabelaUtils.criarColunas([
                    ['titulo', 'Título'],
                    ['data_inicial', 'Data Inicial'],
                    ['data_final', 'Data Final'],
                    ['tipo', 'Tipo'],
                    ['id', 'Ações', tabelaUtils.criarBotaoPadrao]
                ]);
            }

            function criarOpcoesTabela() {
                vm.tabela.opcoes = tabelaUtils.criarTabela(ajax, vm, remover, 'data', carregarObjeto);
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
                dataservice.remover(aData.id_anuncio).then(success).catch(error);

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