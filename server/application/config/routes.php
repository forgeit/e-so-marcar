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

$route['pessoa'] = 'pessoa/buscarTodos';
$route['pessoa/tipo-pessoa/(:num)'] = 'pessoa/buscarTodos/$1';
$route['pessoa/combo'] = 'pessoa/buscarCombo';
$route['pessoa/filtro'] = 'pessoa/buscarComboFiltro';
$route['pessoa/(:num)'] = 'pessoa/buscar';
$route['pessoa/excluir/(:num)'] = 'pessoa/excluir/$1';
$route['pessoa/atualizar/(:num)'] = 'pessoa/atualizar/$1';

$route['tipo-anuncio'] = 'anuncioTipo/buscarTodos';
$route['anuncio'] = 'anuncio/buscarTodos';
$route['anuncio/(:num)'] = 'anuncio/buscar/$1';

$route['tipo-local'] = 'localTipo/buscarTodos';
$route['tipo-esporte'] = 'esporteTipo/buscarTodos';
$route['tipo-quadra'] = 'quadraTipo/buscarTodos';
$route['quadra'] = 'quadra/buscarTodos';
$route['quadra/(:num)'] = 'quadra/buscar/$1';
$route['quadra/combo'] = 'quadra/buscarCombo';

$route['dia-semana/combo'] = 'diaSemana/buscarCombo';

$route['usuario/combo'] = 'usuario/buscarCombo';

$route['reserva'] = 'reserva/buscarTodos';
$route['reserva/(:num)'] = 'reserva/buscar/$1';
$route['reserva/buscar-valor'] = 'reserva/buscarHorarioReserva';

$route['horario'] = 'horario/buscarTodos';
$route['horario/(:num)'] = 'horario/buscar/$1';

$route['excecao'] = 'excecao/buscarTodos';
$route['excecao/(:num)'] = 'excecao/buscar/$1';

$route['upload'] = 'upload/processar';
$route['login/entrar'] = 'login/entrar';
$route['usuario/alterar-senha/(:num)'] = 'usuario/alterarSenha/$1';
