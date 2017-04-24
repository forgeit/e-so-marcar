(function () {

    'use strict';

    angular
            .module('cliente.layout')
            .directive('btnEditar', btnEditar);

    function btnEditar() {
        var directive = {
            restrict: 'E',
            templateUrl: 'cliente/src/app/layout/btn-editar.html'
        };

        return directive;
    }

})();