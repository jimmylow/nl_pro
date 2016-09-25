<?php 

  include("../Setting/Configifx.php");
  include("../Setting/Connection.php");
	
  if(!isset($_GET['c']) || !$method = $_GET['c']) exit;
    
  $cutno = $_GET['c'];

  $query  = "SELECT distinct cutdte FROM prodcutmas ";
  $query .= " WHERE cutno ='$cutno'";
  $result = mysql_query($query) or die(mysql_error());
  $row    = mysql_fetch_array($result);
  $cutdte = date('d-m-Y', strtotime($row[0]));
  
  $query  = "SELECT sum(ordqty) FROM prodcutmas ";
  $query .= " WHERE cutno ='$cutno'";
  $result = mysql_query($query) or die(mysql_error());
  $row    = mysql_fetch_array($result);
  $qty    = number_format($row[0],0,".",",");
  
  $row_array['cd'] = $cutdte;
  $row_array['cq'] = $qty;
    	
  echo json_encode($row_array);
?> 