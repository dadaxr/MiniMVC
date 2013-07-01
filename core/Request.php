<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dadaxr
 * Date: 26/06/13
 * Time: 23:21
 * To change this template use File | Settings | File Templates.
 */

namespace MiniMVC;

class Request {

    public $controller = 'Page';
    public $action = 'index';
    public $params = array();
    public $url;

    function __construct()
    {
        $this->url = $_SERVER['PATH_INFO'];
    }



}