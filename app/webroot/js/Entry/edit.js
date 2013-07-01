/**
 * Created with JetBrains PhpStorm.
 * User: dadaxr
 * Date: 29/06/13
 * Time: 02:13
 * To change this template use File | Settings | File Templates.
 */
(function($) {

    var $categories_tree =  $('#categories_tree_container');

    $(document).ready(function() {
        initFormHandlers();
        buildCategoriesTree();
        initTreeBtn();
    });

    function initFormHandlers(){
        $('#form_entries').on('submit',function(){
            //permet de tenir comptes des catégories selectionnées avant de submit le formulaire
            processSelectedCategories();
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
            processSelectedCategories();
        });

        $categories_tree.jqxTree({
            source: records,
            /*width: '300px',*/
            checkboxes: true,
            hasThreeStates: false,
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

    function processSelectedCategories(){
        var items = $categories_tree.jqxTree('getCheckedItems');
        var list_checked_categories_id = [];
        var item;
        for(var i in items){
            item = items[i];
            list_checked_categories_id.push(item.value);
        }
        $('#ih_categories_id').val(list_checked_categories_id.join(','));
    }



})(jQuery);