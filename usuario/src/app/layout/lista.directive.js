(function () {

    'use strict';

    angular
            .module('usuario.layout')
            .directive('lista', lista);

    function lista() {
        var directive = {
            restrict: 'E',
            templateUrl: 'usuario/src/app/layout/lista.html'
        };

        return directive;
    }

})();