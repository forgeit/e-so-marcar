(function () {

    'use strict';

    angular.module('app.core', [
        'ngRoute',
        'ngCpfCnpj',
        'ui.toggle',
        'ui.mask',
        'ui.utils.masks',
        'datatables',
        'datatables.bootstrap',
        'core.utils',
        'angular-loading-bar',
        'ngStorage',
        'angular-jwt',
        'core.auth']);

})();