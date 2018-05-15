<?php
/*
Plugin Name: Order List
Plugin URI: http://slc.org.ua/
Description:Список заказов - плагин для управления списком заказов веб-магазина
Version: 0.0.1
Author: SLC
Author http://slc.org.ua/
*/
?>
<?php
/*  Copyright 2010 SLC (email : sasha@dglance.com)


*/
?>
<?php

/*************************************************/ 
 //error_reporting(E_ALL);
 //ini_set('display_errors', true);
 
 $pluginprefix = isset($pluginprefix) ? $pluginprefix: "";
 if (!class_exists("ol_prg_table_class")){
	include_once "includes/class.table.php";
}
 
if (!class_exists("ol_prg")){	
 class ol_prg extends ol_prg_table_class{
 
	 var $tablename = 'ol_prg';
	 var $pluginname = 'ol_prg';
	 var $plugintitle = 'Order list';
	 var $db_version = '1.0';
	 var $file = __FILE__;
	 
	 var $tabledef = array();
	 var $rowdef = array();
	 var $editdef = array();
	 
	 public function __construct($prefix){ 
		$this->pluginprefix = $prefix;
	 
		$this->tabledef[1] = array("field"=>"ID","type"=>"bigint", "length"=>"10", "null"=>"NOT NULL","extra"=>"AUTO_INCREMENT");
		$this->tabledef[2] = array("field"=>"date","type"=>"datetime","null"=>"NOT NULL",   "header"=>"Дата");
		$this->tabledef[3] = array("field"=>"IP","type"=>"varchar", "length"=>"20","null"=>"NOT NULL");
		$this->tabledef[4] = array("field"=>"title","type"=>"varchar", "length"=>"255","null"=>"NOT NULL",   "header"=>"ФИО");
		$this->tabledef[5] = array("field"=>"phone","type"=>"varchar", "length"=>"100", "null"=>"NOT NULL",   "header"=>"Телефон");
		$this->tabledef[6] = array("field"=>"email","type"=>"varchar", "length"=>"100", "null"=>"NOT NULL",   "header"=>"E-mail");
		$this->tabledef[7] = array("field"=>"list","type"=>"varchar",  "length"=>"4096", "null"=>"NOT NULL",  "header"=>"Заказ");
		$this->tabledef[8] = array("field"=>"region","type"=>"varchar",  "length"=>"255", "null"=>"NOT NULL",   "header"=>"Регион");
		$this->tabledef[9] = array("field"=>"address","type"=>"varchar",  "length"=>"255", "null"=>"NOT NULL",   "header"=>"Адрес");
		$this->tabledef[10] = array("field"=>"delivery_method","type"=>"varchar",  "length"=>"255", "null"=>"NOT NULL",   "header"=>"Способ доставки");
		$this->tabledef[11] = array("field"=>"full_cost","type"=>"varchar",  "length"=>"30", "null"=>"NOT NULL",   "header"=>"Полная цена");
		$this->tabledef[12] = array("field"=>"payment_method","type"=>"varchar",  "length"=>"255", "null"=>"NOT NULL",   "header"=>"Метод оплаты");
        $this->tabledef[13] = array("field"=>"is_paid","type"=>"varchar",  "length"=>"2048", "null"=>"NOT NULL",   "header"=>"Оплачено");
		$this->tabledef[14] = array("field"=>"active","type"=>"bool",  "null"=>"NOT NULL",   "header"=>"Акт.");
		
		$this->rowdef[1] = array("width"=>"15px");
		$this->rowdef[2] = array("width"=>"75px");
		$this->rowdef[3] = array("width"=>"50px");
		$this->rowdef[4] = array("width"=>"70px");
		$this->rowdef[5] = array("width"=>"60px");
		$this->rowdef[6] = array("width"=>"50px");
		$this->rowdef[7] = array("colspan"=>"3","truncate"=>"100","width"=>"150px");
		$this->rowdef[8] = array("width"=>"60px");
		$this->rowdef[9] = array("width"=>"60px");
		$this->rowdef[10] = array("width"=>"65px", "colspan"=>"2","truncate"=>"60");
        $this->rowdef[11] = array("width"=>"55px");
        $this->rowdef[12] = array("width"=>"55px");
		$this->rowdef[13] = array("width"=>"50px","truncate"=>"30",);
		$this->rowdef[14] = array("width"=>"27px");
		//$this->rowdef[10] = array("cell"=>"start","width"=>"50px");
		//$this->rowdef[11] = array("cell"=>"none");
		//$this->rowdef[12] = array("cell"=>"end");
		
		$this->editdef[1] = array("header"=>"#","row"=>"1","start"=>"true","readonly"=>"readonly");
		$this->editdef[2] = array("header"=>"Дата","row"=>"1","readonly"=>"readonly");
		$this->editdef[3] = array("header"=>"IP", "row"=>"1","end"=>"true","readonly"=>"readonly");
		$this->editdef[4] = array("header"=>"Фамилия, Имя, Отчество","htmlelement"=>"textarea","row"=>"2","start"=>"true");
		$this->editdef[5] = array("header"=>"Телефон","htmlelement"=>"textarea","row"=>"2");
		$this->editdef[6] = array("header"=>"E-mail","htmlelement"=>"textarea","row"=>"2","end"=>"true");
		$this->editdef[7] = array("htmlelement"=>"textarea","colspan"=>"3","header"=>"Содержание заказа", "height" => "200px");
		$this->editdef[8] = array("htmlelement"=>"textarea","row"=>"4","start"=>"true", "header"=>"Регион");
		$this->editdef[9] = array("htmlelement"=>"textarea","row"=>"4", "header"=>"Адрес");
		$this->editdef[10] = array("htmlelement"=>"textarea","row"=>"4","end"=>"true", "header"=>"Способ доставки");
		$this->editdef[11] = array("header"=>"Полная цена","row"=>"5","start"=>"true");
		$this->editdef[12] = array("row"=>"5", "header"=>"Метод оплаты");
        $this->editdef[13] = array("htmlelement"=>"textarea", "row"=>"5", "header"=>"Оплачено","end"=>"true");
		$this->editdef[14] = array("start"=>"true", "row"=>"6","end"=>"true","htmlelement"=>"checkbox", "header"=>"Активно");
		
		
	 }
	 
	 function get_select($context, $id = 0){
		$orderstatement = "";
		$filterstatement = "";
		$joinstatement = "";
		
		if ($context == "rows"){
			$orderstatement = " ORDER BY date";
			$filterstatement = "";
			$joinstatement = "";
		}
		if ($context == "form"){
			$orderstatement = "";
			$filterstatement = "";
			$joinstatement = "";
		}	
		if ($context == "widget"){
			$orderstatement = "";
			$filterstatement = "";
			$joinstatement = "";
		}			
		
		return parent::get_select($id, $joinstatement, $filterstatement, $orderstatement);
	 } 	 
 }
 
  $ol_prg = new ol_prg($pluginprefix);
  $ol_prg->init($ol_prg,'ol_prg');

	  
	function ol_prg_install(){
		//echo "installing";
		$this_table_obj = new ol_prg("");
		$this_table_obj->do_install();
	}
	  
	register_activation_hook(__FILE__,'ol_prg_install');
	register_deactivation_hook(__FILE__,'ol_prg_uninstall');
	  
  //debug code if it all doesn't work
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="ol_prg_install"){
	  $ol_prg->do_install(1);
	}
	if (isset($_REQUEST["action"]) && $_REQUEST["action"]=="ol_prg_uninstall"){
	  $ol_prg->do_uninstall();
	}
}else{
	ol_prg_options();
}
?>