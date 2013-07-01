<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dadaxr
 * Date: 26/06/13
 * Time: 23:27
 * To change this template use File | Settings | File Templates.
 */

namespace MiniMVC\Controllers;

use MiniMVC\Conf;

class Controller {


    /**
     * @var Request
     */
    public $request;

    /**
     * @var Bool : permet d'activer ou de desactiver le rendu automatique d'une action de controller via le bootstrap
     */
    public $auto_render = true;

    private $_view_css = array();
    private $_view_js = array();
    private $_view_vars = array();
    private $_layout = "default"; //fichier layout utilisé par défaut
    private $_allready_rendered = false;

    /**
     * @param Request $request : l'objet request initialisé par la class Router à partir de l'url
     */
    function __construct(\MiniMVC\Request $request){
        $this->request = $request;
        //appel la méthode sur la classe Controller uniquement
        //self::_afterConstruct();
        //appel la méthode sur la classe héritée si celle ci est présente, sinon appel la méthode sur la classe Controller
        $this->_afterConstruct();
    }

    protected function _afterConstruct(){
    }

    /**
     * Permet de définir les variables qui seront passées à la vue
     * @param mixed $key : soit un string correspondant au nom de la variable
     *                     (à ce moment là il faut définir la valeur avec le paramètre $value)
     *                     soit un array de clés => valeurs
     * @param null $value
     */
    public function set($key,$value=null){
        if(is_array($key)){
            $this->_view_vars += $key;
        }else{
            $this->_view_vars[$key] = $value;
        }
    }

    /**
     * permet de définir un fichier javascript à charger à la volée
     * @param $path_to_js
     */
    public function js($path_to_js){
        $this->_view_js[] = $path_to_js.'.js';
    }

    /**
     * permet de définir un fichier css à charger à la volée
     * @param $path_to_css
     */
    public function css($path_to_css){
        $this->_view_css[] = $path_to_css.'.css';
    }

    /**
     * permet de définir le layout utilisé
     * @param string $layout : nom du layout utilisé ( dans le dossier /app/layouts/ )
     */
    public function layout($layout){
        $this->_layout = $layout;
    }

    /**
     * Permet de générer le rendu d'une vue ( ex "/dossier_specifique/nom_de_ma_vue" , ou "nom_de_ma_vue" )
     * @param string $view soit une vue spécifique si ce paramètre commence par un "/", soit une vue située dans le repertoire lié au controlleur courant
     * @return bool
     */
    public function render($view){
        if($this->_allready_rendered == true) {return false;}

        if(strpos($view,'/') === 0){
            $view_path = APP.DS.'views'.DS.$view.'.phtml';
        }else{
            $view_path = APP.DS.'views'.DS.$this->request->controller.DS.$view.'.phtml';
        }

        extract($this->_view_vars);

        ob_start();
        require($view_path);
        $layout_content = ob_get_clean();
        $layout_path = APP.DS.'layouts'.DS.$this->_layout.'.phtml';
        require $layout_path;
        $this->_allready_rendered = true;
        return true;
    }

    /**
     * Charge un model dans le controlleur
     * @param $model_name
     */
    public function loadModel($model_name){
        $models_path = APP.DS.'models'.DS;
        $full_path = $models_path.$model_name.'.php';
        require_once($full_path);
        $fq_model_name = NS_MODELS.$model_name;
        if(!isset($this->$model_name)){
            $this->$model_name = new $fq_model_name();
        }
    }

    public function show404($message){
        header('HTTP/1.0 404 Not Found');
        $this->set('error_msg', $message);
        $this->render('/Errors/404');
        die();
    }

    public function redirect($url, $internal = true){
        if($internal){
            /*@TODO revoir l'utilisation de REQUEST_SCHEME, car pas toujours pertinent*/
            $full_url = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].BASE_URL.'/'.$url;
        }else{
            $full_url = $url;
        }
        header('Location: '.$full_url);
    }

}