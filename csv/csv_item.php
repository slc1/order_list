<div class="metabox-holder has-right-sidebar ">
    <div id="icon-options-general" class="icon32"><br/></div>
    <?php
    $item_id = (isset($_REQUEST["item_id"]) ? $_REQUEST["item_id"] : "");
    if ($item_id == "") { ?>
        <h2>У нас ошибка</h2>
    <?php } else {
    global $wpdb;
    $table_name = $wpdb->prefix . "ol_items";
    $sql = "SELECT * FROM " . $table_name . " WHERE id=" . $item_id;
    $mysql_result = $wpdb->get_row($sql, ARRAY_A);

    ?>
    <h2>Экспорт импорт для <?php echo $mysql_result['title'] ?></h2>
    <div id="post-body">
        <div id="post-body-content" class="has-sidebar-content">
            <p>Ярлык товара <strong><?php echo $mysql_result['slug'] ?></strong></p>
            <form method="post" enctype="multipart/form-data" name="ex_im_form"
                  action="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/csv.php">
                <br/>
                <p>Список полей экспорта\импорта в таблице. <span style="color: red;">При импорте обязательно выбрать поле сопряжения.</span>
                    Поле сопряжения является уникальным идентификатором, по которому определяется товар. </p>
                <br/>
                <table>
                    <tr>
                        <td width="130"><input class="button" value="Экспорт в CSV" type="button"
                                               onclick="csv_export();"/></td>
                        <td width="20"><input type="checkbox" name="export_field_name_enadled" value="ok"/></td>
                        <td>Добавить поля таблицы в файл</td>
                    </tr>
                    <tr>
                        <td colspan="3"><br/><br/></td>
                    </tr>
                    <tr>
                        <td><input class="button" value="Импорт из CSV" type="button" onclick="csv_import();"/></td>
                        <td><input type="checkbox" name="import_field_name_enadled" value="ok"/></td>
                        <td>В файле добавлены поля таблицы &nbsp; Выберите файл для импорта: <input type="file"
                                                                                                    accept="text/html"
                                                                                                    name="csv_file"
                                                                                                    size="20"
                                                                                                    value="выбрать"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">Действие при наличии значения поля сопряжения в базе MySQL, которое осутствует в
                            файле импорта <br/>
                            <input type="radio" name="csv_confluxe_absent" value="nothing"/> Ничего не делать
                            <input type="radio" name="csv_confluxe_absent" value="delete"/> Удалить
                            <input type="radio" name="csv_confluxe_absent" value="mark"/> Пометить.
                            Записать <input type="text" name="mysql_confluxe_absent_mark" value="0"/> в значение поля
                            пометки.
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">Действие при наличии значения поля сопряжения в файле импорта, которое
                            осутствует в базе MySQL<br/>
                            <input type="radio" name="mysql_confluxe_absent" value="nothing"/> Ничего не делать
                            <input type="radio" name="mysql_confluxe_absent" value="delete"/> Добавить новый товар
                        </td>
                    </tr>
                </table>
                <br/>
                <p>Порядок отображаемых полей для CSV: <strong id="ex_im_order"></strong></p>
                <br/>
                <input type="button" value="Выбрать все" onclick="sel_all();"/> <input type="button"
                                                                                       value="Снять выделение"
                                                                                       onclick="unsel_all();"/>
                <table class="widefat">
                    <thead>
                    <tr>
                        <th>Выбор</th>
                        <th>№ п/п</th>
                        <th>Поле</th>
                        <th>Поле БД</th>
                        <th>Тип поля</th>
                        <th>Группа</th>
                        <th>Сопряжение</th>
                        <th>Пометка</th>
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
                        $i++;
                        ?>
                        <tr class="" id="tr_<?php echo $i; ?>">
                            <td><input type="checkbox" name="cb_<?php echo $mysql_result['slug']; ?>"
                                       id="cb_<?php echo $i; ?>" value="ok"
                                       onclick="field_set_num(<?php echo $i; ?>);"/></td>
                            <td><input type="text" class="csv_list_number"
                                       name="list_number_<?php echo $mysql_result['slug']; ?>" value="X"
                                       id="list_number_<?php echo $i; ?>" readonly="readonly"/></td>
                            <td><input type="hidden" value="kk"/> <span
                                        id="title_<?php echo $i; ?>"><?php echo $mysql_result['title']; ?></span></td>
                            <td><?php echo $mysql_result['slug']; ?></td>
                            <td><?php echo $mysql_result['type']; ?></td>
                            <td><?php if ($mysql_result['igroup'] == 0) echo "Без группы"; else echo $mysql_result['igroup']; ?></td>
                            <td align="center"><input type="radio" name="confluxe_index"
                                                      value="<?php echo $mysql_result['slug']; ?>"/></td>
                            <td align="center"><input type="radio" name="mark_index"
                                                      value="<?php echo $mysql_result['slug']; ?>"/></td>

                        </tr>
                    <?php }
                    $i++; ?>
                    <tr class="" id="tr_<?php echo $i; ?>">
                        <td><input type="checkbox" name="cb_post_id" id="cb_<?php echo $i; ?>" value="ok"
                                   onclick="field_set_num(<?php echo $i; ?>);"/></td>
                        <td><input type="text" class="csv_list_number" name="list_number_post_id" value="X"
                                   id="list_number_<?php echo $i; ?>" readonly="readonly"/></td>
                        <td><input type="hidden" value="kk"/> <span id="title_<?php echo $i; ?>">ID поста</span></td>
                        <td>post_id</td>
                        <td></td>
                        <td></td>
                        <td align="center"><input type="radio" name="confluxe_index" value="post_id"/></td>
                        <td align="center"><input type="radio" name="mark_index" value="post_id"/></td>

                    </tr>
                    <tfoot>
                    <tr>
                        <th colspan="8">
                            <p>
                                <input class="button" value="Экспорт в CSV" type="button" onclick="csv_export();"/>
                                &nbsp;
                                Добавить поля таблицы в файл <input type="checkbox" name="export_field_name_enadled"
                                                                    value="ok"/>
                                <br/><br/>
                                <input class="button" value="Импорт из CSV" type="button"/> &nbsp;
                                В файле добавлены поля таблицы <input type="checkbox" name="import_field_name_enadled"
                                                                      value="ok"/>
                            </p>
                        </th>
                    </tr>
                    </tfoot>
                </table>
                <script type="text/javascript">
                  var current_field_num = 0;
                  field_set_num = function (slug) {
                    var cb_count =<?php echo $i; ?>;
                    if (document.getElementById('cb_' + slug).checked) {
                      current_field_num++;
                      document.getElementById('list_number_' + slug).value = current_field_num;
                      document.getElementById('tr_' + slug).style.backgroundColor = "#E88B9D";
                    }
                    else {
                      for (var i = 1; i <= cb_count; i++) {
                        if (document.getElementById('cb_' + i).checked && parseInt(document.getElementById('list_number_' + i).value) > parseInt(document.getElementById('list_number_' + slug).value)) document.getElementById('list_number_' + i).value--;
                      }
                      current_field_num--;
                      document.getElementById('list_number_' + slug).value = "X";
                      document.getElementById('tr_' + slug).style.backgroundColor = "transparent";
                    }
                    var order_list = new Array();
                    for (var i = 1; i <= cb_count; i++) {
                      if (document.getElementById('cb_' + i).checked) order_list[document.getElementById('list_number_' + i).value] = document.getElementById('title_' + i).innerHTML;
                    }
                    document.getElementById('ex_im_order').innerHTML = order_list.join(', ');
                  }

                  csv_export = function () {
                    var error_message = '';
                    var is_field_select = false;

                    for (var i = 1; i <= <?php echo $i; ?>; i++) {
                      if (document.getElementById('cb_' + i).checked) {
                        is_field_select = true;
                        break;
                      }
                    }

                    if (!is_field_select) error_message = error_message + "Не выбрано ни одного поля для импорта \n";

                    if (error_message != '') {
                      alert(error_message);
                    }
                    else {
                      document.ex_im_form.action = "<?php echo plugins_url();  ?>/<?php echo dirname(plugin_basename(__FILE__)); ?>/export_csv_ajax.php";
                      document.ex_im_form.submit();
                    }
                  }

                  check_radio_buttons = function (rb_name) {
                    var our_rb = document.getElementsByName(rb_name);
                    var is_checked = false;
                    for (var i = 0; i < our_rb.length; i++) {
                      if (our_rb[i].checked) {
                        is_checked = true;
                        break;
                      }
                    }
                    return (is_checked);
                  }


                  csv_import = function () {
                    var error_message = '';
                    var is_field_select = false;

                    for (var i = 1; i <= <?php echo $i; ?>; i++) {
                      if (document.getElementById('cb_' + i).checked) {
                        is_field_select = true;
                        break;
                      }
                    }

                    if (!is_field_select) error_message = error_message + "Не выбрано ни одного поля для импорта \n";
                    if (!check_radio_buttons('confluxe_index')) error_message = error_message + "Не выбрано поле сопряжения \n";
                    if (!check_radio_buttons('csv_confluxe_absent')) error_message = error_message + "Не выбрано действие при наличии значения поля сопряжения в базе MySQL \n";
                    if (!check_radio_buttons('mysql_confluxe_absent')) error_message = error_message + "Не выбрано действие при наличии значения поля сопряжения в файле импорта \n";

                    if (error_message != '') {
                      alert(error_message);
                    }
                    else {
                      document.ex_im_form.csv_action.value = 'import';
                      document.ex_im_form.action = "admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/csv.php";
                      document.ex_im_form.submit();
                    }
                  }


                  sel_all = function () {
                    for (var i = 1; i <= <?php echo $i; ?>; i++) {
                      if (!document.getElementById('cb_' + i).checked) {
                        document.getElementById('cb_' + i).checked = true;
                        field_set_num(i);
                      }
                    }
                  }

                  unsel_all = function () {
                    for (var i = 1; i <= <?php echo $i; ?>; i++) {
                      if (document.getElementById('cb_' + i).checked) {
                        document.getElementById('cb_' + i).checked = false;
                        field_set_num(i);
                      }
                    }
                    //current_field_num=0;
                  }
                </script>
                <br/><br/>
                <input name="ancestor_key" value="" type="hidden"/>
                <input name="item_id" value="<?php echo $item_id; ?>" type="hidden"/>
                <input name="csv_action" value="" type="hidden"/>

            </form>
            <?php } ?>

        </div>
    </div>
</div>
<div class="clear"></div>