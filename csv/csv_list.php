<div class="metabox-holder has-right-sidebar">
    <div class="icon32" id="icon-options-general"><br/></div>
    <h2>Настройка экспорта импорта посредством CSV</h2>
    <div id="post-body">
        <div class="has-sidebar-content" id="post-body-content">
            <p>Здесь производиться экспорт и импорт списка товаров в формате CSV. Выберите из таблицы ниже, с какой
                группой товаров Вы хотите производить операции экспорта или импорта. </p>
            <table class="widefat">
                <thead>
                <tr>
                    <th>Название</th>
                    <th>Ярлык</th>
                    <th>Таксономи</th>
                    <th>Категория</th>
                    <th>Поля, шт.</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <?php
                global $wpdb;
                $table_name = $wpdb->prefix . "ol_items";
                $sql = "SELECT * FROM " . $table_name;
                $mysql_results = $wpdb->get_results($sql, ARRAY_A);
                foreach ($mysql_results as $mysql_result) {
                    ?>
                    <tr>
                        <td>
                            <a href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/csv.php&csv_action=csv_item&item_id=<?php echo $mysql_result['id']; ?>"
                               class="more-common"><?php echo $mysql_result['title'] ?></a></td>
                        <td><?php echo $mysql_result['slug']; ?></td>
                        <td><?php if (!$mysql_result['taxonomy'] == '') echo $mysql_result['taxonomy']; else echo "Нет" ?></td>
                        <td><?php if (!$mysql_result['category'] == '') {
                                $cat = 1;
                                $yourcat = get_category($mysql_result['category']);
                                if ($yourcat) {
                                    echo $yourcat->name;
                                }
                            } else echo "Нет" ?></td>
                        <td>
                            <?php
                            $table_name = $wpdb->prefix . "ol_params";
                            $sql = "SELECT * FROM " . $table_name . " WHERE item_id=" . $mysql_result['id'];
                            $mysql_results1 = $wpdb->get_results($sql, ARRAY_A);
                            echo count($mysql_results1);
                            ?>
                        </td>
                        <td>
                            <a href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/csv.php&csv_action=csv_item&item_id=<?php echo $mysql_result['id']; ?>"
                               class="more-common">Перейти к экспорту импорту</a></td>
                    </tr>
                <?php } ?>
                <tfoot>
                <tr>
                    <th>Название</th>
                    <th>Ярлык</th>
                    <th>Таксономи</th>
                    <th>Категория</th>
                    <th>Поля, шт.</th>
                    <th>Действия</th>
                </tr>
                </tfoot>
            </table>

        </div>
    </div>
</div>
<div class="clear"></div>