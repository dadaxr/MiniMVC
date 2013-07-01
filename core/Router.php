<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dadaxr
 * Date: 27/06/13
 * Time: 00:40
 * To change this template use File | Settings | File Templates.
 */

namespace MiniMVC;


class Router {

    /**
     * @param $url
     * @param Request $request
     */
    static function parse($url, Request $request){
        if(!empty($url)){
            $url = trim($url,'/');
            $list_url_parts = explode('/',$url);
            $request->controller = ucfirst($list_url_parts[0]);
            if(!empty($list_url_parts[1])){
                $request->action = $list_url_parts[1];
            }
            $request->params = array_slice($list_url_parts,2);
        }
    }

}