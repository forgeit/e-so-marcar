(function () {

    'use strict';

    angular
            .module('cliente.layout')
            .directive('cabecalho', cabecalho);

    function cabecalho() {
        var directive = {
            restrict: 'E',
            templateUrl: 'cliente/src/app/layout/cabecalho.html'
        };

        return directive;
    }

})();