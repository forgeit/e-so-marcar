(function () {

    'use strict';

    angular
            .module('usuario.layout')
            .directive('cabecalho', cabecalho);

    function cabecalho() {
        var directive = {
            restrict: 'E',
            templateUrl: 'usuario/src/app/layout/cabecalho.html'
        };

        return directive;
    }

})();