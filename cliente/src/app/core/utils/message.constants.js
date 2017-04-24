(function () {
	'use strict';

	var defaultMessage = {
		EMPTY_DATA: 'Não foi possível se conectar ao serviço.',
		SUCCESS: 'Sucesso ao efetuar a operação.',
		ERROR: 'Ocorreu um erro ao efetuar a operação.'
	};

	var messageType = {
		INFO: "info",
		SUCCESS: "success",
		WARNING: "warning",
		ERROR: "error"
	};

	angular
		.module('core.utils')
		.constant('DefaultMessage', defaultMessage)
		.constant('MessageType', messageType);
		
})();