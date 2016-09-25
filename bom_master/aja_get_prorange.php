<?php 

  include("../Setting/Configifx.php");
  include("../Setting/Connection.php");
	
  if(!isset($_GET['b']) || !$method = $_GET['b']) exit;
  if(!isset($_GET['t']) || !$method = $_GET['t']) exit; 
  if(!isset($_GET['p']) || !$method = $_GET['p']) exit;
    
  $buycd = $_GET['b'];
  $typcd = $_GET['t'];
  $precd = $_GET['p'];

  $query  = "SELECT pro_cat_frnum, pro_cat_tonum FROM pro_cat_master ";
  $query .= " WHERE pro_buy_cd ='$buycd' and pro_type_cd = '$typcd'";
  $query .= " AND pro_cat_prefix ='$precd'";
  $result = mysql_query($query) or die(mysql_error());
  $row = mysql_fetch_array($result);
  $frnum = $row['pro_cat_frnum'];
  $tonum = $row['pro_cat_tonum'];
  
  $txtrange = " (".$frnum." - ".$tonum.")";	
  echo $txtrange;
?>    