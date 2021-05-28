<?php

/* Define the custom box */
add_action('add_meta_boxes', 'order_list_add_custom_box');

/* Do something with the data entered */
add_action('save_post', 'order_list_save_postdata');


add_action('before_delete_post', 'order_list_delete_postdata');

/* Adds a box to the main column on the Post and Page edit screens */
function order_list_add_custom_box()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "ol_items";
    $sql = "SELECT * FROM " . $table_name;
    $mysql_results = $wpdb->get_results($sql, ARRAY_A);
    foreach ($mysql_results as $mysql_result) {
        add_meta_box(
            'myplugin_sectionid',
            'Параметры товара',
            'order_list_inner_custom_box',
            $mysql_result['slug']
        );
    }

}

/* Prints the box content */
function order_list_inner_custom_box($post)
{

    // Use nonce for verification
    wp_nonce_field(plugin_basename(__FILE__), 'order_list_noncename');

    // The actual fields for data entry
    // Use get_post_meta to retrieve an existing value from the database and use the value for the form

    //$value = get_post_meta( $post->ID, '_my_meta_value_key', true );
    $item_slug = get_post_type($post);
    global $wpdb;
    $table_name = $wpdb->prefix . "ol_items";
    $sql = "SELECT * FROM " . $table_name . " WHERE slug='" . $item_slug . "'";
    $mysql_result = $wpdb->get_row($sql, ARRAY_A);
    $item_id = $mysql_result['id'];

    $table_name = $wpdb->prefix . "ol_params";
    $sql = "SELECT * FROM " . $table_name . " WHERE item_id=" . $item_id . " ORDER BY id ";
    $mysql_results = $wpdb->get_results($sql, ARRAY_A);
    ?>
    <input name="item_id" value="<?php echo $item_id; ?>" type="hidden"/>
    <table class="param-table">
        <?php
        $table_name = $wpdb->prefix . "ol_item_" . $item_id;
        $sql = "SELECT * FROM " . $table_name . " WHERE post_id=" . $post->ID;
        $mysql_result1 = $wpdb->get_row($sql, ARRAY_A);


        foreach ($mysql_results as $mysql_result) {
            ?>
            <tr>
                <td><label>
                        <?php echo $mysql_result['title'] . "  ";
                        if ($mysql_result['igroup'] != 0) echo "<em>Группа " . $mysql_result['igroup'] . " </em>"; ?>
                    </label></td>
                <td>
                    <?php if ($mysql_result['type'] == 'text') { ?>
                        <input type="text" id="<?php echo $mysql_result['slug']; ?>"
                               name="<?php echo $mysql_result['slug']; ?>"
                               value="<?php echo $mysql_result1[$mysql_result['slug']]; ?>"/>
                    <?php }
                    if ($mysql_result['type'] == 'tinymce') { ?>
                        <?php wp_editor($mysql_result1[$mysql_result['slug']], $mysql_result['slug'], array('textarea_name' => $mysql_result['slug'])); ?>
                    <?php }
                    if ($mysql_result['type'] == 'checkbox') { ?>
                        <input type="checkbox" id="<?php echo $mysql_result['slug']; ?>"
                               name="<?php echo $mysql_result['slug']; ?>"
                               value="1" <?php if ($mysql_result1[$mysql_result['slug']] == 1) echo 'checked="checked"' ?> />
                    <?php }
                    if ($mysql_result['type'] == 'textarea') { ?>
                        <textarea id="<?php echo $mysql_result['slug']; ?>"
                                  name="<?php echo $mysql_result['slug']; ?>"><?php echo $mysql_result1[$mysql_result['slug']]; ?></textarea>
                    <?php }
                    if ($mysql_result['type'] == 'select') {
                        $select_arr = explode(",", $mysql_result['default_value']);
                        ?>
                        <select id="<?php echo $mysql_result['slug']; ?>" name="<?php echo $mysql_result['slug']; ?>">
                            <?php foreach ($select_arr as $select_el) { ?>
                                <option <?php if ($mysql_result1[$mysql_result['slug']] == $select_el) echo 'selected="selected"' ?> ><?php echo $select_el; ?></option>
                            <?php } ?>
                        </select>
                    <?php }
                    if ($mysql_result['type'] == 'radio') {
                        $select_arr = explode(",", $mysql_result['default_value']);
                        ?>
                        <?php foreach ($select_arr as $select_el) { ?>
                            <input type="radio" value="<?php echo $select_el; ?>"
                                   name="<?php echo $mysql_result['slug']; ?>" <?php if ($mysql_result1[$mysql_result['slug']] == $select_el) echo 'checked="checked"' ?> /> <?php echo $select_el; ?>
                        <?php } ?>
                    <?php }
                    if ($mysql_result['type'] == 'number_int') {
                        $my_slug = $mysql_result['slug']; ?>
                        <script type="text/javascript">
                          jQuery(document).ready(function ($) {
                            $("#<?php echo $my_slug; ?>").keyup(function () {
                              var v_<?php echo $my_slug; ?> = $("#<?php echo $my_slug; ?>").val();
                              if (v_<?php echo $my_slug; ?> != 0) {
                                if (isValid<?php echo $my_slug; ?>(v_<?php echo $my_slug; ?>)) {
                                  $("#valid<?php echo $my_slug; ?>").css({"background-image": "url('/wp-content/plugins/order_list/validyes.png')"});
                                } else {
                                  $("#valid<?php echo $my_slug; ?>").css({"background-image": "url('/wp-content/plugins/order_list/validno.png')"});
                                }
                              } else {
                                $("#valid<?php echo $my_slug; ?>").css({"background-image": "none"});
                              }
                            });
                          });

                          function isValid <?php echo $my_slug; ?>(my_var) {
                            var pattern = new RegExp(/^\-?\d+$/i);
                            return pattern.test(my_var);
                          }
                        </script>
                        <style>
                            #valid<?php echo $my_slug; ?> {
                                margin-top: 4px;
                                margin-left: 9px;
                                position: absolute;
                                width: 16px;
                                height: 16px;
                            }
                        </style>
                        <input type="text" id="<?php echo $mysql_result['slug']; ?>"
                               name="<?php echo $mysql_result['slug']; ?>"
                               value="<?php echo $mysql_result1[$mysql_result['slug']]; ?>"/>
                        <span id="valid<?php echo $my_slug; ?>"></span>
                    <?php }
                    if ($mysql_result['type'] == 'number') {
                        $my_slug = $mysql_result['slug']; ?>
                        <script type="text/javascript">
                          jQuery(document).ready(function ($) {
                            $("#<?php echo $my_slug; ?>").keyup(function () {
                              var v_<?php echo $my_slug; ?> = $("#<?php echo $my_slug; ?>").val();
                              if (v_<?php echo $my_slug; ?> != 0) {
                                if (isValid<?php echo $my_slug; ?>(v_<?php echo $my_slug; ?>)) {
                                  $("#valid<?php echo $my_slug; ?>").css({"background-image": "url('/wp-content/plugins/order_list/validyes.png')"});
                                } else {
                                  $("#valid<?php echo $my_slug; ?>").css({"background-image": "url('/wp-content/plugins/order_list/validno.png')"});
                                }
                              } else {
                                $("#valid<?php echo $my_slug; ?>").css({"background-image": "none"});
                              }
                            });
                          });

                          function isValid <?php echo $my_slug; ?>(my_var) {
                            var pattern = new RegExp(/^\-?\d+(\.\d{0,})?$/i);
                            return pattern.test(my_var);
                          }
                        </script>
                        <style>
                            #valid<?php echo $my_slug; ?> {
                                margin-top: 4px;
                                margin-left: 9px;
                                position: absolute;
                                width: 16px;
                                height: 16px;
                            }
                        </style>
                        <input type="text" id="<?php echo $mysql_result['slug']; ?>"
                               name="<?php echo $mysql_result['slug']; ?>"
                               value="<?php echo $mysql_result1[$mysql_result['slug']]; ?>"/><span
                                id="valid<?php echo $my_slug; ?>"></span>
                    <?php }
                    if ($mysql_result['type'] == 'date') {
                        $my_slug = $mysql_result['slug']; ?>
                        <script type="text/javascript">
                          jQuery(document).ready(function ($) {
                            $("#<?php echo $my_slug; ?>").keyup(function () {
                              var v_<?php echo $my_slug; ?> = $("#<?php echo $my_slug; ?>").val();
                              if (v_<?php echo $my_slug; ?> != 0) {
                                if (isValid<?php echo $my_slug; ?>(v_<?php echo $my_slug; ?>)) {
                                  $("#valid<?php echo $my_slug; ?>").css({"background-image": "url('/wp-content/plugins/order_list/validyes.png')"});
                                } else {
                                  $("#valid<?php echo $my_slug; ?>").css({"background-image": "url('/wp-content/plugins/order_list/validno.png')"});
                                }
                              } else {
                                $("#valid<?php echo $my_slug; ?>").css({"background-image": "none"});
                              }
                            });
                          });

                          function isValid <?php echo $my_slug; ?>(my_var) {
                            var pattern = new RegExp(/^(19|20|21)\d\d-((0[1-9]|1[012])-(0[1-9]|[12]\d)|(0[13-9]|1[012])-30|(0[13578]|1[02])-31)$/i);
                            return pattern.test(my_var);
                          }
                        </script>
                        <style>
                            #valid<?php echo $my_slug; ?> {
                                margin-top: 4px;
                                margin-left: 9px;
                                position: absolute;
                                width: 16px;
                                height: 16px;
                            }
                        </style>
                        <input type="text" id="<?php echo $mysql_result['slug']; ?>"
                               name="<?php echo $mysql_result['slug']; ?>"
                               value="<?php echo $mysql_result1[$mysql_result['slug']]; ?>"/><span
                                id="valid<?php echo $my_slug; ?>"></span>
                    <?php }


                    ?>

                    <em><?php echo $mysql_result['caption']; ?></em>
                </td>
            </tr>
        <?php } ?>
    </table>
    <?php
}

/* When the post is saved, saves our custom data */
function order_list_save_postdata($post_id)
{

    // First we need to check if the current user is authorised to do this action.
    if (!empty( $_POST['post_type']) && 'page' === $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return;
    } else {
        if (!current_user_can('edit_post', $post_id))
            return;
    }

    // Secondly we need to check if the user intended to change this value.
    if (!isset($_POST['order_list_noncename']) || !wp_verify_nonce($_POST['order_list_noncename'], plugin_basename(__FILE__)))
        return;

    // Thirdly we can save the value to the database

    //if saving in a custom table, get post_ID
    $post_ID = $_POST['post_ID'];
    $item_id = $_POST['item_id'];
    global $wpdb;

    /*
                       $table_name = $wpdb->prefix."ol_items";
                       $sql="SELECT * FROM ".$table_name." WHERE id=".$item_id;
                       $mysql_results = mysql_query($sql);
                       while($mysql_result = mysql_fetch_array($mysql_results)) { $post_term_id= $mysql_result['category']+0; }

                       wp_set_object_terms( $post_ID, $post_term_id, 'category');

    */

    $table_name = $wpdb->prefix . "ol_params";
    $sql = "SELECT * FROM " . $table_name . " WHERE item_id=" . $item_id . " ORDER BY id ";
    echo $sql . "<br>__________Check second request!!!________<br>";
    $mysql_results = $wpdb->get_results($sql, ARRAY_A);
    $table_fields = array();

    if (count($mysql_results) > 0) {

        foreach ($mysql_results as $mysql_result) {
            $table_fields[] = $mysql_result['slug'];
        }
        $table_name = $wpdb->prefix . "ol_item_" . $item_id;

        $sql = "SELECT * FROM " . $table_name . " WHERE post_id=" . $_POST['post_ID'];
        $mysql_results = $wpdb->get_results($sql, ARRAY_A);
        if (count($mysql_results) == 0) {
            $sql = "INSERT INTO " . $table_name . " (  " .
                " post_id , ";
            $i = 1;
            foreach ($table_fields as $table_field) {
                $sql .= "  " . $table_field;
                if ($i++ < count($table_fields)) $sql .= ", ";
            }
            $sql .= ") " .
                "VALUES     (" . $_POST['post_ID'] . ", ";
            $i = 1;
            foreach ($table_fields as $table_field) {
                $sql .= ' "' . ($_POST[$table_field]) . '"';
                if ($i++ < count($table_fields)) $sql .= ", ";
            }
            $sql .= " ) ";
        } else {
            $sql = 'UPDATE ' . $table_name . ' SET ';
            $i = 1;
            foreach ($table_fields as $table_field) {
                $sql .= $table_field . '="' . ($_POST[$table_field]) . '"';
                if ($i++ < count($table_fields)) $sql .= ", ";
            }
            $sql .= ' WHERE post_id = ' . $_POST['post_ID'];
        }
        if ($wpdb->query($sql) === false) {
            die('Error: ' . $wpdb->print_error() . "<br><br><br>SQL:<br><br>" . $sql);
        }

    }

    // Do something with $mydata
    // either using

//  add_post_meta($post_ID, '_my_meta_value_key', $mydata, true) or
    //  update_post_meta($post_ID, '_my_meta_value_key', $mydata);

    // or a custom table (see Further Reading section below)
}


function order_list_delete_postdata($post_id)
{
    // First we need to check if the current user is authorised to do this action.
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return;
    } else {
        if (!current_user_can('edit_post', $post_id))
            return;
    }


    global $post_type;
    global $wpdb;

    $table_name = $wpdb->prefix . "ol_items";
    $sql = 'SELECT * FROM ' . $table_name . ' WHERE slug="' . $post_type . '"';
    $mysql_result = $wpdb->get_row($sql, ARRAY_A);
    if (!empty($mysql_result['id'])) {
        $table_name = $wpdb->prefix . "ol_item_" . $mysql_result['id'];
        $sql = "DELETE FROM " . $table_name . " WHERE post_id=" . $post_id;

        if ($wpdb->query($sql) === false) {
            die('Error: ' . $wpdb->print_error() . "<br><br><br>SQL:<br><br>" . $sql);
        }
    }
}

