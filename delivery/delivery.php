<?php
function rus2translit($string)
{
    $converter = array(
        'à' => 'a',   'á' => 'b',   'â' => 'v',
        'ã' => 'g',   'ä' => 'd',   'å' => 'e',
        '¸' => 'e',   'æ' => 'zh',  'ç' => 'z',
        'è' => 'i',   'é' => 'y',   'ê' => 'k',
        'ë' => 'l',   'ì' => 'm',   'í' => 'n',
        'î' => 'o',   'ï' => 'p',   'ð' => 'r',
        'ñ' => 's',   'ò' => 't',   'ó' => 'u',
        'ô' => 'f',   'õ' => 'h',   'ö' => 'c',
        '÷' => 'ch',  'ø' => 'sh',  'ù' => 'sch',
        'ü' => "",  'û' => 'y',   'ú' => "",
        'ý' => 'e',   'þ' => 'yu',  'ÿ' => 'ya',
        '³' => 'i',   '¿' => 'yi',  'º' => 'e',
 
        ' ' => '_', 
        
        'À' => 'A',   'Á' => 'B',   'Â' => 'V',
        'Ã' => 'G',   'Ä' => 'D',   'Å' => 'E',
        '¨' => 'E',   'Æ' => 'Zh',  'Ç' => 'Z',
        'È' => 'I',   'É' => 'Y',   'Ê' => 'K',
        'Ë' => 'L',   'Ì' => 'M',   'Í' => 'N',
        'Î' => 'O',   'Ï' => 'P',   'Ð' => 'R',
        'Ñ' => 'S',   'Ò' => 'T',   'Ó' => 'U',
        'Ô' => 'F',   'Õ' => 'H',   'Ö' => 'C',
        '×' => 'Ch',  'Ø' => 'Sh',  'Ù' => 'Sch',
        'Ü' => "",  'Û' => 'Y',   'Ú' => "",
        'Ý' => 'E',   'Þ' => 'Yu',  'ß' => 'Ya',
        '²' => 'I',   '¯' => 'Yi',  'ª' => 'E',
    );
    return strtr($string, $converter);
}




$delivery_action=(isset($_REQUEST["delivery_action"]) ? $_REQUEST["delivery_action"] : "");
$edit_delivery=(isset($_REQUEST["edit_item"]) ? $_REQUEST["edit_item"] : "");
$edit_email=(isset($_REQUEST["edit_email"]) ? $_REQUEST["edit_email"] : "");
$edit_payment=(isset($_REQUEST["edit_payment"]) ? $_REQUEST["edit_payment"] : "");


//------------------------------------------------------------------
  if ($edit_delivery=='' && $edit_email=='' && $edit_payment=='') include('list.php');
//------------------------------------------------------------------


//------------------------------------------------------------------
if ($edit_delivery=='add_new') {
    
$delivery_title=(isset($_REQUEST["title"]) ? $_REQUEST["title"] : "");
$delivery_slug=(isset($_REQUEST["slug"]) ? $_REQUEST["slug"] : "");
$delivery_price=(isset($_REQUEST["price"]) ? $_REQUEST["price"] : "");  

if ($delivery_slug=="") $item_slug=strtolower(rus2translit($delivery_title));  
    
   $delivery_list=get_option('delivery_list');
   if (count($delivery_list)==0) {$delivery_id=1;} else {
       $delivery_list_last=end($delivery_list); 
       $delivery_id=$delivery_list_last['id']+1; 
       //reset($delivery_list);
   } 
   $delivery_list[]=array("id" => $delivery_id, "title" => $delivery_title, "slug" => $delivery_slug, "price" => $delivery_price );
   update_option('delivery_list',$delivery_list);
   
   include('list.php');
}
//------------------------------------------------------------------


//------------------------------------------------------------------
if ($edit_delivery=='edit') {
    
$delivery_id=(isset($_REQUEST["item_id"]) ? $_REQUEST["item_id"] : "");    
$delivery_title=(isset($_REQUEST["title"]) ? $_REQUEST["title"] : "");
$delivery_slug=(isset($_REQUEST["slug"]) ? $_REQUEST["slug"] : "");
$delivery_price=(isset($_REQUEST["price"]) ? $_REQUEST["price"] : "");  

if ($delivery_slug=="") $item_slug=strtolower(rus2translit($delivery_title));  
    
   $delivery_list=get_option('delivery_list');
  
  foreach ($delivery_list as $delivery_list_key => $delivery_item)  { 
    if ($delivery_item['id']==$delivery_id) { 
        $delivery_list[$delivery_list_key]=array("id" => $delivery_id, "title" => $delivery_title, "slug" => $delivery_slug, "price" => $delivery_price );
        break;
    }
  }
   
   
   update_option('delivery_list',$delivery_list);
   
   include('list.php');
}
//------------------------------------------------------------------


//------------------------------------------------------------------
if ($edit_delivery=='delete') {
    
$delivery_id=(isset($_REQUEST["item_id"]) ? $_REQUEST["item_id"] : "");    
    
   $delivery_list=get_option('delivery_list');
   
  foreach ($delivery_list as $delivery_list_key => $delivery_item)  { 
    if ($delivery_item['id']==$delivery_id) { 
         unset($delivery_list[$delivery_list_key]);
         break;
      }
   }
   update_option('delivery_list',$delivery_list);
   
   include('list.php');
}
//------------------------------------------------------------------

//------------------------------------------------------------------
if ($edit_payment=='add_new') {
    
$delivery_title=(isset($_REQUEST["title"]) ? $_REQUEST["title"] : "");
$delivery_slug=(isset($_REQUEST["slug"]) ? $_REQUEST["slug"] : "");
$delivery_price=(isset($_REQUEST["price"]) ? $_REQUEST["price"] : "");  

if ($delivery_slug=="") $item_slug=strtolower(rus2translit($delivery_title));  
    
   $delivery_list=get_option('payment_list');
   if (count($delivery_list)==0) {$delivery_id=1;} else {
       $delivery_list_last=end($delivery_list); 
       $delivery_id=$delivery_list_last['id']+1; 
       //reset($delivery_list);
   } 
   $delivery_list[]=array("id" => $delivery_id, "title" => $delivery_title, "slug" => $delivery_slug, "price" => $delivery_price );
   update_option('payment_list',$delivery_list);
   
   include('list.php');
}
//------------------------------------------------------------------


//------------------------------------------------------------------
if ($edit_payment=='edit') {
    
$delivery_id=(isset($_REQUEST["item_id"]) ? $_REQUEST["item_id"] : "");    
$delivery_title=(isset($_REQUEST["title"]) ? $_REQUEST["title"] : "");
$delivery_slug=(isset($_REQUEST["slug"]) ? $_REQUEST["slug"] : "");
$delivery_price=(isset($_REQUEST["price"]) ? $_REQUEST["price"] : "");  

if ($delivery_slug=="") $item_slug=strtolower(rus2translit($delivery_title));  
    
   $delivery_list=get_option('payment_list');
  
  foreach ($delivery_list as $delivery_list_key => $delivery_item)  { 
    if ($delivery_item['id']==$delivery_id) { 
        $delivery_list[$delivery_list_key]=array("id" => $delivery_id, "title" => $delivery_title, "slug" => $delivery_slug, "price" => $delivery_price );
        break;
    }
  }
   
   
   update_option('payment_list',$delivery_list);
   
   include('list.php');
}
//------------------------------------------------------------------


//------------------------------------------------------------------
if ($edit_payment=='delete') {
    
$delivery_id=(isset($_REQUEST["item_id"]) ? $_REQUEST["item_id"] : "");    
    
   $delivery_list=get_option('payment_list');
   
  foreach ($delivery_list as $delivery_list_key => $delivery_item)  { 
    if ($delivery_item['id']==$delivery_id) { 
         unset($delivery_list[$delivery_list_key]);
         break;
      }
   }
   update_option('payment_list',$delivery_list);
   
   include('list.php');
}
//------------------------------------------------------------------


//------------------------------------------------------------------
if ($edit_email=='add_new') {
    
$email=(isset($_REQUEST["email"]) ? $_REQUEST["email"] : "");
$email_descr=(isset($_REQUEST["email_descr"]) ? $_REQUEST["email_descr"] : "");
   
   $delivery_list=get_option('delivery_email_list');
   if (count($delivery_list)==0) {$delivery_id=1;} else {
       $delivery_list_last=end($delivery_list); 
       $delivery_id=$delivery_list_last['id']+1; 
       //reset($delivery_list);
   } 
   $delivery_list[]=array("id" => $delivery_id, "email" => $email, "email_descr" => $email_descr);
   update_option('delivery_email_list',$delivery_list);
   
   include('list.php');
}
//------------------------------------------------------------------


//------------------------------------------------------------------
if ($edit_email=='edit') {
    
   
$email_id=(isset($_REQUEST["email_id"]) ? $_REQUEST["email_id"] : "");    
$email=(isset($_REQUEST["email"]) ? $_REQUEST["email"] : "");
$email_descr=(isset($_REQUEST["email_descr"]) ? $_REQUEST["email_descr"] : "");
   
   $delivery_list=get_option('delivery_email_list');
  
  foreach ($delivery_list as $delivery_list_key => $delivery_item)  { 
    if ($delivery_item['id']==$email_id) { 
        $delivery_list[$delivery_list_key]=array("id" => $email_id, "email" => $email, "email_descr" => $email_descr);
        
        break;
    }
  }
   
   
   update_option('delivery_email_list',$delivery_list);
   
   include('list.php');
}
//------------------------------------------------------------------


//------------------------------------------------------------------
if ($edit_email=='delete') {
    
$email_id=(isset($_REQUEST["email_id"]) ? $_REQUEST["email_id"] : "");    
    
   $delivery_list=get_option('delivery_email_list');
   
  foreach ($delivery_list as $delivery_list_key => $delivery_item)  { 
    if ($delivery_item['id']==$email_id) { 
         unset($delivery_list[$delivery_list_key]);
         break;
      }
   }
   update_option('delivery_email_list',$delivery_list);
   
   include('list.php');
}
//------------------------------------------------------------------



?>