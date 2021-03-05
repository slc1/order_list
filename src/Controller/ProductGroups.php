<?php

namespace SlcShop\Controller;

class ProductGroups
{

    public  $productGroupsData = [];

    public function __construct()
    {
        global  $wpdb;
        $table_name = $wpdb->prefix."ol_items";
        $sql="SELECT * FROM ".$table_name;
        $mysql_results = $wpdb->get_results($sql, ARRAY_A);
        foreach ($mysql_results as $mysql_result) {
            $this->productGroupsData[] = $mysql_result;
        }
    }
}