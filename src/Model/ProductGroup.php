<?php

namespace SlcShop\Model;

class ProductGroup
{
    public $id;

    public $data;

    public $fieldCount;

    public $paramsData = [];

    public function __construct($id = null, Array $data = [])
    {
        if (empty($id) && empty($data)) {
            throw  new \Exception('Not enough params in ProductGroup constructor');
        }

        global  $wpdb;
        if (!empty($id)) {
            $this->id = $id;
            $table_name = $wpdb->prefix . "ol_items";
            $sql = "SELECT * FROM " . $table_name . " WHERE id=" . $this->id;
            $this->data = $wpdb->get_row($sql, ARRAY_A);
        } else {
            $this->data = $data;
            $this->id = $data['id'];
        }

        $table_name = $wpdb->prefix."ol_params";
        $sql="SELECT * FROM ".$table_name." WHERE item_id=". $this->id;
        $this->paramsData = $wpdb->get_results($sql, ARRAY_A);
        $this->fieldCount = count($this->paramsData);
    }

    public function getId()
    {
        return $this->data['id'];
    }

    public function getSlug()
    {
        return $this->data['slug'] ?? null;
    }

    public function getTitle()
    {
        return $this->data['title'] ?? null;
    }

    public function getTaxonomy()
    {
        return $this->data['taxonomy'] ?? null;
    }

    public function getTaxonomySlug()
    {
        return $this->data['taxonomy_slug'] ?? null;
    }

    public function getCategory()
    {

        if (empty($this->data['category'])) {
            return null;
        }

        $category = get_category($this->data['category']);
        if (empty($category)) {
            return null;
        }

        return $category->name;
    }

    public function editLink()
    {
        return 'admin.php?page=' . dirname(plugin_basename(ORDER_LIST_PLUGIN_FILE)) . '/items/items.php&item_action=edit_item&item_id=' . $this->id;
    }

    public function deleteLink()
    {
        return 'admin.php?page=' . dirname(plugin_basename(ORDER_LIST_PLUGIN_FILE)) . '/items/items.php&item_action=delete_item&item_id=' . $this->id;
    }

    public function categoryList()
    {
        $cat_args = array(
            'show_option_all' => '',
            'show_option_none' => '',
            'orderby' => 'ID',
            'order' => 'ASC',
            'show_count' => 0,
            'hide_empty' => 0,
            'child_of' => 0,
            'exclude' => '',
            'echo' => 1,
            'selected' => $this->getCategory(),
            'hierarchical' => 0,
            'name' => 'item_category',
            'id' => '',
            'class' => 'postform',
            'depth' => 0,
            'tab_index' => 0,
            'taxonomy' => 'category',
            'hide_if_empty' => false
        );

        wp_dropdown_categories($cat_args);
    }
}