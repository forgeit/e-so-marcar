(function () {

    'use strict';

    angular
            .module('usuario.layout')
            .directive('rodape', rodape);

    function rodape() {
        var directive = {
            restrict: 'E',
            templateUrl: 'usuario/src/app/layout/rodape.html'
        };

        return directive;
    }

})();