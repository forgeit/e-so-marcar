(function () {
    'use strict';

    angular
            .module('core.utils')
            .factory('messageUtils', messageUtils);

    messageUtils.$inject = ['DefaultMessage', 'MessageType', 'toastr'];

    function messageUtils(DefaultMessage, MessageType, toastr) {
        var service = {
            feedMessage: feedMessage,
            feed: feed,
            type: MessageType
        };

        initToastr();

        return service;

        function feed(type, message) {
            toastr[type](message);
        }

        function feedDefault(executed) {
            if (executed) {
                toastr[MessageType.SUCCESS](DefaultMessage.SUCCESS);
            } else {
                toastr[MessageType.ERROR](DefaultMessage.ERROR);
            }
        }

        function feedMessage(response) {
            var data = response.data || {};

            if (angular.equals({}, data)) {
                toastr[MessageType.ERROR](DefaultMessage.EMPTY_DATA);
                return;
            }

            var message = data.message || [];

            if (message.length > 0) {
                angular.forEach(message, function (value, key) {
                    toastr[value.tipo.toLowerCase()](value.mensagem);
                });
            } else {
                var executed = typeof data.executed === 'boolean' ? data.executed : false;
                feedDefault(executed);
            }
        }

        function initToastr() {
            toastr.options.timeOut = 3000;
            toastr.options.progressBar = true;
            toastr.options.closeButton = true;
            toastr.options.positionClass = 'toast-bottom-right';
            toastr.options.preventDuplicates = true;
        }
    }

})();