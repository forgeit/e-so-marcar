(function () {
    'use strict';

    angular
            .module('core.authApp2')
            .factory('AuthTokenApp2', AuthTokenApp2);

    AuthTokenApp2.$inject = ['$sessionStorage'];

    function AuthTokenApp2($sessionStorage) {
        var service = {
            remover: remover,
            ler: ler,
            setar: setar
        };

        return service;

        function remover() {
            delete $sessionStorage.tokenApp2;
        }

        function ler() {
            return $sessionStorage.tokenApp2;
        }

        function setar(tokenApp2) {
            $sessionStorage.tokenApp2 = tokenApp2;
        }
    }
})();	