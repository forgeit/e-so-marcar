<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['endereco/cidade'] = 'endereco/cidade';
$route['endereco/bairro/(:num)'] = 'endereco/bairro/$1';
$route['endereco/logradouro/(:num)'] = 'endereco/logradouro/$1';
$route['tipo-pessoa'] = 'tipoPessoa/buscarTodos';
$route['pessoa'] = 'pessoa/buscarTodos';
$route['pessoa/tipo-pessoa/(:num)'] = 'pessoa/buscarTodos/$1';
$route['pessoa/combo'] = 'pessoa/buscarCombo';
$route['pessoa/filtro'] = 'pessoa/buscarComboFiltro';
$route['pessoa/(:num)'] = 'pessoa/buscar';
$route['pessoa/excluir/(:num)'] = 'pessoa/excluir/$1';
$route['pessoa/atualizar/(:num)'] = 'pessoa/atualizar/$1';
$route['tipo-demanda'] = 'tipoDemanda/buscarTodos';
$route['tipo-demanda/(:num)'] = 'tipoDemanda/buscar/$1';
$route['tipo-demanda/excluir/(:num)'] = 'tipoDemanda/excluir/$1';
$route['tipo-demanda/salvar'] = 'tipoDemanda/salvar';
$route['tipo-demanda/salvar/(:num)'] = 'tipoDemanda/salvar/$1';
$route['pessoa/salvar'] = 'pessoa/salvar';
$route['upload'] = 'upload/processar';
$route['login/entrar'] = 'login/entrar';
$route['demanda/salvar'] = 'demanda/salvar';
$route['demanda/atualizar/(:num)'] = 'demanda/atualizar/$1';
$route['demanda/(:num)/(:num)/(:num)'] = 'demanda/buscarPorData/$1/$2/$3';
$route['demanda-fluxo/salvar/(:num)'] = 'demandaFluxo/salvar/$1';
$route['demanda-fluxo/buscar-arquivos/(:num)'] = 'demandaFluxo/buscarArquivos/$1';
$route['demanda/remover/(:num)'] = 'demanda/remover/$1';
$route['demanda/buscar/(:num)'] = 'demanda/buscar/$1';
$route['demanda'] = 'demanda/buscarTodos';
//filtros
$route['demanda/tipo-demanda/(:num)'] = 'demanda/buscarTodos/$1';
$route['demanda/tipo-demanda/(:num)/situacao/(:num)'] = 'demanda/buscarTodos/$1/$2';
$route['demanda/situacao/(:num)'] = 'demanda/buscarTodos/$1';
$route['situacao/combo'] = 'situacao/buscarCombo';
$route['situacao'] = 'situacao/buscarTodos';
$route['usuario/alterar-senha/(:num)'] = 'usuario/alterarSenha/$1';
$route['logradouro/filtrar'] = 'logradouro/filtrar';
$route['logradouro/salvar'] = 'logradouro/salvar';
$route['ver-arquivo/demanda/(:num)/arquivo/(:num)'] = 'verArquivo/porDemanda/$1/$2';
$route['ver-arquivo/demanda-fluxo/(:num)/arquivo/(:num)'] = 'verArquivo/porDemandaFluxo/$1/$2';