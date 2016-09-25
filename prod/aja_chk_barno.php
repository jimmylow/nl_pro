<?php 

  include("../Setting/Configifx.php");
  include("../Setting/Connection.php");
	
  if(!isset($_GET['b']) || !$method = $_GET['b']) exit; 
  $barno = $_GET['b'];

  $query  = "SELECT count(*) FROM prodcutdone ";
  $query .= " WHERE barcodeno='$barno'";
  $result = mysql_query($query) or die(mysql_error());
  $row2 = mysql_fetch_array($result);
  $cnt = $row2[0];
    
  if ($cnt > 0){
   echo '1';
  }else{
   echo '0';	
  }			
?>           

