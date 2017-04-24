(function () {

    'use strict';

    angular
            .module('usuario.layout')
            .directive('uploadArquivo', uploadArquivo);

    function uploadArquivo() {
        var directive = {
            restrict: 'E',
            templateUrl: 'usuario/src/app/layout/upload-arquivo.html'
        };

        return directive;
    }

})();