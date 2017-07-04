(function () {
    'use strict';

    angular
            .module('app.home')
            .controller('Home', Home);

    Home.$inject = ['homeRest', 'controllerUtils'];

    function Home(homeRest, controllerUtils) {

        var vm = this;
        
        buscarReservas();

        function buscarReservas() {
            homeRest.buscarReservas().then(success).catch(error);

            function success(response) {
                $('#calendar').fullCalendar('addEventSource', controllerUtils.getData(response, 'ArrayList'));
            }

            function error() {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar os reserva.');
            }
        }

    }
})();