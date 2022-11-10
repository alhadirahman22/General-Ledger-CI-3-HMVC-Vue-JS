<?php
defined('BASEPATH') or exit('No direct script access allowed');
require_once BASEPATH . 'dotenv/autoloader.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$dotenv = new Dotenv\Dotenv(FCPATH);
$dotenv->load();

$active_group = 'default';
$active_record = TRUE;

// $db['default']['hostname'] = 'localhost';
// $db['default']['username'] = 'root';
// $db['default']['password'] = '';
// $db['default']['database'] = 'smc';

$db['default']['hostname'] = empty(getenv('_DB_HOST')) ?     'localhost'        : getenv('_DB_HOST');
$db['default']['username'] = empty(getenv('_DB_USER')) ?     'root'      : getenv('_DB_USER');
$db['default']['password'] = empty(getenv('_DB_PASSWORD')) ? ''      : getenv('_DB_PASSWORD');
$db['default']['database'] = empty(getenv('_DB_NAME')) ?     'sanaksys' : getenv('_DB_NAME');

// $db['default']['port'] = empty(getenv('_DB_PORT')) ?     '1433' : getenv('_DB_PORT');
$db['default']['dbdriver'] = 'mysqli'; // support with MYSQl,POSTGRE SQL, ORACLE,SQL SERVER
// $db['default']['dbdriver'] = 'sqlsrv';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['encrypt'] = TRUE;
$db['default']['compress'] = TRUE;
$db['default']['stricton'] = FALSE;
$db['default']['failover'] = array();
$db['default']['save_queries'] = TRUE;


// for eloquent
$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'mysql',
    // 'driver' => 'sqlsrv',
    'host' => $db['default']['hostname'],
    'database' => $db['default']['database'],
    'username' => $db['default']['username'],
    'password' => $db['default']['password'],
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',

]);

// Set the event dispatcher used by Eloquent models... (optional)
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

$capsule->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

/* End of file database.php */
/* Location: ./application/config/database.php */
