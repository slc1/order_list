<?php

$non_allow_means = array(
    "attachment",
    "attachment_id",
    "author",
    "author_name",
    "calendar",
    "cat",
    "category",
    "category__and",
    "category__in",
    "category__not_in",
    "category_name",
    "comments_per_page",
    "comments_popup",
    "customize_messenger_channel",
    "customized",
    "cpage",
    "day",
    "debug",
    "error",
    "exact",
    "feed",
    "hour",
    "link_category",
    "m",
    "minute",
    "monthnum",
    "more",
    "name",
    "nav_menu",
    "nonce",
    "nopaging",
    "offset",
    "order",
    "orderby",
    "p",
    "page",
    "page_id",
    "paged",
    "pagename",
    "pb",
    "perm",
    "post",
    "post__in",
    "post__not_in",
    "post_format",
    "post_mime_type",
    "post_status",
    "post_tag",
    "post_type",
    "posts",
    "posts_per_archive_page",
    "posts_per_page",
    "preview",
    "robots",
    "s",
    "search",
    "second",
    "sentence",
    "showposts",
    "static",
    "subpost",
    "subpost_id",
    "tag",
    "tag__and",
    "tag__in",
    "tag__not_in",
    "tag_id",
    "tag_slug__and",
    "tag_slug__in",
    "taxonomy",
    "tb",
    "term",
    "theme",
    "type",
    "w",
    "withcomments",
    "withoutcomments",
    "year ",
);

function rus2translit($string)
{
    $converter = array(
        'а' => 'a', 'б' => 'b', 'в' => 'v',
        'г' => 'g', 'д' => 'd', 'е' => 'e',
        'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
        'и' => 'i', 'й' => 'y', 'к' => 'k',
        'л' => 'l', 'м' => 'm', 'н' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r',
        'с' => 's', 'т' => 't', 'у' => 'u',
        'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
        'ь' => "", 'ы' => 'y', 'ъ' => "",
        'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        'і' => 'i', 'ї' => 'yi', 'є' => 'e',

        ' ' => '_', '~' => '_', '`' => '_',
        '!' => '_', '@' => '_', '#' => '_',
        '$' => '_', '%' => '_', '^' => '_',
        '&' => '_', '*' => '_', '(' => '_',
        ')' => '_', '-' => '_', '+' => '_',
        '=' => '_', ':' => '_', ';' => '_',
        '"' => '_', "'" => '_', '.' => '_',
        ',' => '_', '\\' => '_', '/' => '_',
        '{' => '_', '}' => '_', '[' => '_',
        ']' => '_', '|' => '_', '<' => '_',
        '>' => '_',

        'А' => 'A', 'Б' => 'B', 'В' => 'V',
        'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
        'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
        'И' => 'I', 'Й' => 'Y', 'К' => 'K',
        'Л' => 'L', 'М' => 'M', 'Н' => 'N',
        'О' => 'O', 'П' => 'P', 'Р' => 'R',
        'С' => 'S', 'Т' => 'T', 'У' => 'U',
        'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
        'Ь' => "", 'Ы' => 'Y', 'Ъ' => "",
        'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        'І' => 'I', 'Ї' => 'Yi', 'Є' => 'E',
    );
    return strtr($string, $converter);
}

global $wpdb;

$item_action = (isset($_REQUEST["item_action"]) ? $_REQUEST["item_action"] : "");
$item_id = (isset($_REQUEST["item_id"]) ? $_REQUEST["item_id"] : "");
$field_id = (isset($_REQUEST["field_id"]) ? $_REQUEST["field_id"] : "");
$field_id1 = (isset($_REQUEST["field_id1"]) ? $_REQUEST["field_id1"] : "");

$item_title = (isset($_REQUEST["item_title"]) ? $_REQUEST["item_title"] : "");
$item_slug = (isset($_REQUEST["item_slug"]) ? $_REQUEST["item_slug"] : "");
$item_taxonomy = (isset($_REQUEST["item_taxonomy"]) ? $_REQUEST["item_taxonomy"] : "");
$item_taxonomy_slug = (isset($_REQUEST["item_taxonomy_slug"]) ? $_REQUEST["item_taxonomy_slug"] : "");
$item_category = (isset($_REQUEST["item_category"]) ? $_REQUEST["item_category"] : "");

$field_title = (isset($_REQUEST["field_title"]) ? $_REQUEST["field_title"] : "");
$field_caption = (isset($_REQUEST["field_caption"]) ? $_REQUEST["field_caption"] : "");
$field_type = (isset($_REQUEST["field_type"]) ? $_REQUEST["field_type"] : "");
$field_values = (isset($_REQUEST["field_values"]) ? $_REQUEST["field_values"] : "");
$field_group = (isset($_REQUEST["field_group"]) ? $_REQUEST["field_group"] : "");
$field_to_column = (isset($_REQUEST["field_to_column"]) ? $_REQUEST["field_to_column"] : 0);
$field_order_by = (isset($_REQUEST["field_order_by"]) ? $_REQUEST["field_order_by"] : 0);
$field_to_filter = (isset($_REQUEST["field_to_filter"]) ? $_REQUEST["field_to_filter"] : 0);
$field_prm1 = (isset($_REQUEST["field_prm1"]) ? $_REQUEST["field_prm1"] : 0);
$field_prm2 = (isset($_REQUEST["field_prm2"]) ? $_REQUEST["field_prm2"] : 0);
$is_rewrite_rules = (isset($_REQUEST["is_rewrite_rules"]) ? $_REQUEST["is_rewrite_rules"] : false);

if ($is_rewrite_rules) {
    flush_rewrite_rules();
    echo "<h2 style='color:red;'>Rules rewrited!!!</h2>";
}

//------------------------------------------------------------------
if ($item_action == '') include('items_list.php');
//------------------------------------------------------------------
if ($item_action == 'add_new_item') include('add_new_item.php');
//------------------------------------------------------------------  
if ($item_action == 'edit_item') include('edit_item.php');
//------------------------------------------------------------------  
if ($item_action == 'add_item_to_base') {


    $item_slug = substr($item_slug, 0, 20);
    if ($item_slug == "") $item_slug = substr(strtolower(rus2translit($item_title)), 0, 19);
    $i = 0;
    while (in_array($item_slug, $non_allow_means) || taxonomy_exists($item_slug) || post_type_exists($item_slug)) {
        $item_slug = substr(strtolower(rus2translit($item_slug)), 0, 17) . $i++;
    }

    $item_taxonomy_slug = substr($item_taxonomy_slug, 0, 15);
    if ($item_taxonomy_slug == "" and !$item_taxonomy == "") $item_taxonomy_slug = substr(strtolower(rus2translit($item_taxonomy)), 0, 15);

    if ($item_taxonomy_slug == $item_slug) $item_taxonomy_slug .= '_tax';
    $i = 0;
    while (in_array($item_taxonomy_slug, $non_allow_means) || taxonomy_exists($item_taxonomy_slug) || post_type_exists($item_taxonomy_slug)) {
        $item_taxonomy_slug = substr(strtolower(rus2translit($item_taxonomy_slug)), 0, 18) . $i++;
    }

    $table_name = $wpdb->prefix . "ol_items";
    $sql = "INSERT INTO " . $table_name . " (             " .
        "            title          , " .
        "            slug  ,          " .
        "            taxonomy     ,   " .
        "            taxonomy_slug ,  " .
        "            category   " .
        ") " .
        "VALUES     ('" . $item_title . "',     " .
        "            '" . $item_slug . "'," .
        "            '" . $item_taxonomy . "'," .
        "            '" . $item_taxonomy_slug . "'," .
        "            '" . $item_category . "')  ";

    if ($wpdb->query($sql) === false) {
        die('Error: ' . $wpdb->print_error() . "<br><br><br>SQL:<br><br>" . $sql);
    }

    $table_name = $wpdb->prefix . "ol_item_" . $wpdb->insert_id;

    $wpdb->get_results("SHOW TABLES LIKE '" . $table_name . "'");
    if ($wpdb->num_rows != 1) {
        $sql = "CREATE TABLE " . $table_name . " ( " .
            "id INT NOT NULL AUTO_INCREMENT, " .
            "post_id INT NOT NULL, " .
            "PRIMARY KEY ( id ))  CHARSET=utf8; ";

        if ($wpdb->query($sql) === false) {
            die('Error: ' . $wpdb->print_error() . "<br><br><br>SQL:<br><br>" . $sql);
        }
    }

    echo "<script>location.href='admin.php?page=" . dirname(plugin_basename(__FILE__)) . "/items.php&is_rewrite_rules=true';</script>";
    include('items_list.php');
}
//------------------------------------------------------------------  
if ($item_action == 'edit_item_in_base') {

    // if ($item_slug=="") $item_slug=strtolower(rus2translit($item_title));
    // if  ($item_taxonomy_slug=="" and !$item_taxonomy=="") $item_taxonomy_slug=strtolower(rus2translit($item_taxonomy)) ;

    $item_slug = substr($item_slug, 0, 20);
    if ($item_slug == "") {
        $item_slug = substr(strtolower(rus2translit($item_title)), 0, 19);
        $i = 0;
        while (in_array($item_slug, $non_allow_means) || taxonomy_exists($item_slug) || post_type_exists($item_slug)) {
            $item_slug = substr(strtolower(rus2translit($item_slug)), 0, 17) . $i++;
        }
    }

    $item_taxonomy_slug = substr($item_taxonomy_slug, 0, 15);
    if ($item_taxonomy_slug == $item_slug) $item_taxonomy_slug .= '_tax';

    if ($item_taxonomy_slug == "" and !$item_taxonomy == "") {
        $item_taxonomy_slug = substr(strtolower(rus2translit($item_taxonomy)), 0, 15);
        if ($item_taxonomy_slug == $item_slug) $item_taxonomy_slug .= '_tax';

        $i = 0;
        while (in_array($item_taxonomy_slug, $non_allow_means) || taxonomy_exists($item_taxonomy_slug) || post_type_exists($item_taxonomy_slug)) {
            $item_taxonomy_slug = substr(strtolower(rus2translit($item_taxonomy_slug)), 0, 18) . $i++;
        }
    }

    $table_name = $wpdb->prefix . "ol_items";


    $sql = 'UPDATE ' . $table_name . ' SET ' .
        'title         = "' . $item_title . '"    ,     ' .
        'slug          = "' . $item_slug . '"     ,     ' .
        'taxonomy      = "' . $item_taxonomy . '" ,     ' .
        'taxonomy_slug = "' . $item_taxonomy_slug . '",  ' .
        'category      = ' . $item_category . '  ' .
        'WHERE id = "' . $item_id . '"';

    if ($wpdb->query($sql) === false) {
        die('Error: ' . $wpdb->print_error() . "<br><br><br>SQL:<br><br>" . $sql);
    }

    include('items_list.php');
}
//------------------------------------------------------------------  
if ($item_action == 'delete_item') {

    $table_name = $wpdb->prefix . "ol_items";
    $sql = 'DELETE FROM ' . $table_name . ' WHERE id = "' . $item_id . '"';
    if ($wpdb->query($sql) === false) {
        die('Error: ' . $wpdb->print_error() . "<br><br><br>SQL:<br><br>" . $sql);
    }

    $table_name = $wpdb->prefix . "ol_params";
    $sql = 'DELETE FROM ' . $table_name . ' WHERE item_id = "' . $item_id . '"';
    if ($wpdb->query($sql) === false) {
        die('Error: ' . $wpdb->print_error() . "<br><br><br>SQL:<br><br>" . $sql);
    }

    $table_name = $wpdb->prefix . "ol_item_" . $item_id;
    $sql = 'DROP TABLE ' . $table_name;
    if ($wpdb->query($sql) === false) {
        die('Error: ' . $wpdb->print_error() . "<br><br><br>SQL:<br><br>" . $sql);
    }

    include('items_list.php');
}
//------------------------------------------------------------------  
if ($item_action == 'add_item_field') {
    include('add_item_field.php');
}
//------------------------------------------------------------------    
if ($item_action == 'add_item_field_to_base') {

    $field_slug = strtolower(rus2translit($field_title));

    // проверка поля дубликата
    $table_name = $wpdb->prefix . "ol_item_" . $item_id;
    $i = 0;
    $sql = "SHOW COLUMNS  FROM " . $table_name . " WHERE Field = '" . $field_slug . "'";
    $wpdb->get_results($sql);

    while ($wpdb->num_rows > 0) {
        $field_slug = strtolower(rus2translit($field_title)) . $i++;
        $sql = "SHOW COLUMNS  FROM " . $table_name . " WHERE Field = '" . $field_slug . "'";
        $wpdb->get_results($sql);
    }
    // конец проверки

    $table_name = $wpdb->prefix . "ol_params";
    $sql = "INSERT INTO " . $table_name . " (             " .
        "            item_id          , " .
        "            title          , " .
        "            slug          , " .
        "            caption  ,          " .
        "            type     ,   " .
        "            igroup     ,   " .
        "            default_value,  " .
        "            to_column,  " .
        "            to_filter,  " .
        "            prm1,  " .
        "            prm2,  " .
        "            order_by  " .
        ") " .
        "VALUES     ('" . $item_id . "',     " .
        "            '" . $field_title . "'," .
        "            '" . $field_slug . "'," .
        "            '" . $field_caption . "'," .
        "            '" . $field_type . "'," .
        "            '" . $field_group . "'," .
        "            '" . $field_values . "'," .
        "            " . $field_to_column . "," .
        "            " . $field_to_filter . "," .
        "            " . $field_prm1 . "," .
        "            " . $field_prm2 . "," .
        "            " . $field_order_by . " )  ";

    if ($wpdb->query($sql) === false) {
        die('Error: ' . $wpdb->print_error() . "<br><br><br>SQL:<br><br>" . $sql);
    }

    $table_name = $wpdb->prefix . "ol_item_" . $item_id;
    //ALTER TABLE contacts ADD email VARCHAR(60);

    $parsing_field_type = "VARCHAR(200)";
    if ($field_type == 'textarea') $parsing_field_type = "TEXT";
    if ($field_type == 'tinymce') $parsing_field_type = "TEXT";
    if ($field_type == 'number_int') $parsing_field_type = "INT";
    if ($field_type == 'number') $parsing_field_type = "FLOAT";
    if ($field_type == 'date') $parsing_field_type = "DATE";
    if ($field_type == 'time') $parsing_field_type = "TIME";
    if ($field_type == 'datetime') $parsing_field_type = "DATETIME";
    if ($field_type == 'checkbox') $parsing_field_type = "BOOL";


    $sql = "ALTER TABLE " . $table_name . " ADD " . $field_slug . " " . $parsing_field_type;
    if ($wpdb->query($sql) === false) {
        die('Error: ' . $wpdb->print_error() . "<br><br><br>SQL:<br><br>" . $sql);
    }

    include('edit_item.php');
}
//------------------------------------------------------------------

if ($item_action == 'change_item_fields') {
    $table_name = $wpdb->prefix . "ol_params";
    $sql = "SELECT * FROM " . $table_name . " WHERE id IN ( " . $field_id . ", " . $field_id1 . " )";
//    $mysql_results = mysql_query($sql);
//    $field_first = mysql_fetch_array($mysql_results);
//    $field_second = mysql_fetch_array($mysql_results);
    $mysql_results = $wpdb->get_results($sql, ARRAY_A);
    $field_first = $mysql_results[0];
    $field_second = $mysql_results[1];

    $sql = 'UPDATE ' . $table_name . ' SET ' .
        'item_id         = "' . $field_first['item_id'] . '"    ,     ' .
        'title         = "' . $field_first['title'] . '"     ,     ' .
        'slug         = "' . $field_first['slug'] . '"     ,     ' .
        'caption      = "' . $field_first['caption'] . '" ,     ' .
        'type = "' . $field_first['type'] . '" ,  ' .
        'igroup = "' . $field_first['igroup'] . '" ,  ' .
        'default_value = "' . $field_first['default_value'] . '",  ' .
        'to_column = ' . $field_first['to_column'] . ',  ' .
        'to_filter = ' . $field_first['to_filter'] . ',  ' .
        'prm1 = ' . $field_first['prm1'] . ',  ' .
        'prm2 = ' . $field_first['prm2'] . ',  ' .
        'order_by = ' . $field_first['order_by'] . '  ' .
        'WHERE id = "' . $field_second['id'] . '"';

    if ($wpdb->query($sql) === false) {
        die('Error: ' . $wpdb->print_error() . "<br><br><br>SQL:<br><br>" . $sql);
    }

    $sql = 'UPDATE ' . $table_name . ' SET ' .
        'item_id         = "' . $field_second['item_id'] . '"    ,     ' .
        'title         = "' . $field_second['title'] . '"     ,     ' .
        'slug         = "' . $field_second['slug'] . '"     ,     ' .
        'caption      = "' . $field_second['caption'] . '" ,     ' .
        'type = "' . $field_second['type'] . '" , ' .
        'igroup = "' . $field_second['igroup'] . '" , ' .
        'default_value = "' . $field_second['default_value'] . '" ,  ' .
        'to_column = "' . $field_second['to_column'] . '" ,  ' .
        'to_filter = "' . $field_second['to_filter'] . '" ,  ' .
        'prm1 = "' . $field_second['prm1'] . '" ,  ' .
        'prm2 = "' . $field_second['prm2'] . '" ,  ' .
        'order_by = ' . $field_second['order_by'] . '  ' .
        ' WHERE id = "' . $field_first['id'] . '"';

    if ($wpdb->query($sql) === false) {
        die('Error: ' . $wpdb->print_error() . "<br><br><br>SQL:<br><br>" . $sql);
    }

    include('edit_item.php');
}
//------------------------------------------------------------------
if ($item_action == 'edit_item_field') include('edit_item_field.php');
//------------------------------------------------------------------
if ($item_action == 'edit_item_field_in_base') {

    $table_name = $wpdb->prefix . "ol_params";

    $sql = 'UPDATE ' . $table_name . ' SET ' .
        'title         = "' . $field_title . '"    ,     ' .
        'caption         = "' . $field_caption . '"     ,     ' .
        'type      = "' . $field_type . '" ,     ' .
        'igroup      = "' . $field_group . '" ,     ' .
        'default_value = "' . $field_values . '" ,  ' .
        'to_column = ' . $field_to_column . ' ,  ' .
        'to_filter = ' . $field_to_filter . ' ,  ' .
        'prm1 = ' . $field_prm1 . ' ,  ' .
        'prm2 = ' . $field_prm2 . ' ,  ' .
        'order_by = ' . $field_order_by . '  ' .
        'WHERE id = "' . $field_id . '"';

    if ($wpdb->query($sql) === false) {
        die('Error: ' . $wpdb->print_error() . "<br><br><br>SQL:<br><br>" . $sql);
    }

    include('edit_item.php');
}
//------------------------------------------------------------------ 
if ($item_action == 'delete_item_fields') {

    $table_name = $wpdb->prefix . "ol_params";
    $sql = 'SELECT slug FROM ' . $table_name . ' WHERE id = "' . $field_id . '"';
    $mysql_result = $wpdb->get_row($sql, ARRAY_A);
    $field_slug = $mysql_result['slug'];

    $sql = 'DELETE FROM ' . $table_name . ' WHERE id = "' . $field_id . '"';
    if ($wpdb->query($sql) === false) {
        die('Error: ' . $wpdb->print_error() . "<br><br><br>SQL:<br><br>" . $sql);
    }

    $table_name = $wpdb->prefix . "ol_item_" . $item_id;
    $sql = "ALTER TABLE " . $table_name . " DROP " . $field_slug;
    if ($wpdb->query($sql) === false) {
        die('Error: ' . $wpdb->print_error() . "<br><br><br>SQL:<br><br>" . $sql);
    }

    include('edit_item.php');
}
//------------------------------------------------------------------ 
?>

			