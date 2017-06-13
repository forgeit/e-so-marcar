(function () {
    'use strict';

    angular
            .module('app.horario')
            .controller('HorarioLista', HorarioLista);

    HorarioLista.$inject = [
        '$scope',
        'horarioRest',
        'tabelaUtils',
        'controllerUtils'];

    function HorarioLista($scope, dataservice, tabelaUtils, controllerUtils) {
        /* jshint validthis: true */
        var vm = this;
        vm.alterarHorariosLista = alterarHorariosLista;
        vm.filtro = null;
        vm.tabela = {};
        vm.tipoHorarioList = [];
        vm.instancia = {};

        iniciar();

        function alterarHorariosLista() {
            tabelaUtils.recarregarDados(vm.instancia);
        }

        function carregarTipoHorarioList() {
            return dataservice.buscarComboTipoHorario().then(success).catch(error);

            function error(response) {
                return [];
            }

            function success(response) {
                vm.tipoHorarioList = controllerUtils.getData(response, 'ArrayList');
            }
        }

        function iniciar() {
            montarTabela();
            //carregarTipoHorarioList();
        }

        function montarTabela() {
            criarOpcoesTabela();

            function carregarObjeto(aData) {
                controllerUtils.$location.path('privado/novo-horario/' + aData.id);
                $scope.$apply();
            }

            function criarColunasTabela() {
                vm.tabela.colunas = tabelaUtils.criarColunas([
                    ['quadra', 'Quadra'],
                    ['dia', 'Dia da Semana'],
                    ['hora_inicial', 'Hora Inicial'],
                    ['hora_final', 'Hora Final'],
                    ['valor', 'Valor'],
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