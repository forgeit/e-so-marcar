(function () {

    'use strict';

    angular
            .module('cliente.layout')
            .directive('btnRemover', btnRemover);

    function btnRemover() {
        var directive = {
            restrict: 'E',
            templateUrl: 'cliente/src/app/layout/btn-remover.html'
        };

        return directive;
    }

})();