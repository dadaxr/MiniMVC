/**
 * Created with JetBrains PhpStorm.
 * User: dadaxr
 * Date: 01/07/13
 * Time: 04:36
 * To change this template use File | Settings | File Templates.
 */
(function($) {

    var $categories_tree =  $('#categories_tree_container');
    var $ti_selected_category_title = $('#ti_category_title');
    var $trigger_update_selected = $('#trigger_update_selected');
    var $trigger_remove_selected = $('#trigger_remove_selected');
    var $trigger_add = $('#trigger_add');

    $(document).ready(function() {
        initFormHandlers();
        buildCategoriesTree();
        initTreeBtn();
    });

    function initFormHandlers(){
        $('#form_categories').on('submit',function(){
            serializeCategories();
        });

        $trigger_update_selected.on('click',function(){
            var item = $categories_tree.jqxTree('getSelectedItem');
            if(!item){return;}
            var new_label = $ti_selected_category_title.val();
            $categories_tree.jqxTree('updateItem',item,{label: new_label});
        });

        $trigger_remove_selected.on('click',function(){
            var item = $categories_tree.jqxTree('getSelectedItem');
            if(!item){return;}
            $categories_tree.jqxTree('removeItem',item);
        });

        $trigger_add.on('click',function(){
            var new_label = "Categorie-"+Math.floor(Math.random()*101);
            $categories_tree.jqxTree('addTo', { label: new_label });
        });

    }

    function buildCategoriesTree(){

        var categories_json = $('#categories_tree_container').data('categoriesJson');
        var source = {
            datatype: "json",
            datafields: [
                { name: 'id', type:'int' },
                { name: 'title', type:'string' },
                { name: 'parent_id', type:'int' },
                { name: 'html_id', type:'string' },
                { name: 'checked', type:'bool' }
            ],
            id: 'id',
            localdata: categories_json
        };

        // create data adapter.
        var dataAdapter = new $.jqx.dataAdapter(source);
        dataAdapter.dataBind();

        // get the tree items. The first parameter is the item's id.
        // The second parameter is the parent item's id.
        // The 'items' parameter represents the sub items collection name.*
        // Each jqxTree item has a 'label' property, but in the JSON data, we have a 'text' field.
        // The last parameter specifies the mapping between the json source and tree source fields.
        var map_json_to_html = [
            { name: 'title', map: 'label'},
            { name: 'checked', map: 'checked' },
            { name: 'id', map: 'value' }, /*attention à l'ordre, car il peut y'avoir des bugs si cette ligne là est placé en dessous la suivante.*/
            { name: 'html_id', map: 'id' }
        ];
        var records = dataAdapter.getRecordsHierarchy('id', 'parent_id', 'items', map_json_to_html);
        $categories_tree.on('initialized', function (event){
        });

        $categories_tree.on('select', function (event){
            var item = $categories_tree.jqxTree('getSelectedItem');
            $ti_selected_category_title.val(item.label);
        });

        $categories_tree.jqxTree({
            source: records,
            /*width: '300px',*/
            height: '300px',
            allowDrag: true,
            /*allowDrop: true,*/
            theme: "bootstrap"
        });
    }

    function initTreeBtn(){
        $('#trigger_expand_all').click(function(){
            $categories_tree.jqxTree('expandAll');
        });

        $('#trigger_collapse_all').click(function(){
            $categories_tree.jqxTree('collapseAll');
        });
    }

    function serializeCategories(){
        var list_items = $categories_tree.jqxTree('getItems');
        var item, parent_id, order, is_new, category_id;
        var order_by_parent = {"null":0};
        var list_categories = [];
        for(var i in list_items){
            item = list_items[i];
            parent_id = null;
            if(item.level > 0){
                parent_id = item.parentId.split('_').pop();
            }
            order = order_by_parent[parent_id]++;

            /*petite gymnastique permettant de conserver la notion de parenté même pour les nouveaux éléments*/
            if(item.value){
                category_id = item.value;
                is_new = false;
            }else{
                category_id = item.id
                is_new = true;
            }
            list_categories.push({
                id: category_id,
                title: item.label,
                parent_id: parent_id,
                order: order,
                is_new: is_new
            });
        }

        $('#serialized_categories').val(JSON.stringify(list_categories));
    }

})(jQuery);