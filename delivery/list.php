<script language="javascript" type="text/javascript"> var is_can_edit=true; var is_can_edit_email=true; </script>
<div class="metabox-holder has-right-sidebar">		
		
					<div class="icon32" id="icon-options-general"><br/></div>
					<h2>Настройки корзины</h2>
	

					<div id="post-body">
						<div class="has-sidebar-content" id="post-body-content">
                    <h2>Параметры доставки</h2>    
					<p>Здесь редактируются параметры доставки</p>
                    <form action="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/delivery.php" method="POST" name="edit_delivery">   
                    <table class="widefat">
			 	     <thead>
					    <tr>
												<th>№</th>
                                                <th>Название</th>
                                                <th>Ярлык</th>
												<th>Цена</th>
												<th>Действия</th>
						</tr>
				     </thead>
                      <?php
                    if($delivery_list=get_option('delivery_list')) {
                      //echo  " <pre>".print_r($delivery_list,1)." </pre>";
                      
                      foreach ($delivery_list as $delivery_item)  { ?>
                       <tr id="delivery_<?php echo $delivery_item['id']; ?>">
                                <td><?php echo $delivery_item['id']; ?></td>  
								<td><?php echo $delivery_item['title']; ?></td>
                                <td><?php echo $delivery_item['slug']; ?></td>
                                <td><?php echo $delivery_item['price']; ?></td>
								<td><a href="javascript:edit_item_<?php echo $delivery_item['id']; ?>('<?php echo $delivery_item['id']; ?>','<?php echo $delivery_item['title'];; ?>','<?php echo $delivery_item['slug']; ?>','<?php echo $delivery_item['price'] ?>');" class="more-common">Редактировать</a> | 
                                <script language="javascript" type="text/javascript">
                                function edit_item_<?php echo $delivery_item['id'];; ?>(item_id,title,slug,price) {
                                                if (is_can_edit) {
                                                is_can_edit=false;    
                                                jQuery.ajax ({url: '<?php echo plugins_url()."/".dirname(plugin_basename(__FILE__)); ?>/edit_delivery_ajax.php?item_id='+item_id+'&title='+title+'&slug='+slug+'&price='+price, //УРЛ, к которому мы обращаемся
	         type: 'GET', //тип: может быть GET или POST 
	         success: function(response){ //success - функция, которая вызывается, когда запрос прошёл успешно и данные (data) получены
	         document.getElementById('delivery_<?php echo $delivery_item['id']; ?>').innerHTML=response;
	       } }); 
                                                }
                                }
                                </script>
                                <a class="more-common-delete" href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/delivery.php&item_id=<?php echo $delivery_item['id']; ?>&edit_item=delete" >Удалить</a> |</td> 
					   </tr>
					 <?php  } } ?>
                     	
				<tfoot>
					    <tr>
												<th>№</th>
                                                <th>Название</th>
                                                <th>Ярлык</th>
												<th>Цена</th>
												<th>Действия</th>
						</tr>
				</tfoot>
			</table>
            </form>
            
            
            <br /><br />
            <form name="add_new_delivery" method="POST" action="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/delivery.php">
            <table class="widefat">
			 	     <thead>
					    <tr>
												<th>Название</th>
                                                <th>Ярлык</th>
												<th>Цена</th>
												<th></th>
						</tr>
				     </thead>
            
                       <tr>
								<td><input type="text" size="100" name="title" value="" class="delivery_title"  /></td>
                                <td><input type="text" size="100" name="slug" value=""  class="delivery_slug"  /></td>
                                <td><input type="text" size="100" name="price" value="" class="delivery_price" /></td>
								<td>Новая доставка</td> 
					   </tr>
			</table>
            <input type="hidden" name="edit_item" value="add_new" />        			
			<p><input type="submit" value="Добавить доставку" class="button action" /></p>
            </form>	
           
            <br /><br /><br /><br />
            
            
            
            
            
            
            
            
             <h2>Методы оплаты</h2>    
					<p>Здесь редактируются методы оплаты</p>
                    <form action="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/delivery.php" method="POST" name="edit_delivery">   
                    <table class="widefat">
			 	     <thead>
					    <tr>
												<th>№</th>
                                                <th>Название</th>
                                                <th>Ярлык</th>
												<th>Цена</th>
												<th>Действия</th>
						</tr>
				     </thead>
                      <?php
                    if($payment_list=get_option('payment_list')) {
                      foreach ($payment_list as $payment_item)  { ?>
                       <tr id="payment_<?php echo $payment_item['id']; ?>">
                                <td><?php echo $payment_item['id']; ?></td>  
								<td><?php echo $payment_item['title']; ?></td>
                                <td><?php echo $payment_item['slug']; ?></td>
                                <td><?php echo $payment_item['price']; ?></td>
								<td><a href="javascript:edit_payment_<?php echo $payment_item['id']; ?>('<?php echo $payment_item['id']; ?>','<?php echo $payment_item['title'];; ?>','<?php echo $payment_item['slug']; ?>','<?php echo $payment_item['price'] ?>');" class="more-common">Редактировать</a> | 
                                <script language="javascript" type="text/javascript">
                                function edit_payment_<?php echo $payment_item['id'];; ?>(item_id,title,slug,price) {
                                                if (is_can_edit) {
                                                is_can_edit=false;    
                                                jQuery.ajax ({url: '<?php echo plugins_url()."/".dirname(plugin_basename(__FILE__)); ?>/edit_payment_ajax.php?item_id='+item_id+'&title='+title+'&slug='+slug+'&price='+price, //УРЛ, к которому мы обращаемся
	         type: 'GET', //тип: может быть GET или POST 
	         success: function(response){ //success - функция, которая вызывается, когда запрос прошёл успешно и данные (data) получены
	         document.getElementById('payment_<?php echo $payment_item['id']; ?>').innerHTML=response;
	       } }); 
                                                }
                                }
                                </script>
                                <a class="more-common-delete" href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/delivery.php&item_id=<?php echo $payment_item['id']; ?>&edit_payment=delete" >Удалить</a> |</td> 
					   </tr>
					 <?php  } } ?>
                     	
				<tfoot>
					    <tr>
												<th>№</th>
                                                <th>Название</th>
                                                <th>Ярлык</th>
												<th>Цена</th>
												<th>Действия</th>
						</tr>
				</tfoot>
			</table>
            </form>
            
            
            <br /><br />
            <form name="add_new_payment" method="POST" action="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/delivery.php">
            <table class="widefat">
			 	     <thead>
					    <tr>
												<th>Название</th>
                                                <th>Ярлык</th>
												<th>Цена</th>
												<th></th>
						</tr>
				     </thead>
            
                       <tr>
								<td><input type="text" size="100" name="title" value="" class="delivery_title"  /></td>
                                <td><input type="text" size="100" name="slug" value=""  class="delivery_slug"  /></td>
                                <td><input type="text" size="100" name="price" value="" class="delivery_price" /></td>
								<td>Новая доставка</td> 
					   </tr>
			</table>
            <input type="hidden" name="edit_payment" value="add_new" />        			
			<p><input type="submit" value="Добавить метод оплаты" class="button action" /></p>
            </form>	
          
            
            <br /><br /><br /><br />
            
            
            
            
            
            
            
                    <h2>Настройка рассылки</h2>    
					<p>Здесь можно добавить и удалить e-mail рассылки заказа</p>
            		<form action="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/delivery.php" method="POST" name="edit_delivery_email">   
                    <table class="widefat">
			 	     <thead>
					    <tr>
												<th>№</th>
                                                <th>E-mail</th>
                                                <th>Описание</th>
												<th>Действия</th>
						</tr>
				     </thead>
                      <?php
                    if($delivery_email_list=get_option('delivery_email_list')) {
                     
                      foreach ($delivery_email_list as $delivery_email)  { ?>
                       <tr id="email_<?php echo $delivery_email['id']; ?>">
                                <td><?php echo $delivery_email['id']; ?></td>  
                                <td><?php echo $delivery_email['email']; ?></td>
                                <td><?php echo $delivery_email['email_descr']; ?></td>
								<td><a href="javascript:edit_email_<?php echo $delivery_email['id']; ?>('<?php echo $delivery_email['id']; ?>','<?php echo $delivery_email['email']; ?>','<?php echo $delivery_email['email_descr']; ?>');" class="more-common">Редактировать</a> | 
                                <script language="javascript" type="text/javascript">
                                function edit_email_<?php echo $delivery_email['id']; ?>(email_id,email,email_descr) {
                                                if (is_can_edit_email) {
                                                is_can_edit_email=false;    
                                                jQuery.ajax ({url: '<?php echo plugins_url()."/".dirname(plugin_basename(__FILE__)); ?>/edit_email_ajax.php?email_id='+email_id+'&email='+email+'&email_descr='+email_descr, //УРЛ, к которому мы обращаемся
	         type: 'GET', //тип: может быть GET или POST 
	         success: function(response){ //success - функция, которая вызывается, когда запрос прошёл успешно и данные (data) получены
	         document.getElementById('email_<?php echo $delivery_email['id']; ?>').innerHTML=response;
	       } }); 
                                                }
                                }
                                </script>
                                <a class="more-common-delete" href="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/delivery.php&email_id=<?php echo $delivery_email['id']; ?>&edit_email=delete" >Удалить</a> |</td> 
					   </tr>
					 <?php  } } ?>
                     	
				<tfoot>
					    <tr>
												<th>№</th>
                                                <th>E-mail</th>
                                                <th>Описание</th>
												<th>Действия</th>
						</tr>
				</tfoot>
			</table>
            </form>
            
            
            <br /><br />
            <form name="add_new_email" method="POST" action="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/delivery.php">
            <table class="widefat">
			 	     <thead>
					    <tr>
												<th>E-mail</th>
                                                <th>Описание</th>
												<th></th>
						</tr>
				     </thead>
            
                       <tr>
								<td><input type="text" size="100" name="email" value="" class="email-input"  /></td>
                                <td><input type="text" size="100" name="email_descr" value=""  class="email-descr"  /></td>
								<td>Новый email</td> 
					   </tr>
			</table>
            <input type="hidden" name="edit_email" value="add_new" />        			
			<p><input type="submit" value="Добавить email" class="button action" /></p>
            </form>	
					</div> 
					</div>
</div>
			
<div class="clear"></div>