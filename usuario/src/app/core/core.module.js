(function () {

    'use strict';

    angular.module('usuario.core', [
        'ngRoute',
        'ngCpfCnpj',
        'ui.mask',
        'datatables',
        'datatables.bootstrap',
        'core.utils',
        'angular-loading-bar',
        'ngStorage',
        'angular-jwt',
        'core.auth']);

})();