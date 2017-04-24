(function () {

    'use strict';

    angular
            .module('usuario.layout')
            .directive('btnEditar', btnEditar);

    function btnEditar() {
        var directive = {
            restrict: 'E',
            templateUrl: 'usuario/src/app/layout/btn-editar.html'
        };

        return directive;
    }

})();