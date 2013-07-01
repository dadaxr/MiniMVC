<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dadaxr
 * Date: 26/06/13
 * Time: 23:27
 * To change this template use File | Settings | File Templates.
 */

namespace MiniMVC\Controllers;

class Page extends Controller {

    public function index(){
    }

    public function about(){
    }

    public function action($p1 = null,$p2 = null){
        print_r($p1);
        print_r($p2);

        $this->set("mavar","mavaleur");
        $this->set(array("mavar2" => "mavaleur2"));
        $this->render('index');
    }

}