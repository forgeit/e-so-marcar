(function () {
    'use strict';

    angular
            .module('app.sobre')
            .controller('Sobre', Sobre);

    Sobre.$inject = ['controllerUtils', 'sobreRest'];

    function Sobre(controllerUtils, sobreRest) {

        var vm = this;

    }
})();