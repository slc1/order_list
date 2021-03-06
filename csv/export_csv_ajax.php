<?php
require($_SERVER["DOCUMENT_ROOT"] . '/wp-load.php');
$user_id_1 = get_current_user_id();
$user_info = get_userdata($user_id_1);
$our_user_level = $user_info->user_level;

if ($our_user_level < 7) { ?>
    Не хватает прав для проведения операции
<?php } else {

    $item_id = (isset($_REQUEST["item_id"]) ? $_REQUEST["item_id"] : "");
    $export_field_name_enabled = (isset($_REQUEST["export_field_name_enabled"]) ? $_REQUEST["export_field_name_enabled"] : "");
    $add_images_to_post = (isset($_REQUEST["add_images_to_post"]) ? $_REQUEST["add_images_to_post"] : false);


    global $wpdb;
    $table_name = $wpdb->prefix . "ol_item_" . $item_id;
    $post_table_name = $wpdb->prefix . 'posts';

    $sql = "SHOW COLUMNS FROM " . $table_name;
    $sql2 = "SHOW COLUMNS FROM " . $post_table_name;
    $our_export_fields = array();
    $mysql_results = array_merge($wpdb->get_results($sql, ARRAY_A), $wpdb->get_results($sql2, ARRAY_A));
    foreach ($mysql_results as $mysql_result) {
        if ($_POST['cb_' . $mysql_result['Field']] == 'ok') {
            $our_export_fields[$_POST['list_number_' . $mysql_result['Field']]] = $mysql_result['Field'];
        }
    }
    ksort($our_export_fields);
    if ($add_images_to_post) {
        $our_export_fields[] = 'post_id';
    }
    $csv_file = ''; // создаем переменную, в которую записываем строки

    if ($export_field_name_enabled == "ok") { // если задано, добавляем имена полей таблицы
        foreach ($our_export_fields as $my_element) {
            $csv_file .= $my_element . ";";
        }
        if ($add_images_to_post) {
            $csv_file .= "image_url;";
        }
        $csv_file .= "\r\n";
    }


    $sql = "SELECT " . implode(", ", $our_export_fields) . " FROM " . $table_name .
        ' LEFT JOIN ' .  $post_table_name.
        ' ON ' . $table_name. '.post_id = ' . $post_table_name . '.id';

    $mysql_results = $wpdb->get_results($sql, ARRAY_A);
    foreach ($mysql_results as $mysql_result) {
        foreach ($mysql_result as $my_element) {
            $csv_file .= iconv("UTF-8", "windows-1251", str_replace(array(';', "\r", "\n"), '', $my_element)) . ";";
        }
        if ($add_images_to_post && !empty($mysql_result['post_id'])) {
            $image_url = get_the_post_thumbnail_url($mysql_result['post_id']);
            $csv_file .= $image_url . ';';
        }
        $csv_file .= "\r\n";
    }

    $file_name = 'export.csv'; // название файла
    $file = fopen($file_name, "w"); // открываем файл для записи, если его нет, то создаем его в текущей папке, где расположен скрипт
    fwrite($file, trim($csv_file)); // записываем в файл строки
    fclose($file); // закрываем файл

// задаем заголовки. то есть задаем всплывающее окошко, которое позволяет нам сохранить файл
    header('Content-type: application/csv;'); // указываем, что это csv документ
    header("Content-Disposition: inline; filename=" . $file_name); // указываем файл, с которым будем работать
    readfile($file_name); // считываем файл

    unlink($file_name); // удаляем файл. тоесть когда вы сохраните файл на локальном компе, то после он удалится с сервера

}