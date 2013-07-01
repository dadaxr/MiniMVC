<?php
/**
 * Created by JetBrains PhpStorm.
 * User: dadaxr
 * Date: 29/06/13
 * Time: 17:58
 * To change this template use File | Settings | File Templates.
 */

namespace MiniMVC\Models;

class Entry extends Model {

    public function getAllWithCategories(){
        $query_str = '
            SELECT e.*,
            c.id AS category_id,
            c.title AS category_title,
            c.order AS category_order,
            c.parent_id AS category_parent_id
            FROM `entry` AS e
            LEFT JOIN `a_category_entry` AS a_c_e ON a_c_e.fk_entry_id = e.id
            LEFT JOIN `category` AS c ON c.id = a_c_e.fk_category_id
        ';
        $query = $this->db->query($query_str);
        $results = $query->fetchAll();
        $list_results = array();
        foreach($results as $a_row){
            $formated_row = array(
                "entry" => array(
                    "id" => $a_row['id'],
                    "title" => $a_row['title'],
                    "description" => $a_row['description'],
                ),
                "category" => array(
                    "id" => $a_row['category_id'],
                    "title" => $a_row['category_title'],
                    "order" => $a_row['category_order'],
                    "parent_id" => $a_row['category_parent_id'],
                )
            );
            if(!isset($list_results[$formated_row['entry']['id']]['entry'])){
                $list_results[$formated_row['entry']['id']]['entry'] = $formated_row['entry'];
                $list_results[$formated_row['entry']['id']]['categories'] = array();
            }
            if(!empty($formated_row['category']['id'])){
                $list_results[$formated_row['entry']['id']]['categories'][$formated_row['category']['id']] = $formated_row['category'];
            }
        }
        return $list_results;
    }

    public function saveLinkedCategories($entry_id, array $list_categories_id ){
        $entry_id = $this->db->quote($entry_id);
        $query_str = 'DELETE FROM `a_category_entry` WHERE fk_entry_id = '.$entry_id;
        $this->db->exec($query_str);

        if(!empty($list_categories_id)){
            $list_insert_values = array();
            foreach($list_categories_id as $category_id){
                $list_insert_values[] = '('.$entry_id.','.$this->db->quote($category_id).')';
            }
            $query_str = 'INSERT INTO `a_category_entry` (fk_entry_id, fk_category_id) VALUES '.implode(' , ', $list_insert_values);
            $this->db->exec($query_str);
        }
    }
}