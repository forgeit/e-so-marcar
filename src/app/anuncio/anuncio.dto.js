(function () {

    'use strict';

    angular
            .module('app.pessoa')
            .factory('pessoaDto', dto);

    function dto() {
        var service = {
            criarAtualizar: criarAtualizar,
            criarCarregar: criarCarregar
        };

        return service;

        function criarAtualizar(objeto) {
            console.log(objeto);

            var dto = {};

            dto.nome = objeto.nome;
            dto.email = objeto.email;

            if (objeto.cnpj) {
                dto.cnpj = objeto.cnpj;
            }

            if (objeto.numero) {
                dto.numero = objeto.numero;
            }

            if (objeto.cpf) {
                dto.cpf = objeto.cpf;
            }

            if (objeto.telefone) {
                dto.telefone = objeto.telefone;
            }

            if (objeto.celular) {
                dto.celular = objeto.celular;
            }

            if (objeto.cidade) {

                if (objeto.cidade.id_cidade) {
                    dto.cidade = objeto.cidade.id_cidade;
                } else {
                    dto.cidade = objeto.cidade;
                }

            }

            dto.fgTipoPessoa = objeto.fgTipoPessoa;

            if (objeto.bairro) {

                if (objeto.bairro.id_bairro) {
                    dto.bairro = objeto.bairro.id_bairro;
                } else {
                    dto.bairro = objeto.bairro;
                }

            }

            if (objeto.logradouro) {

                if (objeto.logradouro.id_logradouro) {
                    dto.logradouro = objeto.logradouro.id_logradouro;
                } else {
                    dto.logradouro = objeto.logradouro;
                }

            }

            if (objeto.tipoPessoa) {

                if (objeto.tipoPessoa.id_tipo_pessoa) {
                    dto.tipoPessoa = objeto.tipoPessoa.id_tipo_pessoa;
                } else {
                    dto.tipoPessoa = objeto.tipoPessoa;
                }

            }

            if (objeto.observacao) {
                dto.observacao = objeto.observacao;
            }

            return dto;
        }

        function criarCarregar(objeto) {
            var dto = objeto;

            dto.fgTipoPessoa = objeto.fg_tipo_pessoa == 1 ? 'F' : 'J';
            delete dto.fg_tipo_pessoa;

            if (objeto.fgTipoPessoa == 'F') {

                if (objeto.cpf_cnpj) {
                    dto.cpf = objeto.cpf_cnpj;
                    delete dto.cpf_cnpj;
                }

            } else if (objeto.fgTipoPessoa == 'J') {

                if (objeto.cpf_cnpj) {
                    dto.cnpj = objeto.cpf_cnpj;
                    delete dto.cpf_cnpj;
                }

            }

            dto.tipoPessoa = {id_tipo_pessoa: objeto.id_tipo_pessoa};
            delete dto.id_tipo_pessoa;

            if (objeto.id_cidade) {
                dto.cidade = {id_cidade: objeto.id_cidade};
                delete dto.id_cidade;
            }

            if (objeto.id_bairro) {
                dto.bairro = {id_bairro: objeto.id_bairro};
                delete dto.id_bairro;
            }

            if (objeto.id_logradouro) {
                dto.logradouro = {id_logradouro: objeto.id_logradouro};
                delete dto.id_logradouro;
            }

            return dto;
        }
    }
})();