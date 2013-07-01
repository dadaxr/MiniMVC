<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dadaxr
 * Date: 29/06/13
 * Time: 00:27
 * To change this template use File | Settings | File Templates.
 */

namespace MiniMVC\Controllers;

/**
 * Class Category
 * @package MiniMVC\Controllers
 * @property \MiniMVC\Models\Category Category
 */
class Category extends Controller{


    public function manage(){
        $this->loadModel('Category');

        if(!empty($_POST['serialized_categories'])){
            $list_categories = json_decode($_POST['serialized_categories'], true);
            $list_existing_categories = array();
            $list_new_categories = array();
            foreach($list_categories as $a_cat){
                $is_new = $a_cat['is_new'] ? true : false;
                unset($a_cat['is_new']);
                if($is_new){
                    $list_new_categories[$a_cat['id']] = $a_cat;
                }else{
                    $list_existing_categories[$a_cat['id']] = $a_cat;
                }
            }
            $this->_removeDeleted(array_keys($list_existing_categories));

            /*
             * 1ere etape on met à jours les categories existantes,
             * il y'aurai moyen d'optimiser coté client en detectant quelles sont les categories ayant subies des modifications
             * */
            foreach($list_existing_categories as $a_cat){
                $this->Category->save($a_cat);
            }

            /*2eme étape on ajoutes en bdd les nouvelles catégories*/
            $map_tmp_id__new_id = array();
            foreach($list_new_categories as $a_cat){
                $tmp_id = $a_cat['id'];
                unset($a_cat['id']);
                if(!empty($a_cat['parent_id'])){
                    $a_cat['parent_id'] = $map_tmp_id__new_id[$a_cat['parent_id']];
                }
                $new_id = $this->Category->save($a_cat);
                $map_tmp_id__new_id[$tmp_id] = $new_id;
            }
        }

        $list_categories_json = json_encode(array_values($this->Category->getAllTreeCategories()));
        $this->set('list_categories_json', $list_categories_json);
    }


    private function _removeDeleted($list_existing_categories_id){
        if(empty($list_existing_categories_id)) return;
        $query_str = 'SELECT id FROM `category` WHERE id NOT IN('.implode(',',$list_existing_categories_id).')';
        $query = $this->Category->db->query($query_str);
        $results = $query->fetchAll();
        foreach($results as $a_row){
            $list_deleted_categories_id[] = $a_row['id'];
        }
        $this->Category->removeCategories($list_deleted_categories_id);
    }

}