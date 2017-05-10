(function () {
    'use strict';

    angular
            .module('core.utils')
            .factory('tabelaUtils', tabelaUtils);

    tabelaUtils.$inject = ['$compile', 'DTColumnBuilder', 'DTOptionsBuilder', '$httpParamSerializer', '$http', 'datatables'];

    function tabelaUtils($compile, DTColumnBuilder, DTOptionsBuilder, $httpParamSerializer, $http, datatables) {
        var service = {
            criarBotaoPadrao: criarBotaoPadrao,
            criarColunas: criarColunas,
            criarTabela: criarTabela,
            criarParametrosGet: criarParametrosGet,
            recarregarDados: recarregarDados
        };

        return service;

        function criarBotaoPadrao() {
            return '<div class="text-center"><btn-editar class="editar"></btn-editar>&nbsp;<btn-remover class="remover"></btn-remover></div>';
        }

        function criarColunas(colunas) {
            var dtColumns = [];

            angular.forEach(colunas, function (value, key) {
                var column = DTColumnBuilder.newColumn(value[0]).withTitle(value[1]);

                if (value.length === 3) {
                    column.renderWith(value[2]);
                }

                dtColumns.push(column);
            });

            return dtColumns;
        }

        function criarParametrosGet(request) {

            var data = {
                request: request
            };

            return $httpParamSerializer(data);
        }

        function criarTabela(ajax, vm, remover, nomeArrayRetorno, carregarObjeto, order) {
            return DTOptionsBuilder.newOptions()
                    .withOption('ajax', ajax)
                    .withPaginationType('full_numbers')
                    .withOption('createdRow', createdRow)
                    .withOption('rowCallback', rowCallback)
                    .withOption('order', order)
                    .withLanguage(datatables.ptbr)
                    .withBootstrap();

            function createdRow(row, data, dataIndex) {
                $compile(angular.element(row).contents())(vm);
            }

            function rowCallback(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $('.remover', nRow).unbind('click');
                $('.remover', nRow).bind('click', function () {
                    evtRemover(aData, remover);
                });

                $('.editar', nRow).unbind('click');
                $('.editar', nRow).bind('click', function () {
                    carregarObjeto(aData);
                });
            }

            function evtRemover(aData, remover) {
                $.confirm({
                    text: "Você tem certeza que deseja remover?",
                    title: "Confirmação",
                    confirm: function (button) {
                        remover(aData);
                    },
                    confirmButtonClass: "btn-danger btn-flat",
                    cancelButtonClass: "btn-default btn-flat",
                    confirmButton: "Sim, tenho certeza!",
                    cancelButton: "Não",
                    dialogClass: "modal-dialog modal-lg"
                });
            }
        }

        function recarregarDados(dtInstance) {
            var resetPaging = false;
            dtInstance.reloadData(null, resetPaging);
        }
    }

})();