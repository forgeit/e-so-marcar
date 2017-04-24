(function () {
    'use strict';

    angular
            .module('core.auth')
            .factory('AuthToken', AuthToken);

    AuthToken.$inject = ['$sessionStorage'];

    function AuthToken($sessionStorage) {
        var service = {
            remover: remover,
            ler: ler,
            setar: setar
        };

        return service;

        function remover() {
            delete $sessionStorage.token;
        }

        function ler() {
            return $sessionStorage.token;
        }

        function setar(token) {
            $sessionStorage.token = token;
        }
    }
})();	