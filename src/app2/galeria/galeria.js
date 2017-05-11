(function () {
    'use strict';

    angular
            .module('app.galeria')
            .controller('Galeria', Galeria);

    Galeria.$inject = ['controllerUtils', 'galeriaRest'];

    function Galeria(controllerUtils, galeriaRest) {

        var vm = this;

    }
})();