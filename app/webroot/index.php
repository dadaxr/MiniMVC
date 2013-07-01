<?php

define('DS', DIRECTORY_SEPARATOR);
define('WEBROOT', __DIR__);
define('APP', dirname(WEBROOT));
define('CORE', dirname(APP).DS.'core');
$base_url = dirname(dirname(dirname($_SERVER['SCRIPT_NAME'])));
if($base_url == '\\'){
    $base_url = '';
}
define('BASE_URL', $base_url);

require_once(CORE.DS.'includes.php');
