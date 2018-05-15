<?php

?>

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
                     global  $wpdb;
                     $table_name = $wpdb->prefix."ol_items";
                     $sql="SELECT * FROM ".$table_name;
                     $mysql_results = $wpdb->get_results($sql, ARRAY_A);
                     foreach ($mysql_results as $mysql_result) {
                     ?>
                       <tr>
								<td><a href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php&item_action=edit_item&item_id=<?php echo $mysql_result['id']; ?>" class="more-common"><?php echo $mysql_result['title'] ?></a></td>
                                <td><?php echo $mysql_result['slug']; ?></td>
                                <td><?php if (!$mysql_result['taxonomy']=='') echo $mysql_result['taxonomy']; else echo "Нет" ?></td>
                                <td><?php if (!$mysql_result['category']=='') {$cat=1; $yourcat = get_category($mysql_result['category']); if ($yourcat) {
echo  $yourcat->name ;} } else echo "Нет" ?></td>
								<td>
                                         <?php 
                                          $table_name = $wpdb->prefix."ol_params";
                                          $sql="SELECT * FROM ".$table_name." WHERE item_id=".$mysql_result['id'];
                                         $mysql_results1 = $wpdb->get_results($sql, ARRAY_A);
                                          echo count($mysql_results1);
                                         ?>
                                </td>
								<td><a href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php&item_action=edit_item&item_id=<?php echo $mysql_result['id']; ?>" class="more-common">Редактировать</a> | 
                                <script language="javascript" type="text/javascript">
                                function delete_item_<?php echo $mysql_result['id']; ?>() {
                                  if (confirm('Вы хотите удалить товар "<?php echo $mysql_result['title']; ?>"? ')) {
                                       location.href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php&item_action=delete_item&item_id=<?php echo $mysql_result['id']; ?>";
                                  } else {
                                // Do nothing!
                                  } 
                                }
                                </script>
                                <a class="more-common-delete" onclick="delete_item_<?php echo $mysql_result['id']; ?>();">Удалить</a> |</td> 
					   </tr>
					 <?php  } ?>	
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
			<p><a href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php&item_action=add_new_item" class="button-primary">Добавить новый товар</a>  <a href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php&is_rewrite_rules=true" class="button-primary">Rewrite Rules</a></p>			
									</div> 
					</div>
</div>
			
<div class="clear"></div>