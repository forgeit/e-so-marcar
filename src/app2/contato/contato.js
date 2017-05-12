(function () {
    'use strict';

    angular
            .module('app.contato')
            .controller('Contato', Contato);

    Contato.$inject = ['controllerUtils', 'contatoRest'];

    function Contato(controllerUtils, contatoRest) {

        var vm = this;

    }
})();