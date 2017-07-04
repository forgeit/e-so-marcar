(function () {
    'use strict';

    angular
            .module('app.reserva')
            .controller('ReservaLista', ReservaLista);

    ReservaLista.$inject = [
        '$scope',
        'reservaRest',
        'tabelaUtils',
        'controllerUtils'];

    function ReservaLista($scope, dataservice, tabelaUtils, controllerUtils) {
        /* jshint validthis: true */
        var vm = this;
        vm.alterarReservasLista = alterarReservasLista;
        vm.filtro = null;
        vm.tabela = {};
        vm.tipoReservaList = [];
        vm.instancia = {};

        iniciar();

        function alterarReservasLista() {
            tabelaUtils.recarregarDados(vm.instancia);
        }

        function carregarTipoReservaList() {
            return dataservice.buscarComboTipoReserva().then(success).catch(error);

            function error(response) {
                return [];
            }

            function success(response) {
                vm.tipoReservaList = controllerUtils.getData(response, 'ArrayList');
            }
        }

        function iniciar() {
            montarTabela();
            //carregarTipoReservaList();
        }

        function montarTabela() {
            criarOpcoesTabela();

            function carregarObjeto(aData) {
                controllerUtils.$location.path('privado/nova-reserva/' + aData.id);
                $scope.$apply();
            }

            function criarColunasTabela() {
                vm.tabela.colunas = tabelaUtils.criarColunas([
                    ['quadra', 'Quadra'],
                    ['usuario', 'Usuário'],
                    ['telefone', 'Telefone'],
                    ['data_hora_reserva', 'Data e Hora'],
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