create table pessoa
(
        id_pessoa integer primary key auto_increment,
        nome varchar(255) not null,
        email varchar(255),
        cpf_cnpj varchar(255),
        telefone varchar(255),
        celular varchar(255),
        observacao text,
        id_cidade integer,
        id_bairro integer,
        id_logradouro integer,
        foreign key (id_cidade) references cidade (id_cidade),
        foreign key (id_bairro) references bairro (id_bairro),
        foreign key (id_logradouro) references logradouro (id_logradouro)
);

create table tipo_pessoa
(
        id_tipo_pessoa integer primary key auto_increment,
        descricao varchar(255) not null
);

insert into tipo_pessoa
(id_tipo_pessoa, descricao)
values
(1, 'Eleitor'),
(2, 'Órgão Público'),
(3, 'Outros');

alter table pessoa add column id_tipo_pessoa integer;
alter table pessoa add foreign key (id_tipo_pessoa) references tipo_pessoa (id_tipo_pessoa);

create table situacao 
(
        id_situacao integer primary key,
        descricao varchar(255) not null
);

insert into situacao
values
(1, 'Demanda Iniciada'),
(2, 'Demanda Em Análise'),
(3, 'Demanda Em Andamento'),
(4, 'Demanda Enviada Para Terceiros'),
(5, 'Demanda Não Resolvida'),
(6, 'Demanda Resolvida');

create table demanda
(
        id_demanda integer primary key auto_increment,
        titulo varchar(255) not null,
        descricao text,
        id_tipo_demanda integer not null,
        dt_criacao date not null,
        dt_contato date not null,
        prazo_final date,
        id_situacao integer not null, 
        foreign key (id_tipo_demanda) references tipo_demanda (id_tipo_demanda),
        foreign key (id_situacao) references situacao (id_situacao)
);

create table demanda_arquivo
(
        id_demanda_arquivo integer primary key auto_increment,
        id_demanda integer,
        arquivo varchar(255),
        foreign key (id_demanda) references demanda (id_demanda)
);

create table demanda_fluxo
(
        id_demanda_fluxo integer primary key auto_increment,
        id_demanda integer not null,
        id_pessoa integer,
        descricao text,
        foreign key (id_demanda) references demanda (id_demanda),
        foreign key (id_pessoa) references pessoa (id_pessoa)
);

create table demanda_arquivo_fluxo
(
        id_demanda_arquivo_fluxo integer primary key auto_increment,
        id_demanda_fluxo integer not null,
        arquivo varchar(255),
        foreign key (id_demanda_fluxo) references demanda_fluxo (id_demanda_fluxo)
);

alter table demanda add column id_solicitante integer;
alter table demanda add foreign key (id_solicitante) references pessoa (id_pessoa);