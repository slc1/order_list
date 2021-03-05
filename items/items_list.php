<div class="metabox-holder has-right-sidebar">
    <div class="icon32" id="icon-options-general"><br/></div>
    <h2>Товары</h2>
    <div id="post-body">
        <div class="has-sidebar-content" id="post-body-content">
            <p>Здесь добавляются и редактируются наявные у нас группы товаров</p>
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
                $productsItems = new \SlcShop\Controller\ProductGroups();
                foreach ($productsItems->productGroupsData as $productGroupData) {
                    $productGroup = new \SlcShop\Model\ProductGroup(null, $productGroupData);
                    ?>
                    <tr>
                        <td>
                            <a href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php&item_action=edit_item&item_id=<?php echo $productGroup->id; ?>"
                               class="more-common"><?php echo $productGroup->getTitle() ?></a></td>
                        <td><?php echo $productGroup->getSlug(); ?></td>
                        <td><?php echo $productGroup->getTaxonomy() ? $productGroup->getTaxonomy() : "Нет" ?></td>
                        <td><?php echo $productGroup->getCategory() ? $productGroup->getCategory() : "Нет" ?></td>
                        <td><?php echo $productGroup->fieldCount; ?></td>
                        <td><a href="<?php echo $productGroup->editLink() ?>" class="more-common">Редактировать</a> |
                            <script language="javascript" type="text/javascript">
                                function delete_item_<?php echo $mysql_result['id']; ?>() {
                                    if (confirm('Вы хотите удалить товар "<?php echo $productGroup->getTitle()?>"? ')) {
                                        location.href = "<?php echo $productGroup->deleteLink() ?>";
                                    } else {
                                        // Do nothing!
                                    }
                                }
                            </script>
                            <a class="more-common-delete" onclick="delete_item_<?php echo $productGroup->id ?>();">Удалить</a>
                            |
                        </td>
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
            <p>
                <a href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php&item_action=add_new_item"
                   class="button-primary">Добавить новый товар</a> <a
                        href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php&is_rewrite_rules=true"
                        class="button-primary">Rewrite Rules</a></p>
        </div>
    </div>
</div>
<div class="clear"></div>