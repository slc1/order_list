<?php


namespace SlcShop\Model;


class ProductParam
{
    public $id;

    public $data;

    public function __construct($id = null, Array $data = [])
    {
        if (empty($id) && empty($data)) {
            throw  new \Exception('Not enough params in ProductParam constructor');
        }

        global  $wpdb;
        if (!empty($id)) {
//            $this->id = $id;
//            $table_name = $wpdb->prefix . "ol_items";
//            $sql = "SELECT * FROM " . $table_name . " WHERE id=" . $this->id;
//            $this->data = $wpdb->get_row($sql, ARRAY_A);
        } else {
            $this->data = $data;
            $this->id = $data['id'];
        }
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

    public function getType()
    {
        return $this->data['type'] ?? null;
    }

    public function getIgroup()
    {
        return $this->data['igroup'] ?? null;
    }

    public function getOrderBy()
    {
        return $this->data['order_by'] ?? null;
    }

    public function getToColumn()
    {
        return $this->data['to_column'] ?? null;
    }

    public function getToFilter()
    {
        return $this->data['to_filter'] ?? null;
    }
}