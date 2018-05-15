<?php


?>

				<div class="metabox-holder has-right-sidebar ">		
				
					<div id="icon-options-general" class="icon32"><br/></div>
					<h2>Новый товар</h2>

	
					<div id="post-body">
						<div id="post-body-content" class="has-sidebar-content">
		<p>Здесь мы добавляем новый тип товара в наш веб-магазин.</p>						
        <form method="post" action="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php">
				<table class="form-table">
					<tr class="">
					<th scope="row" valign="top">Название товара<em class="required">Обязательно</em></th>
						<td>
							<input class="input-text" name="item_title" value="Товар1" type="text"/>
                            <em>Это имя товара, каким его будут видеть пользователи (например: одежда, косметика).</em>		 			
                        </td>
					</tr>
                    <tr class="">
					<th scope="row" valign="top">Ярлык товара</th>
						<td>
							<input class="input-text" name="item_slug" value="" type="text"/>
                            <em>Так имя нашего товара будет отображаться в базе данных.</em>
                        </td>
					</tr>
                    <tr class="">
					<th scope="row" valign="top">Название таксономи</th>
						<td>
							<input class="input-text" name="item_taxonomy" value="" type="text"/>
                            <em>Если необходима отдельная таксономи для товара, укажите ее, иначе оставте поле пустым.</em>		 			
                        </td>
					</tr>
                    <tr class="">
					<th scope="row" valign="top">Ярлык таксономи</th>
						<td>
							<input class="input-text" name="item_taxonomy_slug" value="" type="text"/>
                            <em>Так имя таксономи будет отображаться в базе данных.</em>		 			
                        </td>
					</tr>
							<tr class="">
					<th scope="row" valign="top">Категория</th>
											<td>
                                            <?php 
     $cat_args = array(
	'show_option_all'    => '',
	'show_option_none'   => '',
	'orderby'            => 'ID', 
	'order'              => 'ASC',
	'show_count'         => 0,
	'hide_empty'         => 0, 
	'child_of'           => 0,
	'exclude'            => '',
	'echo'               => 1,
	'selected'           => 0,
	'hierarchical'       => 0, 
	'name'               => 'item_category',
	'id'                 => '',
	'class'              => 'postform',
	'depth'              => 0,
	'tab_index'          => 0,
	'taxonomy'           => 'category',
	'hide_if_empty'      => false
                              ); 
 
     wp_dropdown_categories( $cat_args ); 

?>
											</td>
						 			</tr>
			</table>	
<br /><br />
			<input name="ancestor_key" value="" type="hidden"/>
			<input name="originating_keys" value="_plugin,parametry" type="hidden"/>
			<input name="item_action" value="add_item_to_base" type="hidden"/>
			<input class="button" value="Добавить" type="submit"/> &nbsp; <a class="button-primary" href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php">Отмена</a>	
			</form>

									</div> 
					</div>
				<!-- more-plugins --></div>
			
<div class="clear"></div>