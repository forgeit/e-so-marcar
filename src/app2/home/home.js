(function () {
    'use strict';

    angular
            .module('app.home')
            .controller('Home', Home);

    Home.$inject = ['controllerUtils', 'homeRest'];

    function Home(controllerUtils, homeRest) {

        var vm = this;

        vm.areaCliente = areaCliente;

        function areaCliente() {
            controllerUtils.$window.location.href = 'index-cliente.html';
        }

    }
})();