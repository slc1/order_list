<div class="wrap">
    <div id="<?php echo $this_table_obj->pluginname ?>_icon32" class="icon32"><br/></div>
    <h2>Просмотр заказа</h2>
    <br/><br/>

    <form name="post"
          action="admin.php?page=<?php echo ORDER_LIST_PLUGIN_SLUG ?>"
          method="post" id="post">
        <input class="button-primary" type="submit" name="cancelbutton" value="Выйти"/>
        <br/><br/>
        <?php
        $columns = $this_table_obj->get_editdef();
        for ($i = 1; $i <= sizeof($columns); $i++) {
            ?>
            <div style="font-size:16px; font-style:italic;">
                <?php
                if (isset($this_table_obj->tabledef[$i]['header'])) echo $this_table_obj->tabledef[$i]['header'];
                else  echo $this_table_obj->tabledef[$i]['field'];
                ?>
                :
            </div>
            <?php
            echo nl2br($post->{$columns[$i]["field"]});
            ?>
            <br/><br/>
        <?php } ?>
        <br/><br/>

        <input class="button-primary" type="submit" name="cancelbutton" value="Выйти"/>
    </form>
</div>