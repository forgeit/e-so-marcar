(function () {

    'use strict';

    angular
            .module('cliente.layout')
            .directive('menuTopo', menuTopo);

    function menuTopo() {
        var directive = {
            restrict: 'E',
            templateUrl: 'cliente/src/app/layout/menu-topo.html'
        };

        return directive;
    }

})();