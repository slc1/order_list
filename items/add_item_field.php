<?php

?>
				<div class="metabox-holder has-right-sidebar">		
				
					<div id="icon-options-general" class="icon32"><br/></div>
					<h2>Добавить поле</h2>
	
					<div id="post-body">
						<div id="post-body-content" class="has-sidebar-content">
			<form method="post" action="#">
				<table class="form-table">
                  <tr class="">
					<th scope="row" valign="top">Имя поля<em class="required">Обязательно</em></th>
					<td>
					   <input class="input-text" name="field_title" value="" type="text"/>
                       <em>Это имя поля, которое будет отображаться у этого параметра товара.</em>		 				        
                    </td>
				  </tr>
				  <tr class="">
					<th scope="row" valign="top">Текст подсказки</th>
					<td>
						<textarea class="input-textarea" name="field_caption"></textarea>
                        <em>Это текст подсказки, что именно и как можно вводить в этот параметр.</em>		 	  </td>
	 			  </tr>
                  <tr class="">
					<th scope="row" valign="top">Добавить поле в группу</th>
					<td>
						<select name="field_group">
                            <option value="0">Без группы</option>
                            <?php 
                                $table_name = $wpdb->prefix."ol_params";
                                $sql="SELECT igroup FROM ".$table_name." WHERE item_id =".$item_id." AND igroup<>0 GROUP BY igroup ORDER by igroup";
                                $mysql_results = mysql_query($sql);
                                 while($mysql_result = mysql_fetch_array($mysql_results))  { 
                            ?>
                            <option value="<?php echo $mysql_result['igroup']; ?>"><?php echo $mysql_result['igroup']; ?></option>
                            <?php $last_result=$mysql_result['igroup']; } ?>
                            <option value="<?php echo $last_result+1; ?>">Новая группа</option>
                        </select>
                        <em>Здесь проводится группировка полей</em>		 	  </td>
	 			  </tr>
                 <tr class="">
					<th scope="row" valign="top">В колонку </th>
					<td>
                       <input type="checkbox" value="1" name="field_to_column" />
                       <em>Добавить колонку с этим значением в список товаров с возможностью сортировки по ней</em>	
	 	            </td>
	 			  </tr>
                  <tr class="">
					<th scope="row" valign="top">Сортировка </th>
					<td>
                       <input type="checkbox" value="1" name="field_order_by"/>
                       <em>Поставить галочку, в случае если вы планируете проводить сортировку по этому полю</em>	
	 	            </td>
	 			  </tr>
                  <tr class="">
					<th scope="row" valign="top">Фильтр</th>
					<td>
                       <input type="checkbox" value="1" name="field_to_filter"/>
                       <em>Поставить галочку, в случае если вы собираетесь делать фильтрацию по этому полю</em>	
	 	            </td>
	 			  </tr>
                  <tr class="">
					<th scope="row" valign="top">Прм 1 </th>
					<td>
                       <input type="checkbox" value="1" name="field_prm1"/>
                       <em>Это дополнительный параметр номер 1 (на всякий случай)</em>	
	 	            </td>
	 			  </tr>
                  <tr class="">
					<th scope="row" valign="top">Прм 2</th>
					<td>
                       <input type="checkbox" value="1" name="field_prm2"/>
                       <em>Это дополнительный параметр номер 2 (на всякий случай)</em>	
	 	            </td>
	 			  </tr>
				  <tr class="">
					<th scope="row" valign="top">Тип поля</th>
					<td>
					  <label><input class="input-radio" name="field_type" value="text" checked="checked" type="radio"/> Text</label> 
                      <em>Создается простое поле ввода в одну строчку.</em>
                      <label><input class="input-radio" name="field_type" value="textarea" type="radio"/> Textarea</label> 
                      <em>Большое поле ввода для текста.</em>
                      <label><input class="input-radio" name="field_type" value="tinymce" type="radio"/> Tiny MCE</label> 
                      <em>Поле ввода Tiny MCE, как для контента Wordpress.</em>
                      <label><input class="input-radio" name="field_type" value="select" type="radio"/> Select</label> 
                      <em>Создается выпадающий список. Для него обязательно заполнение предопределенных значений.</em>
                      <label><input class="input-radio" name="field_type" value="radio" type="radio"/> Radio</label> 
                      <em>Создает список радиокнопок, из которых можна вбрать одну. Для него обязательно заполнение предопределенных значений.</em>
                      <label><input class="input-radio" name="field_type" value="checkbox" type="radio"/> Checkbox</label> 
                      <em>Создается переключатель из двух положений да\нет</em>
                      <label><input class="input-radio" name="field_type" value="number_int" type="radio"/> Number Integer</label> 
                      <em>Поле для целых числовых значений -2147483648 to 2147483647. При вводе проводится валидация.</em>
                      <label><input class="input-radio" name="field_type" value="number" type="radio"/> Number</label> 
                      <em>Поле для целых и дробных числовых значений. При вводе проводится валидация.</em>
                      <label><input class="input-radio" name="field_type" value="date" type="radio"/> Date</label> 
                      <em>Поле для ввода даты. Должно быть в формате YYYY-mm-dd, например <code>2010-07-10</code>. При вводе проводится валидация.</em>
                      
                      <!--
                      <label><input class="input-radio" name="field_type" value="color" type="radio"/> Color</label> 
                      <em>HTML5 input type, full functionality available in modern browsers. Alternative input values can be <code class="mf_color"><a href="http://www.w3.org/TR/css3-color/#html4">color keyword</a></code>, <code>rgba(255, 255, 255, 1.0)</code>, <code>#ffffff</code> or <code>#fff</code></em>
                      <label><input class="input-radio" name="field_type" value="time" type="radio"/> Time</label> 
                      <em>HTML5 input type, full functionality available in modern browsers. Alternative input: HH:ii, i.e <code>13:37</code></em>
                      <label><input class="input-radio" name="field_type" value="datetime" type="radio"/> Date and Time</label> 
                      <em>HTML5 input type, full functionality available in modern browsers. Alternative input: YYYY-mm-dd HH:ii, i.e <code>2010-07-10 13:37</code></em>		 			
                        -->	
                      </td>
	 			</tr>
				<tr class="">
					<th scope="row" valign="top">Список значений (если возможно)</th>
					<td>
					  <input class="input-text" name="field_values" value="" type="text"/>
                      <em>Если поле данного типа разрешает список предопределенных значений, введите их здесь, через запятую. Это должно выглядеть таким образом: <code>Drums, Bells, *Whistles</code>. Количество символов не должно превышать 1000.</em>			 				
                    </td>
	 			</tr>
		      </table>
			<input name="ancestor_key" value="" type="hidden">
			<input name="item_id" value="<?php echo $item_id; ?>" type="hidden"/>
			<input name="item_action" value="add_item_field_to_base" type="hidden"/>
			<input class="button" value="Добавить" type="submit"/>	&nbsp; <a class="button-primary" href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/items.php&item_action=edit_item&item_id=<?php echo $item_id; ?>">Отмена</a>		
			</form>

		
	

							</div> 
					</div>
</div>
<div class="clear"></div>