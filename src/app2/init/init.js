(function () {
    'use strict';

    angular
            .module('app.init')
            .controller('InitController', InitController);

    InitController.$inject = ['controllerUtils'];

    function InitController(controllerUtils) {

        var vm = this;

        vm.areaCliente = areaCliente;

        function areaCliente() {
            controllerUtils.$window.location.href = 'index-cliente.html';
        }

    }
})();