<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dadaxr
 * Date: 26/06/13
 * Time: 23:15
 * To change this template use File | Settings | File Templates.
 */

namespace MiniMVC;

define('NS_CONTROLLERS',  __NAMESPACE__.'\\Controllers\\');
define('NS_MODELS',  __NAMESPACE__.'\\Models\\');

/**
 * Class Bootstrap
 * @package MiniMVC
 */
class Bootstrap {

    /**
     * @var Request
     */
    public $request = null;

    /**
     * @var Controller
     */
    public $controller = null;


    function __construct()
    {
        $this->_initRequest();
        $this->loadController($this->request->controller);
        $this->_run();
    }

    /**
     * charge et instancie le controlleur
     * @param $controller_name
     */
    function loadController($controller_name){
        $controllers_path = APP.DS.'controllers'.DS;
        $full_path = $controllers_path.$controller_name.'.php';
        if(!file_exists($full_path)){
            $error_msg = 'Le controlleur "'.$controller_name.'" n\'existe pas';
            $this->show404($error_msg);
        }
        require_once($full_path);
        $fq_controller_name = NS_CONTROLLERS.$controller_name;
        $this->controller = new $fq_controller_name($this->request);
    }

    /**
     * @param $message message d'erreur a afficher dans la page 404
     */
    public function show404($message){
        $this->controller = new Controllers\Controller($this->request);
        $this->controller->show404($message);
    }

    /**
     * instancie l'objet request et l'initialise en parsant l'url
     */
    private function _initRequest(){
        $this->request = new Request();
        Router::parse($this->request->url, $this->request);
    }

    /**
     * appel la méthode du controlleur en lui passant les bon paramètres de l'url
     */
    private function _run(){
        $controller_has_the_method = in_array($this->request->action, get_class_methods($this->controller));
        if($controller_has_the_method == false){
            $error_msg = 'le controller "'.$this->request->controller.'" n\'a pas d\'action "'.$this->request->action.'"';
            $this->show404($error_msg);
        }

        call_user_func_array(
            array(
                $this->controller,
                $this->request->action
            ),
            $this->request->params
        );

        if($this->controller->auto_render == true){
            $this->controller->render($this->request->action);
        }

    }




}