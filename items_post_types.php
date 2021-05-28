<?php

add_action('init', 'create_custom_taxonomy');

add_action('init', 'create_post_taxonomy');

add_action('init', 'create_post_type');

function create_custom_taxonomy()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "ol_items";
    $sql = "SELECT * FROM " . $table_name;
    $mysql_results = $wpdb->get_results($sql, ARRAY_A);
    foreach ($mysql_results as $mysql_result) {
        if ($mysql_result['taxonomy'] != "" && $mysql_result['taxonomy_slug'] != "") {
            if (!(taxonomy_exists($mysql_result['taxonomy_slug']))) {
                register_taxonomy(
                    $mysql_result['taxonomy_slug'],
                    array(),
                    array(
                        'hierarchical' => true,
                        'label' => 'Категория &quot;' . $mysql_result['taxonomy'] . '&quot;',
                        'singular_label' => $mysql_result['taxonomy'],
                        'show_admin_column' => true,
                        'rewrite' => array('slug' => $mysql_result['taxonomy_slug'], 'with_front' => false),
                        'capabilities' => array(
                            'assign_terms' => 'edit_posts',
                            'edit_terms' => 'publish_posts'
                        )
                    )
                );
                // flush_rewrite_rules();
            }
        }
    }
}

function create_post_taxonomy()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "ol_items";
    $sql = "SELECT * FROM " . $table_name;
    $mysql_results = $wpdb->get_results($sql, ARRAY_A);
    foreach ($mysql_results as $mysql_result) {
        if (!(taxonomy_exists($mysql_result['slug'] . "-list"))) {
            register_taxonomy(
                $mysql_result['slug'] . "-list",
                array(),
                array(
                    'hierarchical' => true,
                    'label' => 'Рубрика &quot;' . $mysql_result['title'] . '&quot;',
                    'singular_label' => 'Рубрика &quot;' . $mysql_result['title'] . '&quot;',
                    'show_admin_column' => true,
                    'rewrite' => array('slug' => $mysql_result['slug'] . "-list", 'with_front' => false),
                    'capabilities' => array(
                        'assign_terms' => 'edit_posts',
                        'edit_terms' => 'publish_posts'
                    )
                )
            );
            // flush_rewrite_rules();
        }
    }
}

function create_post_type()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "ol_items";
    $sql = "SELECT * FROM " . $table_name;
    $mysql_results = $wpdb->get_results($sql, ARRAY_A);
    foreach ($mysql_results as $mysql_result) {
        if (!(post_type_exists($mysql_result['slug']))) {
            $post_taxonomy_arr = array($mysql_result['slug'] . "-list");
            if (taxonomy_exists($mysql_result['taxonomy_slug'])) $post_taxonomy_arr[] = $mysql_result['taxonomy_slug'];
            register_post_type($mysql_result['slug'],
                array(
                    'labels' => array(
                        'name' => $mysql_result['title'],
                        'singular_name' => $mysql_result['title']
                    ),
                    'public' => true,
                    'has_archive' => true,
                    'taxonomies' => $post_taxonomy_arr,
                    'menu_position' => 100,
                    'supports' => array('title', 'editor', 'author', 'thumbnail', 'comments')
                )
            );
            //flush_rewrite_rules();
        }
    }
}


global $wpdb, $our_item_id;
$table_name = $wpdb->prefix . "ol_items";
$sql = "SELECT * FROM " . $table_name;
$mysql_results = $wpdb->get_results($sql, ARRAY_A);

foreach ($mysql_results as $mysql_result) {
    add_filter('manage_edit-' . $mysql_result['slug'] . '_columns', 'my_columns');
    add_filter('manage_edit-' . $mysql_result['slug'] . '_sortable_columns', 'my_columns_sortable');
    add_action('manage_' . $mysql_result['slug'] . '_posts_custom_column', 'my_show_columns');
    //add_action( 'pre_get_'.$mysql_result['slug'].'_posts', 'my_slice_orderby' );
}


// находим колонки, какие нужно добавить из базы
function my_columns($columns)
{
    global $post, $wpdb;
    //определяем item_id
    $table_name = $wpdb->prefix . "ol_items";
    $sql = 'SELECT * FROM ' . $table_name . ' WHERE slug="' . $post->post_type . '"';
    $mysql_result = $wpdb->get_row($sql, ARRAY_A);
    $our_item_id = $mysql_result['id'];
    //собственно колонки
    $table_name = $wpdb->prefix . "ol_params";
    $sql = "SELECT * FROM " . $table_name . " WHERE item_id=" . $our_item_id . " AND to_column=1";
    $mysql_results = $wpdb->get_results($sql, ARRAY_A);
    foreach ($mysql_results as $mysql_result) {
        $columns[$mysql_result['slug']] = $mysql_result['title'];
    }
    return $columns;
}

// тоже, только делаем их с сортировкой
function my_columns_sortable($columns)
{
    global $post, $wpdb;

    $table_name = $wpdb->prefix . "ol_items";
    $sql = 'SELECT * FROM ' . $table_name . ' WHERE slug="' . $post->post_type . '"';
    $mysql_result = $wpdb->get_row($sql, ARRAY_A);
    $our_item_id = $mysql_result['id'];

    $table_name = $wpdb->prefix . "ol_params";
    $sql = "SELECT * FROM " . $table_name . " WHERE item_id=" . $our_item_id . " AND to_column=1";
    $mysql_results = $wpdb->get_results($sql, ARRAY_A);
    foreach ($mysql_results as $mysql_result) {
        $columns[$mysql_result['slug']] = $mysql_result['slug'];
    }
    return $columns;
}


//вычисляем значение для отдельной колонки
function my_show_columns($column_name)
{
    global $post, $wpdb;
    //определяем item_id
    $table_name = $wpdb->prefix . "ol_items";
    $sql = 'SELECT * FROM ' . $table_name . ' WHERE slug="' . $post->post_type . '"';
    $mysql_result = $wpdb->get_row($sql, ARRAY_A);
    $our_item_id = $mysql_result['id'];
    //определяем колонки для этого item_id и считаем значение
    $table_name = $wpdb->prefix . "ol_params";
    $sql = "SELECT * FROM " . $table_name . " WHERE item_id=" . $our_item_id . " AND to_column=1";
    $mysql_results = $wpdb->get_results($sql, ARRAY_A);
    foreach ($mysql_results as $mysql_result) {
        if ($column_name == $mysql_result['slug']) {
            $table_name = $wpdb->prefix . "ol_item_" . $our_item_id;
            $sql = "SELECT " . $mysql_result['slug'] . " FROM " . $table_name . " WHERE post_id=" . $post->ID;
            $mysql_result1 = $wpdb->get_row($sql, ARRAY_A);
            $our_mean = $mysql_result1[$mysql_result['slug']];
            echo $our_mean;
        }
    }
}


// добавляем алгоритм сортировки
add_action('pre_get_posts', 'my_slice_orderby');

function my_slice_orderby($query)
{
    // в админке ли мы?
    if (!is_admin())
        return;
    global $wpdb;
    // смотрим по чем сортировка
    $orderby = $query->get('orderby');
    // определяем наши post_type
    $table_name = $wpdb->prefix . "ol_items";
    $sql = 'SELECT slug FROM ' . $table_name;
    $mysql_results = $wpdb->get_results($sql, ARRAY_A);
    $post_type_array = array();
    foreach ($mysql_results as $mysql_result) {
        $post_type_array[] = $mysql_result['slug'];
    }
    // есть ли post_type и наш ли он
    if (!empty($_GET['post_type']) && in_array($_GET['post_type'], $post_type_array)) {
        //определяем item_id
        $table_name = $wpdb->prefix . "ol_items";
        $sql = 'SELECT * FROM ' . $table_name . ' WHERE slug="' . $_GET['post_type'] . '"';
        $mysql_result = $wpdb->get_row($sql, ARRAY_A);
        $item_id = $mysql_result['id'];
        // список колонок по которым сортировка
        $table_name = $wpdb->prefix . "ol_params";
        $sql = "SELECT * FROM " . $table_name . " WHERE item_id=" . $item_id . " AND to_column=1";
        //if(! ($mysql_results1 = mysql_query($sql)) ) {  die('Error: ' . mysql_error()."     ".$query->get('post_type')); }
        $mysql_results1 = $wpdb->get_results($sql, ARRAY_A);
        foreach ($mysql_results1 as $mysql_result1) {

            // если колонка из списка совпадает с нашим ордербаем то...
            if ($mysql_result1['slug'] == $orderby) {
                $item_order = $query->get('orderby');
                $item_order_by = $query->get('order');
                // берем query предварительно обнулив наш orderby, ибо ашипка, и кидаем id постов в массив
                $args = $query->query;
                $args['orderby'] = '';
                $my_posts = get_posts($args);
                $term_posts_arr = array();
                foreach ($my_posts as $my_post) {
                    $term_posts_arr[] = $my_post->ID;
                }
                // делаем выборку из нашей базы по id постов из массива с сортировкой
                $table_name = $wpdb->prefix . "ol_item_" . $item_id;
                $sql = "SELECT post_id FROM " . $table_name . " WHERE post_id IN (" . implode(', ', $term_posts_arr) . ") ORDER BY " . $item_order . " " . $item_order_by;
                $mysql_results = $wpdb->get_results($sql, ARRAY_A);
                $term_posts_arr = array();
                // и получнное добро в массив
                foreach ($mysql_results as $mysql_result) {
                    $term_posts_arr[] = $mysql_result['post_id'];
                }

                // теперь правка query  с учетом полученных данных - а именно массив id постов и сортировка по этому массиву, который уже ранее отсортирован
                $query->set('post__in', $term_posts_arr);
                $query->set('orderby', 'post__in');
            }
        }
    }
}

