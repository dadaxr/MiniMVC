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
 * Class Entry
 * @package MiniMVC\Controllers
 * @property \MiniMVC\Models\Entry Entry
 * @property \MiniMVC\Models\Category Category
 */
class Entry extends Controller{


    protected function _afterConstruct(){
        $this->loadModel('Entry');
    }

    public function show(){

        $list_entries_with_categories = $this->Entry->getAllWithCategories();
        $this->set('list_entries_with_categories', $list_entries_with_categories);
    }

    public function edit($entry_id = null){
        if(!empty($_POST['entry'])){
            $entry_id = $this->Entry->save($_POST['entry']);
            $list_categories_id = empty($_POST['categories_id']) ? array() : explode(',',$_POST['categories_id']);
            $this->Entry->saveLinkedCategories($entry_id, $list_categories_id);
            $this->redirect('entry/show');
        }else{
            if(!empty($entry_id)){
                $find_params = array(
                    'where' => array(
                        'id =' => $entry_id
                    )
                );
                $list_entries = $this->Entry->findAll($find_params);
                if(!empty($list_entries)){
                    $entry = current($list_entries);
                }
            }else{
                $entry = false;
            }

            $this->loadModel('Category');
            $list_categories_json = json_encode(array_values($this->Category->getTreeCategoriesForEntry($entry_id)));

            $this->set('list_categories_json', $list_categories_json);
            $this->set('entry', $entry);
        }
    }

    public function remove($entry_id){
        $remove_params = array(
            'where' => array(
                'id =' => $entry_id
            )
        );
        $this->Entry->remove($remove_params);
        $this->redirect('entry/show');
    }
}