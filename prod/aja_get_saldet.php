<?php 

  include("../Setting/Configifx.php");
  include("../Setting/Connection.php");
	
  if(!isset($_GET['o']) || !$method = $_GET['o']) exit;
  if(!isset($_GET['b']) || !$method = $_GET['b']) exit;
    
  $ord = $_GET['o'];
  $buy = $_GET['b'];

  $query  = "SELECT distinct sorddte, sexpddte FROM salesentry ";
  $query .= " WHERE sordno='$ord' and sbuycd='$buy'";
  $result = mysql_query($query) or die(mysql_error());
  $row2 = mysql_fetch_array($result);
  
  if (!empty($row2[0])){
  	$row2[0] = date('d-m-Y', strtotime($row2[0]));
  }else{
  	$row2[0] = "";
  }
  
  if (!empty($row2[1])){
  	$row2[1] = date('d-m-Y', strtotime($row2[1]));
  }else{
  	$row2[1] = "";
  }		 		  
			
  $values = implode('^',$row2);
  echo $values;			
?>           

