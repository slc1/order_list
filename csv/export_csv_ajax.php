<?php
require($_SERVER["DOCUMENT_ROOT"] . '/wp-load.php');
$user_id_1 = get_current_user_id();
$user_info = get_userdata($user_id_1);
$our_user_level = $user_info->user_level;

if ($our_user_level < 7) { ?>
    Не хватает прав для проведения операции
<?php } else {

    $item_id = (isset($_REQUEST["item_id"]) ? $_REQUEST["item_id"] : "");
    $export_field_name_enadled = (isset($_REQUEST["export_field_name_enadled"]) ? $_REQUEST["export_field_name_enadled"] : "");


    global $wpdb;
    $table_name = $wpdb->prefix . "ol_item_" . $item_id;

    $sql = "SHOW COLUMNS FROM " . $table_name;
    $our_export_fields = array();
    $mysql_results = $wpdb->get_results($sql, ARRAY_A);
    foreach ($mysql_results as $mysql_result) {
        // echo '<pre>';   print_r($mysql_result);     echo '</pre><br>';
        if ($_POST['cb_' . $mysql_result['Field']] == 'ok') $our_export_fields[$_POST['list_number_' . $mysql_result['Field']]] = $mysql_result['Field'];
    }
    ksort($our_export_fields);
//echo '<pre>';   print_r($our_export_fields);     echo '</pre><br>';    
    //echo implode(", ", $our_export_fields);


    $csv_file = ''; // создаем переменную, в которую записываем строки

    if ($export_field_name_enadled == "ok") { // если задано, добавляем имена полей таблицы
        foreach ($our_export_fields as $my_element) {
            $csv_file .= $my_element . ";";
        }
        $csv_file .= "\r\n";
    }


    $sql = "SELECT " . implode(", ", $our_export_fields) . " FROM " . $table_name;
    $mysql_results = $wpdb->get_results($sql, ARRAY_A);
    foreach ($mysql_results as $mysql_result) {
        foreach ($mysql_result as $my_element) {
            $csv_file .= iconv("UTF-8", "windows-1251", str_replace(array(';', "\r", "\n"), '', $my_element)) . ";";
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