<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dadaxr
 * Date: 26/06/13
 * Time: 23:27
 * To change this template use File | Settings | File Templates.
 */

namespace MiniMVC\Controllers;

class Main extends Controller {


    function __construct(\MiniMVC\Request $request)
    {
        parent::__construct($request);
        $this->auto_render = false;
    }

    function index(){
        echo __METHOD__;
    }

    public function action(){
        var_dump(func_get_args());
    }

    public function phpinfo(){
        phpinfo();
    }

}