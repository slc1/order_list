<?php
/*
Plugin Name: Order List
Plugin URI: http://slc.org.ua/
Description:Список заказов - плагин для управления списком заказов веб-магазина
Version: 1.0.0
Author: SLC
Author http://slc.org.ua/
*/


define( 'ORDER_LIST_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ORDER_LIST_PLUGIN_SLUG', 'order-list');

add_option('currency_course', 8);
if (!isset($pluginprefix))
    $pluginprefix = "";
$pluginprefix .= "order_list/";
$plugintitle = "Order list";

include("ol_prg/ol_prg.php");

function order_list()
{

}

function order_list_install()
{
    ol_prg_install();
}


function order_list_uninstall()
{
    ol_prg_uninstall();
}

register_activation_hook(__FILE__, 'order_list_install');
register_deactivation_hook(__FILE__, 'order_list_uninstall');


function order_list_items_install()
{

    //create tables for items
    global $wpdb;
    $table_name = $wpdb->prefix . "ol_items";

    $wpdb->get_results("SHOW TABLES LIKE '" . $table_name . "'");
    if ($wpdb->num_rows != 1) {
        $sql = "CREATE TABLE " . $table_name . " ( " .
            "id INT NOT NULL AUTO_INCREMENT, " .
            "title VARCHAR(100) NOT NULL, " .
            "slug VARCHAR(100) NOT NULL, " .
            "taxonomy VARCHAR(100) NOT NULL, " .
            "taxonomy_slug VARCHAR(100) NOT NULL, " .
            "category int NOT NULL, " .
            "PRIMARY KEY ( id ))  CHARSET=utf8; ";

        if (!$wpdb->query($sql)) {
            die('Could not create table: ');
        }

    }

    $table_name = $wpdb->prefix . "ol_backet";
    $wpdb->get_results("SHOW TABLES LIKE '" . $table_name . "'");
    if ($wpdb->num_rows != 1) {
        $sql = "CREATE TABLE " . $table_name . " ( " .
            "id INT NOT NULL AUTO_INCREMENT, " .
            "user_hash VARCHAR(100) NOT NULL, " .
            "user_ip VARCHAR(30) NOT NULL, " .
            "user_time DATETIME NOT NULL, " .
            "item_id INT NOT NULL, " .
            "post_id INT NOT NULL, " .
            "my_item_id INT NOT NULL, " .
            "item_group INT NOT NULL, " .
            "item_count INT NOT NULL, " .
            "item_price FLOAT NOT NULL, " .
            "PRIMARY KEY ( id ))  CHARSET=utf8; ";

        if (!$wpdb->query($sql)) {
            die('Could not create table: ');
        }
    }


    $table_name = $wpdb->prefix . "ol_params";

    $wpdb->get_results("SHOW TABLES LIKE '" . $table_name . "'");
    if ($wpdb->num_rows != 1) {
        $sql = "CREATE TABLE " . $table_name . " ( " .
            "id INT NOT NULL AUTO_INCREMENT, " .
            "item_id INT NOT NULL, " .
            "title VARCHAR(100) NOT NULL, " .
            "slug VARCHAR(100) NOT NULL, " .
            "caption VARCHAR(200), " .
            "type VARCHAR(100) NOT NULL, " .
            "igroup INT , " .
            "default_value VARCHAR(1000), " .
            "to_column BOOL , " .
            "order_by BOOL , " .
            "to_filter BOOL , " .
            "prm1 BOOL , " .
            "prm2 BOOL , " .
            "PRIMARY KEY ( id ))  CHARSET=utf8; ";

        if (!$wpdb->query($sql)) {
            die('Could not create table: ');
        }

    }
}


function order_list_items_uninstall()
{

}

register_activation_hook(__FILE__, 'order_list_items_install');
register_deactivation_hook(__FILE__, 'order_list_items_uninstall');


function order_list_create_submenu()
{
    $pluginname = "order_list";
    if (function_exists('add_menu_page')) {
        wp_enqueue_style("order_list", plugins_url('order_list.css', __FILE__), false, " ");
        add_menu_page("Магазин", "Магазин", 'edit_posts', ORDER_LIST_PLUGIN_SLUG, function(){ include(ORDER_LIST_PLUGIN_DIR . '/ol_prg/ol_prg.php');}, 'dashicons-cart');
        if (function_exists('add_submenu_page')) {
            //add_submenu_page('order-list', "Список заказов", "Список заказов", 'edit_posts', dirname(plugin_basename(__FILE__)) . '/ol_prg/ol_prg.php');
            add_submenu_page(ORDER_LIST_PLUGIN_SLUG, "Параметры товара", "Параметры товара", 'edit_posts', dirname(plugin_basename(__FILE__)) . '/items/items.php');
            add_submenu_page(ORDER_LIST_PLUGIN_SLUG, "Настройки корзины", "Корзина", 'edit_posts', dirname(plugin_basename(__FILE__)) . '/delivery/delivery.php');
            add_submenu_page(ORDER_LIST_PLUGIN_SLUG, "Параметры экспорта импорта через CSV", "Экспорт-импорт", 'edit_posts', dirname(plugin_basename(__FILE__)) . '/csv/csv.php');
            add_submenu_page(ORDER_LIST_PLUGIN_SLUG, "Курс валют", "Курс валют", 'edit_posts', dirname(plugin_basename(__FILE__)) . '/currency/currency.php');
        }

    }
}

add_action('admin_menu', 'order_list_create_submenu');

include('items_post_types.php');
include('items_custom_box.php');

