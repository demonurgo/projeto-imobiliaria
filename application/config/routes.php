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
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['profile'] = 'users/profile';
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
$route['default_controller'] = 'auth';
$route['profile'] = 'users/profile';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Rotas para o módulo de importação XML
$route['importacao'] = 'xml';
$route['importacao/upload'] = 'xml/upload';
$route['importacao/revisar/(:any)'] = 'xml/review/$1';
$route['importacao/editar/(:num)'] = 'xml/edit/$1';
$route['importacao/excluir/(:num)'] = 'xml/delete/$1';
$route['importacao/concluir/(:any)'] = 'xml/complete_import/$1';
$route['importacao/diagnostico'] = 'xml/diagnostico';

// Rotas para o módulo de Notas Fiscais
$route['notas'] = 'notas';
$route['notas/view/(:num)'] = 'notas/view/$1';
$route['notas/edit/(:num)'] = 'notas/edit/$1';
$route['notas/delete/(:num)'] = 'notas/delete/$1';
$route['notas/dimob/(:num)/(:num)'] = 'notas/dimob/$1/$2';

// Rotas para o módulo de Inquilinos
$route['inquilinos'] = 'inquilinos';
$route['inquilinos/create'] = 'inquilinos/create';
$route['inquilinos/edit/(:num)'] = 'inquilinos/edit/$1';
$route['inquilinos/view/(:num)'] = 'inquilinos/view/$1';
$route['inquilinos/delete/(:num)'] = 'inquilinos/delete/$1';

// Rotas para o módulo de Imóveis
$route['imoveis'] = 'imoveis';
$route['imoveis/create'] = 'imoveis/create';
$route['imoveis/edit/(:num)'] = 'imoveis/edit/$1';
$route['imoveis/view/(:num)'] = 'imoveis/view/$1';
$route['imoveis/delete/(:num)'] = 'imoveis/delete/$1';
$route['imoveis/filter_by_inquilino'] = 'imoveis/filter_by_inquilino';
