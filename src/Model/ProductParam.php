<?php


namespace SlcShop\Model;


class ProductParam
{
    public $id;

    public $data;

    public function __construct($idOrSlug = null, Array $data = [])
    {
        if (empty($idOrSlug) && empty($data)) {
            throw  new \Exception('Not enough params in ProductParam constructor');
        }


        if (!empty($idOrSlug)) {
            global $wpdb;
            $table_name = $wpdb->prefix . "ol_params";
            if (is_int($idOrSlug)) {
                $sql = "SELECT * FROM " . $table_name . " WHERE id=" . $idOrSlug;
            } else {
                $sql = "SELECT * FROM " . $table_name . " WHERE slug='" . $idOrSlug . "'";
            }
            $this->data = $wpdb->get_row($sql, ARRAY_A);
        } else {
            $this->data = $data;
        }

        $this->id = $this->data['id'];
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