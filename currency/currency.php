<?php 
	if (isset($_POST['course_means'])){update_option('currency_course',$_POST['course_means']);} 
	?>
	<script type="text/javascript" language="javascript">
	 test_digit=function()  {
	 	var digit_pattern = /^\d+[.]?\d*$/;
if (digit_pattern.test(document.currency_form.course_means.value))
	 	document.currency_form.submit();
else
    alert('Ошибка в числе! \n Можно использовать только цифры от 0 до 9. \n В качестве делителя дробной части используеться точка (".").');
	 }
	</script>
	
	<div class="wrap">
	<h2>Установка курса валют</h2>
	
		<form method="post" action="admin.php?page=<?php echo dirname(plugin_basename(__FILE__)); ?>/currency.php" name="currency_form">
          1 доллар = <input type="text" name="course_means" style="width:40px;" value="<?php if (isset($_POST['course_means'])) {echo $_POST['course_means'];} else {echo get_option('currency_course');} ?>"/> гривен
	        <p>Используется мета currency_course</p>
			<p class="submit">
				<input type="button" class="button-primary" value="<?php _e('Сохранить изменения') ?>" onclick="test_digit();" />
			</p>
		</form>

        <?php $curr_ukr_course=file_get_contents('http://finance.ua');
		$div_start=strpos($curr_ukr_course, '<div id="portlet-currency-cash-cashless"');
		$div_end=strpos($curr_ukr_course, '<div id="portlet-currency-cash-forex"');
		
		$curr_ukr_course=substr($curr_ukr_course,$div_start,$div_end-$div_start);
		
		function win_to_utf($s)
{
for($i=0, $m=strlen($s); $i<$m; $i++)
{
$c=ord($s[$i]);
if ($c<=127)
{$t.=chr($c); continue; }
if ($c>=192 && $c<=207)
{$t.=chr(208).chr($c-48); continue; }
if ($c>=208 && $c<=239)
{$t.=chr(208).chr($c-48); continue; }
if ($c>=240 && $c<=255)
{$t.=chr(209).chr($c-112); continue; }
if ($c==184) { $t.=chr(209).chr(209);
continue; };
if ($c==168) { $t.=chr(208).chr(129);
continue; };
}
return $t;
}

		
		//echo '<br>'.$div_start.'<br>'.$div_end.'<br>';
		//echo '<link rel="stylesheet" href="http://static.finance.ua/css/layout-1.9.css" type="text/css" media="all">';
		echo win_to_utf($curr_ukr_course);
		?>


	</div>

