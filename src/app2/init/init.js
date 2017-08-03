(function () {
    'use strict';

    angular
            .module('app.init')
            .controller('InitController', InitController);

    InitController.$inject = ['controllerUtils', 'initRest', 'AuthTokenApp2', 'jwtHelper', '$rootScope', 'vcRecaptchaService', 'Facebook', '$scope'];

    function InitController(controllerUtils, dataService, AuthTokenApp2, jwtHelper, $rootScope, vcRecaptchaService, Facebook, $scope) {

        var vm = this;

        vm.areaCliente = areaCliente;
        vm.newsletter = newsletter;
        vm.cadastrar = cadastrar;
        vm.senha = senha;
        vm.submit = submit;

        function areaCliente() {
            controllerUtils.$window.location.href = 'index-cliente.html';
        }

        function newsletter() {

            dataService.newsletter(vm.newsletterForm).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao registrar o e-mail.');
            }

            function success(response) {
                controllerUtils.feedMessage(response);
                if (response.data.status === 'true') {
                    vm.newsletterForm.email = null;
                }
            }
        }

        function cadastrar() {

            dataService.cadastrar(vm.cadastro).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao cadastrar.');
            }

            function success(response) {
                if (response.data.status === 'true') {
                    $('#myModalTwo').modal('hide');
                    setTimeout(function () {
                        $('#modalMessage').modal('show');
                    }, 500);
                } else {
                    controllerUtils.feedMessage(response);
                }
            }
        }

        function senha(email) {

            dataService.senha(email).then(success).catch(error);

            function error(response) {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Erro ao recuperar senha.');
            }

            function success(response) {
                if (response.data.status === 'true') {
                    $('#myModal').modal('hide');
                    $('#myModalTwo').modal('hide');
                }
                controllerUtils.feedMessage(response);
            }
        }

        vm.response = null;
        vm.widgetId = null;

        vm.model = {
            key: '6LfuQycUAAAAADiRK_lhH80niSyIwdoOtdTXYTDU'
        };

        vm.setResponse = function (response) {
            vm.response = response;
        };

        vm.setWidgetId = function (widgetId) {
            vm.widgetId = widgetId;
        };

        vm.cbExpiration = function () {
            vcRecaptchaService.reload(vm.widgetId);
            vm.response = null;
        };

        function submit() {

            if (vm.response) {
                vm.login.hash = vm.response;
                dataService.logar(vm.login).then(success).catch(error);
            } else {
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'O reCaptcha não está resolvido.');
            }

            function error(response) {
                vcRecaptchaService.reload(vm.widgetId);
                controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao acessar o sistema.');
            }

            function success(response) {
                vcRecaptchaService.reload(vm.widgetId);
                if (response.data.status === 'true') {
                    AuthTokenApp2.setar(response.data.data.token);

                    var payload = jwtHelper.decodeToken(response.data.data.token);
                    $rootScope.usuarioSistema = {};
                    $rootScope.usuarioSistema.id = payload.id;
                    $rootScope.usuarioSistema.nome = payload.nome;
                    $rootScope.usuarioSistema.email = payload.email;
                    $rootScope.usuarioSistema.nomeExibir = ((payload.nome) ? payload.nome : payload.email);

                    vm.login = {};

                    $('#myModal').modal('hide');
                }

                $('#loading-bar-container').html('<div id="loader-wrapper"><h4><img style="width: 150px;" src="src/app2/layout/img/logo-lg.png" /><br/><img src="src/app2/layout/img/loader.gif"/></h4></div>');
                setTimeout(function () {
                    $('#loading-bar-container').html('');
                }, 500);
                setTimeout(function () {
                    controllerUtils.feedMessage(response);
                }, 600);
            }

        }

        $scope.$watch(
                function () {
                    return Facebook.isReady();
                },
                function (newVal) {
                    if (newVal)
                        $scope.facebookReady = true;
                }
        );

        $rootScope.userIsConnected = false;

        Facebook.getLoginStatus(function (response) {
            if (response.status == 'connected') {
                $rootScope.userIsConnected = true;
            }
        });

        vm.IntentLogin = function () {
            if (!$rootScope.userIsConnected) {
                login();
            } else {
                me();
            }
        };

        function login() {
            Facebook.login(function (response) {
                if (response.status == 'connected') {
                    me();
                }
            });
        };

        function me() {
            Facebook.api('/me', function (response) {
                /**
                 * Using $scope.$apply since this happens outside angular framework.
                 */
                
                $scope.$apply(function () {
                    vm.user = response;

                    dataService.logarFace(response).then(success).catch(error);

                    function success(response) {
                        if (response.data.status === 'true') {
                            AuthTokenApp2.setar(response.data.data.token);

                            var payload = jwtHelper.decodeToken(response.data.data.token);
                            $rootScope.userIsConnected = true;
                            $rootScope.usuarioSistema = {};
                            $rootScope.usuarioSistema.id = payload.id;
                            $rootScope.usuarioSistema.nome = payload.nome;
                            $rootScope.usuarioSistema.email = payload.email;
                            $rootScope.usuarioSistema.nomeExibir = ((payload.nome) ? payload.nome : payload.email);

                            vm.login = {};

                            $('#myModal').modal('hide');
                        }


                        $('#loading-bar-container').html('<div id="loader-wrapper"><h4><img style="width: 150px;" src="src/app2/layout/img/logo-lg.png" /><br/><img src="src/app2/layout/img/loader.gif"/></h4></div>');
                        setTimeout(function () {
                            $('#loading-bar-container').html('');
                        }, 500);
                        setTimeout(function () {
                            controllerUtils.feedMessage(response);
                        }, 600);
                    }

                    function error(response) {
                        controllerUtils.feed(controllerUtils.messageType.ERROR, 'Ocorreu um erro ao acessar o sistema com o facebook.');
                    }
                });

            });
        };
        
    }
})();