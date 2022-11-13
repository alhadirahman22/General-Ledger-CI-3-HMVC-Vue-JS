<?php

defined('BASEPATH') or exit('No direct script access allowed');
// print_r(Date('YmdHis'));
// die();



$route['default_controller'] = 'dashboard';
$route['dashboard'] = 'dashboard';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


$route['select2'] = 'c_select2';
$route['select2/withfilterMuseum'] = 'c_select2/select2withfilterMuseum';
$route['select2/initialselect'] = 'c_select2/initialselect';

$route['unsetsession/(:any)'] = 'c_session/unsetsession/$1';

// user management
$route['administration/user_management/(:any)'] = 'administration/$1';
$route['administration/user_management/(:any)/(:any)'] = 'administration/$1/$2';
$route['administration/user_management/(:any)/(:any)/(:any)'] = 'administration/$1/$2/$3';


// approval
$route['administration/approval/(:any)'] = 'administration/$1';
$route['administration/approval/(:any)/(:any)'] = 'administration/$1/$2';
$route['administration/approval/(:any)/(:any)/(:any)'] = 'administration/$1/$2/$3';


$route['master/attribute/(:any)'] = 'master/$1';
$route['master/attribute/(:any)/(:any)'] = 'master/$1/$2';
$route['master/attribute/(:any)/(:any)/(:any)'] = 'master/$1/$2/$3';
// $route['master/attribut/(:any)/(:any)/(:any)'] = 'master/$1/$2/$3/$4';

$route['finance/coa/(:any)'] = 'finance/$1';
$route['finance/coa/(:any)/(:any)'] = 'finance/$1/$2';
$route['finance/coa/(:any)/(:any)/(:any)'] = 'finance/$1/$2/$3';
$route['finance/coa/(:any)/(:any)/(:any)/(:any)'] = 'finance/$1/$2/$3/$4';
