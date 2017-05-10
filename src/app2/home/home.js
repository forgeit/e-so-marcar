(function () {
    'use strict';

    angular
            .module('app.home')
            .controller('Home', Home);

    Home.$inject = ['homeRest'];

    function Home(homeRest) {

        var vm = this;

    }
})();