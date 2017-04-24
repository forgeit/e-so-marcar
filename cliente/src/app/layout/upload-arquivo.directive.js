(function () {

    'use strict';

    angular
            .module('cliente.layout')
            .directive('uploadArquivo', uploadArquivo);

    function uploadArquivo() {
        var directive = {
            restrict: 'E',
            templateUrl: 'cliente/src/app/layout/upload-arquivo.html'
        };

        return directive;
    }

})();