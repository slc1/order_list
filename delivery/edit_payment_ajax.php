<?php 

require($_SERVER["DOCUMENT_ROOT"].'/wp-blog-header.php');
$title=(isset($_REQUEST["title"]) ? $_REQUEST["title"] : "");
$slug=(isset($_REQUEST["slug"]) ? $_REQUEST["slug"] : "");
$price=(isset($_REQUEST["price"]) ? $_REQUEST["price"] : "");
$item_id=(isset($_REQUEST["item_id"]) ? $_REQUEST["item_id"] : "");

?>
							
                            	<td><?php echo $item_id; ?></td>
                                <td><input type="text" size="100" name="title" value="<?php echo $title; ?>" class="delivery_title"  /></td>
                                <td><input type="text" size="100" name="slug" value="<?php echo $slug; ?>"  class="delivery_slug"  /></td>
                                <td><input type="text" size="100" name="price" value="<?php echo $price; ?>" class="delivery_price" /></td>
								<td><input type="hidden" name="edit_payment" value="edit" />
                                    <input type="hidden" name="item_id" value="<?php echo $item_id; ?>" />
                                    <input type="submit" value="Редактировать метод" class="button action" /> <input type="button" value="Отмена" class="button action" onclick="javascript:location.href='admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/delivery.php'" /></td>
                        