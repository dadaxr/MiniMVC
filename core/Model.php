<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dadaxr
 * Date: 27/06/13
 * Time: 00:43
 * To change this template use File | Settings | File Templates.
 */

namespace MiniMVC\Models;

use MiniMVC\Conf;

class Model {

    /**
     * @var \PDO : connexion courante vers une bdd
     */
    public $db = null;

    /**
     * @var string : nom de la configuration de bdd utilisé ( voir config/Database.php )
     */
    public $db_conf_key = 'default';

    /**
     * contient la liste des connexions à la bdd
     * @var array
     */
    public static $list_pdo_cnx = array();

    /**
     * @var string : nom de la table utilisée par le model ( si false, alors detection automatique à partir du nom du model )
     */
    public $table = false;


    protected $_list_drivers_dsn = array(
        'mysql' => 'mysql:host=%s;dbname=%s'
    );

    function __construct()
    {
        $this->_initPdoCnx();
        $this->_initTable();
    }

    private function _initTable(){
        if($this->table == false){
            //permet de récupérer le nom de la classe sans les informations relatives aux namespaces
            $model_class_name = end(explode('\\', get_class($this)));
            $this->table = strtolower($model_class_name);
        }
    }

    private function _initPdoCnx(){
        if(!isset(self::$list_pdo_cnx[$this->db_conf_key])){
            $db_conf = Conf\Database::$databases[$this->db_conf_key];

            $dsn = null; //DataSourceName
            $pdo_drivers_options = array();
            try{
                switch($db_conf['driver']){
                    case 'mysql':
                        $dsn = sprintf($this->_list_drivers_dsn[$db_conf['driver']],$db_conf['host'], $db_conf['database'] );
                        $pdo_drivers_options[\PDO::MYSQL_ATTR_INIT_COMMAND] = "SET NAMES utf8";
                        $pdo = new \PDO(
                            $dsn,
                            $db_conf['user'],
                            $db_conf['password'],
                            $pdo_drivers_options
                        );
                        $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
                        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                        self::$list_pdo_cnx[$this->db_conf_key] = $pdo;
                        break;
                }
            }catch(\PDOException $e){
                if(Conf\Debug::$debug_lvl >= 1){
                    echo $e->getMessage();
                    die();
                }else{
                    echo "Une erreur est survenue lors de la connexion à la base de données";
                    die();
                }
            }
        }else{
            $pdo = self::$list_pdo_cnx[$this->db_conf_key];
        }
        $this->db = $pdo;
    }

    /**
     * Récupère les enregistrements de la bdd pour le model courant, selon certaines conditions
     * @param array $params
     * @return array
     */
    public function findAll(array $params = array()){
        $list_query_parts[] = 'SELECT * FROM `'.$this->table.'`';
        if(isset($params['where'])){
            $list_query_parts[] = ' WHERE ';
            if(is_array($params['where'])){
                $list_condition_parts = array();
                //on itère sur chaque paramètre et on le protège
                foreach($params['where'] as $key => &$value){
                    $this->db->quote($value);
                    $list_condition_parts[] = $key.'"'.$value.'"';
                }
                $condition_str = implode(' AND ', $list_condition_parts);
                $list_query_parts[] = $condition_str;
            }else{
                $list_query_parts[] = $params['where'];
            }

        }
        if(isset($params['order']) && is_array($params['order'])){
            $list_order_parts = array();
            foreach($params['order'] as $order_col => $order_type){
                $list_order_parts[] = '`'.$order_col.'` '.strtoupper($order_type);
            }

            $list_query_parts[] = " ORDER BY ";
            $list_query_parts[] = implode(', ', $list_order_parts);
        }
        if(isset($params['limit'])){
            $list_query_parts[] = ' LIMIT 0,'.$params['limit'].' ';
        }
        $query_str = implode('',$list_query_parts);
        $query = $this->db->query($query_str);
        $result = $query->fetchAll();
        return $result;
    }

    /**
     * fonction générique permettant de gérer la sauvegarde d'un model, si le champs "id" est passé dans le tableau de
     * paramètres, alors on considère qu'il s'agit d'un UPDATE
     * @param array $fields
     * @return string
     */
    public function save(array $fields){
        //si le champs "id" est non vide, alors on est en mode "update"
        $update_mode = !empty($fields['id']);

        if($update_mode){
            $list_query_parts[] = 'UPDATE ';
        }else{
            unset($fields['id']); //permet d'empecher la tentative d'insertion d'une chaine vide
            $list_query_parts[] = 'INSERT INTO ';
        }

        $list_query_parts[] = ' `'.$this->table.'` SET ';

        $list_set_parts = array();
        foreach($fields as $name => $value){
            $list_set_parts[] = ' `'.$name.'` = '.$this->db->quote($value).' ';
        }
        $list_query_parts[] = implode(',',$list_set_parts);

        if($update_mode){
            $list_query_parts[] = ' WHERE `id` = '.$this->db->quote($fields['id']).' ';
        }

        $query_str = implode('',$list_query_parts);
        $nb_altered_rows = $this->db->exec($query_str);

        if(!$update_mode){
            $fields['id'] = $this->db->lastInsertId();
        }else{
            $fields['id'];
        }
        return $fields['id'];
    }

    public function remove(array $params){
        $list_query_parts[] = 'DELETE FROM `'.$this->table.'`';
        if(isset($params['where'])){
            $list_query_parts[] = ' WHERE ';
            if(is_array($params['where'])){
                $list_condition_parts = array();
                //on itère sur chaque paramètre et on le protège
                foreach($params['where'] as $key => &$value){
                    $this->db->quote($value);
                    $list_condition_parts[] = $key.'"'.$value.'"';
                }
                $condition_str = implode(' AND ', $list_condition_parts);
                $list_query_parts[] = $condition_str;
            }else{
                $list_query_parts[] = $params['where'];
            }

            $query_str = implode('',$list_query_parts);
            $nb_altered_rows = $this->db->exec($query_str);
            return $nb_altered_rows;

        }else{
            //protection empechant de remove tous les enregistrement si aucune condition n'est fournie
            return false;
        }
    }

}