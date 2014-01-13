<?php

define('ROOT', str_replace('config', '', __DIR__));
define('BASE_URL', 'http://cubus/smarthome/');
define('PAGETITLE', 'SmartHome');
define('REWRITE_PARAM', 'p');

define('TEMPLATE_DIR', ROOT . 'templates/');
define('TEMPLATE_COMPILE_DIR', ROOT . 'tmp/');
define('TEMPLATE_CACHE', ROOT . 'tmp/');

define('TIMEZONE', 'Europe/Berlin');
define('LANGUAGE', 'de_DE');
define('SESSIONTIMEOUT', 60 * 30);

$USERS = array(
  array(
    'email' => 'Michael.Bartel@gmx.net',
    'password' => '7c1d0e13a018257a6f35ca0477ca002f',
    'name' => 'Michael Bartel'
  )
);