<?php

/**
 * Author: Michael Bartel <Michael.Bartel@gmx.net>
 */
require_once 'config/config.php';
Session::init();



Template::setTemplate('overview');

Template::display();
