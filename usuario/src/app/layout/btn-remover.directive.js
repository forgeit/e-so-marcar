(function () {

    'use strict';

    angular
            .module('usuario.layout')
            .directive('btnRemover', btnRemover);

    function btnRemover() {
        var directive = {
            restrict: 'E',
            templateUrl: 'usuario/src/app/layout/btn-remover.html'
        };

        return directive;
    }

})();