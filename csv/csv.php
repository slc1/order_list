<?php
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

        ' ' => '_',

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

$item_action = (isset($_REQUEST["csv_action"]) ? $_REQUEST["csv_action"] : "");
$item_id = (isset($_REQUEST["item_id"]) ? $_REQUEST["item_id"] : "");

//------------------------------------------------------------------
if ($item_action == '') include('csv_list.php');
//------------------------------------------------------------------
if ($item_action == 'csv_item') include('csv_item.php');
//------------------------------------------------------------------
if ($item_action == 'export') {

    $csv_file = ''; // создаем переменную, в которую записываем строки

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
// echo implode(", ", $our_export_fields);

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

//------------------------------------------------------------------

if ($item_action == 'import') {

    $upload_dir = wp_upload_dir();
    $uploadfile = $upload_dir['path'] . "/" . basename($_FILES['csv_file']['name']);

    echo '<pre>';
    if (move_uploaded_file($_FILES['csv_file']['tmp_name'], $uploadfile)) {
        echo "Файл корректен и был успешно загружен.\n";
    } else {
        echo "Возможная атака с помощью файловой загрузки!\n";
    }

    echo 'Некоторая отладочная информация:';
    print_r($_FILES);

    print_r($_POST);

    print "</pre>";
    echo $uploadfile . "<br><br>";
    $confluxe_index = (isset($_REQUEST["confluxe_index"]) ? $_REQUEST["confluxe_index"] : "");
    $confluxe_type = "text";
    $params_arr = array();

    $table_name = $wpdb->prefix . "ol_params";
    $sql = "SELECT * FROM " . $table_name . " WHERE item_id=" . $item_id;
    $mysql_results = $wpdb->get_results($sql, ARRAY_A);
    foreach ($mysql_results as $mysql_result) {
        if ($confluxe_index == $mysql_result['slug']) $confluxe_type = $mysql_result['type'];
        if ($_POST['cb_' . $mysql_result['slug']] == 'ok') {
            $params_arr[$_POST['list_number_' . $mysql_result['slug']]] = array('field' => $mysql_result['slug'], 'type' => $mysql_result['type']);
        }
    }
    if ($_POST['cb_post_id'] == 'ok') {
        $params_arr[$_POST['list_number_post_id']] = array('field' => 'post_id', 'type' => 'number_int');
        $confluxe_type = 'number_int';
    }
    $table_name = $wpdb->prefix . "ol_item_" . $item_id;
    if (($handle = fopen($uploadfile, "r")) !== FALSE) {
        if ($_POST['import_field_name_enabled'] == 'ok') $csv_data = fgetcsv($handle, null, ";"); // днлаем первую холостую  итерацию, если есть заголовки полей в csv файле
        while (($csv_data = fgetcsv($handle, null, ";")) !== FALSE) {
            $csv_num_items = count($csv_data);
            //echo '<pre>';   print_r($csv_data);     echo '</pre><br>';
            $sql = 'UPDATE ' . $table_name . ' SET ';
            for ($c = 0; $c < $csv_num_items; $c++) {
                $field_mean = iconv("windows-1251", "UTF-8", $csv_data[$c]);
                $sql .= ' ' . $params_arr[$c + 1]['field'] . ' = ';
                if (!in_array($params_arr[$c + 1]['type'], array('number', 'checkbox', 'number_int'))) $sql .= '"';
                $sql .= $field_mean;
                if (!in_array($params_arr[$c + 1]['type'], array('number', 'checkbox', 'number_int'))) $sql .= '"';
                if ($c == ($csv_num_items - 1)) $sql .= " "; else $sql .= ", ";
                if ($confluxe_index == $params_arr[$c + 1]['field']) $confluxe_mean = $field_mean;
            }
            $sql .= 'WHERE ' . $_POST['confluxe_index'] . ' = ';
            if (!in_array($confluxe_type, array('number', 'checkbox', 'number_int'))) $sql .= '"';
            $sql .= $confluxe_mean;
            if (!in_array($confluxe_type, array('number', 'checkbox', 'number_int'))) $sql .= '"';
            echo $sql . "<br><br>";
            if ($wpdb->query($sql) === false) {
                die('Error: ' . $wpdb->print_error() . "<br><br><br>SQL:<br><br>" . $sql);
            }
        }
        fclose($handle);
    }
}