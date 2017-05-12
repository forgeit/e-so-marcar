(function () {

    'use strict';

    angular
            .module('app.layout')
            .directive('menuTopo', menuTopo);

    function menuTopo() {
        var directive = {
            restrict: 'E',
            templateUrl: 'src/app2/layout/menu-topo.html'
        };
        
        console.log('directive');

        return directive;
    }

})();