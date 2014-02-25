<?php

define('ROOT', str_replace('config', '', __DIR__));
define('BASE_URL', 'http://cubus/framework/');
define('PAGETITLE', 'Framework');
define('REWRITE_PARAM', 'p');

define('TEMPLATE_DIR', ROOT . 'templates/');
define('TEMPLATE_COMPILE_DIR', ROOT . 'tmp/');
define('TEMPLATE_CACHE', ROOT . 'tmp/');

define('SESSIONTIMEOUT', 60 * 30);

error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set('Europe/Berlin');
setlocale(LC_ALL, 'de_DE');

$CLASSPATH = array('lib/', 'lib/db/');

include ROOT . 'inc.functions.php';
