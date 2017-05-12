(function () {
    'use strict';

    angular
            .module('app.home')
            .controller('Home', Home);

    Home.$inject = ['controllerUtils', 'homeRest'];

    function Home(controllerUtils, dataservice) {

        var vm = this;

        vm.cadastrar = cadastrar;
        vm.areaCliente = areaCliente;
        vm.count = {
            quadras: 0,
            jogos: 0,
            jogadores: 0
        };

        buscar();

        function buscar() {
            dataservice.buscar().then(success).catch(error);

            function success(response) {
                var dados = controllerUtils.getData(response, 'dto');
                vm.count.quadras = dados[0];
                vm.count.jogos = dados[1];
                vm.count.jogadores = dados[2];
            }

            function error() {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Não foi possível carregar os contadores.');
            }

        }

        function cadastrar(formulario) {

            dataservice.cadastrar(vm.cadastro).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao cadastrar.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);
                if (response.data.status === 'true') {
                    
                }
            }
        }

        function areaCliente() {
            controllerUtils.$window.location.href = 'index-cliente.html';
        }

    }
})();