<div class="metabox-holder has-right-sidebar ">
    <div id="icon-options-general" class="icon32"><br/></div>
    <h2>Редактировать товар</h2>
    <div id="post-body">
        <div id="post-body-content" class="has-sidebar-content">
            <p>Здесь мы редатируем параметры существующего товара.</p>
            <form method="post" action="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php">
                <table class="form-table">
                    <?php
                    $productGroup = new \SlcShop\Model\ProductGroup((isset($_REQUEST["item_id"]) ? $_REQUEST["item_id"] : ""));
                        ?>
                        <tr class="">
                            <th scope="row" valign="top">Название товара<em class="required">Обязательно</em></th>
                            <td>
                                <input class="input-text" name="item_title" value="<?php echo $productGroup->getTitle() ?>"
                                       type="text"/>
                                <em>Это имя товара, каким его будут видеть пользователи (например: одежда,
                                    косметика).</em>
                            </td>
                        </tr>
                        <tr class="">
                            <th scope="row" valign="top">Ярлык товара</th>
                            <td>
                                <input class="input-text" name="item_slug" value="<?php echo $productGroup->getSlug() ?>"
                                       type="text"/>
                                <em>Так имя нашего товара будет отображаться в базе данных.</em>
                            </td>
                        </tr>
                        <tr class="">
                            <th scope="row" valign="top">Название таксономи</th>
                            <td>
                                <input class="input-text" name="item_taxonomy"
                                       value="<?php echo $productGroup->getTaxonomy() ?>" type="text"/>
                                <em>Если необходима отдельная таксономи для товара, укажите ее, иначе оставте поле
                                    пустым.</em>
                            </td>
                        </tr>
                        <tr class="">
                            <th scope="row" valign="top">Ярлык таксономи</th>
                            <td>
                                <input class="input-text" name="item_taxonomy_slug"
                                       value="<?php echo $productGroup->getTaxonomySlug() ?>" type="text"/>
                                <em>Так имя таксономи будет отображаться в базе данных.</em>
                            </td>
                        </tr>
                        <tr class="">
                            <th scope="row" valign="top">Категория</th>
                            <td>
                                <?php $productGroup->categoryList(); ?>
                            </td>
                        </tr>
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
                    $i = 0;
                    $i_max = $productGroup->fieldCount;
                    foreach ($productGroup->paramsData as $paramData) {
                        $productParam = new \SlcShop\Model\ProductParam(null, $paramData);
                        $i++;
                        ?>
                        <tr class="">
                            <td><a class="more-common"
                                   href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php&item_action=edit_item_field&item_id=<?php echo $productGroup->id; ?>&field_id=<?php echo $productParam->getId() ?>"><?php echo $productParam->getTitle(); ?></a>
                            </td>
                            <td><?php echo $productParam->getSlug();  ?></td>
                            <td><?php echo $productParam->getType();  ?></td>
                            <td><?php if (!$productParam->getIgroup()) echo "Без группы"; else echo $productParam->getIgroup(); ?></td>
                            <td><?php if ($productParam->getToColumn()) echo "Да"; else echo "Нет"; ?></td>
                            <td><?php if ($productParam->getOrderBy()) echo "Да"; else echo "Нет"; ?></td>
                            <td><?php if ($productParam->getToFilter()) echo "Да"; else echo "Нет"; ?></td>
                            <td><a class="more-common"
                                   href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php&item_action=edit_item_field&item_id=<?php echo $productGroup->id; ?>&field_id=<?php echo $productParam->getId(); ?>">Редактировать</a>
                                |
                                <script language="javascript" type="text/javascript">
                                  function delete_field_ <?php echo $productParam->getId(); ?>() {
                                    if (confirm('Вы хотите удалить поле "<?php echo $productParam->getTitle(); ?>"? ')) {
                                      location.href = "admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php&item_action=delete_item_fields&item_id=<?php echo $productGroup->id; ?>&field_id=<?php echo $productParam->getId(); ?>";
                                    } else {
                                      // Do nothing!
                                    }
                                  }
                                </script>
                                <a class="more-common-delete"
                                   onclick="delete_field_<?php echo $productParam->getId(); ?>();">Удалиить</a>
                                <?php if (!($i == 1)) { ?>
                                    | <a class="more-common"
                                         href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php&item_action=change_item_fields&item_id=<?php echo $productGroup->id; ?>&field_id=<?php echo $productParam->getId(); ?>&field_id1=<?php echo $productGroup->paramsData[$i - 2]['id']; ?>">↑</a>
                                <?php } ?>
                                <?php if (!($i == $i_max)) { ?> | <a class="more-common"
                                                                     href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php&item_action=change_item_fields&item_id=<?php echo $productGroup->id; ?>&field_id=<?php echo $productParam->getId(); ?>&field_id1=<?php echo $productGroup->paramsData[$i]['id']; ?>">↓</a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tfoot>
                    <tr>
                        <th colspan="8">
                            <p><a class="button-primary"
                                  href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php&item_action=add_item_field&item_id=<?php echo $productGroup->id; ?>">Добавить
                                    поле</a></p></th>
                    </tr>
                    </tfoot>
                </table>
                <br/><br/>
                <input name="ancestor_key" value="" type="hidden"/>
                <input name="item_id" value="<?php echo $productGroup->id; ?>" type="hidden"/>
                <input name="item_action" value="edit_item_in_base" type="hidden"/>
                <input class="button" value="Редактировать" type="submit"/> &nbsp;
                <a class="button-primary" href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php">Отмена</a>
            </form>
        </div>
    </div>
</div>
<div class="clear"></div>