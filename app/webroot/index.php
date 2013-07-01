<?php

define('DS', DIRECTORY_SEPARATOR);
define('WEBROOT', __DIR__);
define('APP', dirname(WEBROOT));
define('CORE', dirname(APP).DS.'core');
define('BASE_URL', dirname(dirname(dirname($_SERVER['SCRIPT_NAME']))));

require_once(CORE.DS.'includes.php');
