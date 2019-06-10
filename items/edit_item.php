<div class="metabox-holder has-right-sidebar ">
    <div id="icon-options-general" class="icon32"><br/></div>
    <h2>Редактировать товар</h2>
    <div id="post-body">
        <div id="post-body-content" class="has-sidebar-content">
            <p>Здесь мы редатируем параметры существующего товара.</p>
            <form method="post" action="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php">
                <table class="form-table">
                    <?php
                    $item_id = (isset($_REQUEST["item_id"]) ? $_REQUEST["item_id"] : "");
                    global $wpdb;
                    $table_name = $wpdb->prefix . "ol_items";
                    $sql = "SELECT * FROM " . $table_name . " WHERE id=" . $item_id;
                    $mysql_results = $wpdb->get_results($sql, ARRAY_A);
                    foreach ($mysql_results as $mysql_result) {

                        ?>
                        <tr class="">
                            <th scope="row" valign="top">Название товара<em class="required">Обязательно</em></th>
                            <td>
                                <input class="input-text" name="item_title" value="<?php echo $mysql_result['title'] ?>"
                                       type="text"/>
                                <em>Это имя товара, каким его будут видеть пользователи (например: одежда,
                                    косметика).</em>
                            </td>
                        </tr>
                        <tr class="">
                            <th scope="row" valign="top">Ярлык товара</th>
                            <td>
                                <input class="input-text" name="item_slug" value="<?php echo $mysql_result['slug'] ?>"
                                       type="text"/>
                                <em>Так имя нашего товара будет отображаться в базе данных.</em>
                            </td>
                        </tr>
                        <tr class="">
                            <th scope="row" valign="top">Название таксономи</th>
                            <td>
                                <input class="input-text" name="item_taxonomy"
                                       value="<?php echo $mysql_result['taxonomy'] ?>" type="text"/>
                                <em>Если необходима отдельная таксономи для товара, укажите ее, иначе оставте поле
                                    пустым.</em>
                            </td>
                        </tr>
                        <tr class="">
                            <th scope="row" valign="top">Ярлык таксономи</th>
                            <td>
                                <input class="input-text" name="item_taxonomy_slug"
                                       value="<?php echo $mysql_result['taxonomy_slug'] ?>" type="text"/>
                                <em>Так имя таксономи будет отображаться в базе данных.</em>
                            </td>
                        </tr>
                        <tr class="">
                            <th scope="row" valign="top">Категория</th>
                            <td>
                                <?php
                                $cat_args = array(
                                    'show_option_all' => '',
                                    'show_option_none' => '',
                                    'orderby' => 'ID',
                                    'order' => 'ASC',
                                    'show_count' => 0,
                                    'hide_empty' => 0,
                                    'child_of' => 0,
                                    'exclude' => '',
                                    'echo' => 1,
                                    'selected' => $mysql_result['category'],
                                    'hierarchical' => 0,
                                    'name' => 'item_category',
                                    'id' => '',
                                    'class' => 'postform',
                                    'depth' => 0,
                                    'tab_index' => 0,
                                    'taxonomy' => 'category',
                                    'hide_if_empty' => false
                                );

                                wp_dropdown_categories($cat_args);
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
                <br/><br/>
                <p>Список полей для товара</p>
                <table class="widefat">
                    <thead>
                    <tr>
                        <th>Поле</th>
                        <th>Слаг</th>
                        <th>Тип поля</th>
                        <th>Группа</th>
                        <th>В колонку</th>
                        <th>Сортировка</th>
                        <th>Фильтр</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <?php
                    global $wpdb;
                    $table_name = $wpdb->prefix . "ol_params";
                    $sql = "SELECT * FROM " . $table_name . " WHERE item_id=" . $item_id . " ORDER BY id ";
                    $mysql_results = $wpdb->get_results($sql, ARRAY_A);

                    $sql_array = array();
                    $i = 0;
                    $i_max = count($mysql_results);
                    foreach ($mysql_results as $mysql_result) {
                        $sql_array[] = $mysql_result;
                    }
                    foreach ($sql_array as $mysql_result) {
                        $i++;
                        ?>
                        <tr class="">
                            <td><a class="more-common"
                                   href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php&item_action=edit_item_field&item_id=<?php echo $item_id; ?>&field_id=<?php echo $mysql_result['id']; ?>"><?php echo $mysql_result['title']; ?></a>
                            </td>
                            <td><?php echo $mysql_result['slug']; ?></td>
                            <td><?php echo $mysql_result['type']; ?></td>
                            <td><?php if ($mysql_result['igroup'] == 0) echo "Без группы"; else echo $mysql_result['igroup']; ?></td>
                            <td><?php if ($mysql_result['to_column']) echo "Да"; else echo "Нет"; ?></td>
                            <td><?php if ($mysql_result['order_by']) echo "Да"; else echo "Нет"; ?></td>
                            <td><?php if ($mysql_result['to_filter']) echo "Да"; else echo "Нет"; ?></td>
                            <td><a class="more-common"
                                   href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php&item_action=edit_item_field&item_id=<?php echo $item_id; ?>&field_id=<?php echo $mysql_result['id']; ?>">Редактировать</a>
                                |
                                <script language="javascript" type="text/javascript">
                                  function delete_field_ <?php echo $mysql_result['id']; ?>() {
                                    if (confirm('Вы хотите удалить поле "<?php echo $mysql_result['title']; ?>"? ')) {
                                      location.href = "admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php&item_action=delete_item_fields&item_id=<?php echo $item_id; ?>&field_id=<?php echo $mysql_result['id']; ?>";
                                    } else {
                                      // Do nothing!
                                    }
                                  }
                                </script>
                                <a class="more-common-delete"
                                   onclick="delete_field_<?php echo $mysql_result['id']; ?>();">Удалиить</a>
                                <?php if (!($i == 1)) { ?>
                                    | <a class="more-common"
                                         href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php&item_action=change_item_fields&item_id=<?php echo $item_id; ?>&field_id=<?php echo $mysql_result['id']; ?>&field_id1=<?php echo $sql_array[$i - 2]['id']; ?>">↑</a>
                                <?php } ?>
                                <?php if (!($i == $i_max)) { ?> | <a class="more-common"
                                                                     href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php&item_action=change_item_fields&item_id=<?php echo $item_id; ?>&field_id=<?php echo $mysql_result['id']; ?>&field_id1=<?php echo $sql_array[$i]['id']; ?>">↓</a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tfoot>
                    <tr>
                        <th colspan="8">
                            <p><a class="button-primary"
                                  href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php&item_action=add_item_field&item_id=<?php echo $item_id; ?>">Добавить
                                    поле</a></p></th>
                    </tr>
                    </tfoot>
                </table>
                <br/><br/>
                <input name="ancestor_key" value="" type="hidden"/>
                <input name="item_id" value="<?php echo $item_id; ?>" type="hidden"/>
                <input name="item_action" value="edit_item_in_base" type="hidden"/>
                <input class="button" value="Редактировать" type="submit"/> &nbsp;
                <a class="button-primary" href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php">Отмена</a>
            </form>
        </div>
    </div>
</div>
<div class="clear"></div>