<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dadaxr
 * Date: 27/06/13
 * Time: 00:21
 * To change this template use File | Settings | File Templates.
 */

require_once(APP.DS.'config'.DS.'Debug.php');
require_once(APP.DS.'config'.DS.'Database.php');
require_once('Router.php');
require_once('Request.php');
require_once('Controller.php');
require_once('Model.php');
require_once('Bootstrap.php');


new \MiniMVC\Bootstrap();