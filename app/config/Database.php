<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dadaxr
 * Date: 29/06/13
 * Time: 18:46
 * To change this template use File | Settings | File Templates.
 */

namespace MiniMVC\Conf;


class Database {

    public static $databases = array(
        'default' => array(
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'database' => 'medialibs',
            'user' => 'root',
            'password' => 'mysql',
        )
    );
}