(function () {

    'use strict';

    angular
            .module('cliente.layout')
            .directive('rodape', rodape);

    function rodape() {
        var directive = {
            restrict: 'E',
            templateUrl: 'cliente/src/app/layout/rodape.html'
        };

        return directive;
    }

})();