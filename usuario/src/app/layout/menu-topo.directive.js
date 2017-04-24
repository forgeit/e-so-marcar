(function () {

    'use strict';

    angular
            .module('usuario.layout')
            .directive('menuTopo', menuTopo);

    function menuTopo() {
        var directive = {
            restrict: 'E',
            templateUrl: 'usuario/src/app/layout/menu-topo.html'
        };

        return directive;
    }

})();