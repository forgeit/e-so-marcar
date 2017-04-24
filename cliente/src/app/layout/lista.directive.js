(function () {

    'use strict';

    angular
            .module('cliente.layout')
            .directive('lista', lista);

    function lista() {
        var directive = {
            restrict: 'E',
            templateUrl: 'cliente/src/app/layout/lista.html'
        };

        return directive;
    }

})();