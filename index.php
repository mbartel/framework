<?php

/**
 * Author: Michael Bartel <Michael.Bartel@gmx.net>
 */
require_once 'config/config.php';

error_reporting(E_ALL & ~E_NOTICE);
date_default_timezone_set(TIMEZONE);
setlocale(LC_ALL, LANGUAGE);

$CLASSPATH = array('lib/', 'lib/db/');

include ROOT . 'inc.functions.php';
include ROOT . 'lib/smarty/Smarty.class.php';

Session::init();

Template::setTemplate('overview');

Template::display();
