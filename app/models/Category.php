<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dadaxr
 * Date: 01/07/13
 * Time: 00:48
 * To change this template use File | Settings | File Templates.
 */

namespace MiniMVC\Models;

class Category extends Model {


    public function getTreeCategoriesForEntry($entry_id = null){
        $list_tree_categories = $this->getAllTreeCategories();

        if(!empty($entry_id)){
            $query_str = "SELECT fk_category_id FROM `a_category_entry` WHERE fk_entry_id = ".$this->db->quote($entry_id);
            $query = $this->db->query($query_str);
            $result = $query->fetchAll();
            if(!empty($result)){
                foreach($result as $a_row){
                    $list_tree_categories[$a_row['fk_category_id']]['checked'] = true;
                }
            }
        }
        return $list_tree_categories;
    }

    public function getAllTreeCategories(){
        $list_tree_categories = array();
        $find_params = array(
            'order' => array('order' => 'ASC')
        );
        $list_categories = $this->findAll($find_params);


        foreach($list_categories as $a_category){
            $a_category['html_id'] = "cat_".$a_category['id'];
            $a_category['checked'] = false;
            $list_tree_categories[$a_category['id']] = $a_category;
        }

        return $list_tree_categories;
    }

    public function removeCategories($list_categories_id){
        if(empty($list_categories_id)) return;
        $query_str = 'DELETE FROM `category` WHERE id IN ('.implode(',',$list_categories_id).')';
        $nb_altered_rows = $this->db->exec($query_str);
        return $nb_altered_rows;
    }
}