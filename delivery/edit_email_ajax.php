<?php 

require($_SERVER["DOCUMENT_ROOT"].'/wp-blog-header.php');
$email=(isset($_REQUEST["email"]) ? $_REQUEST["email"] : "");
$email_id=(isset($_REQUEST["email_id"]) ? $_REQUEST["email_id"] : "");
$email_descr=(isset($_REQUEST["email_descr"]) ? $_REQUEST["email_descr"] : "");

?>
                  							
                            	<td><?php echo $email_id; ?></td>
                                <td><input type="text" size="100" name="email" value="<?php echo $email; ?>" class="email-input" /></td>
                                <td><input type="text" size="100" name="email_descr" value="<?php echo $email_descr; ?>" class="email-descr" /></td>
								<td><input type="hidden" name="edit_email" value="edit" />
                                    <input type="hidden" name="email_id" value="<?php echo $email_id; ?>" />
                                    <input type="submit" value="Редактировать email" class="button action" /> <input type="button" value="Отмена" class="button action" onclick="javascript:location.href='admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/delivery.php'" /></td>
                  