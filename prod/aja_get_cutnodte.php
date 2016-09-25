<?php 

  include("../Setting/Configifx.php");
  include("../Setting/Connection.php");
  if(!isset($_GET['o']) || !$method = $_GET['o']) exit;
  if(!isset($_GET['b']) || !$method = $_GET['b']) exit;
  if(!isset($_GET['c']) || !$method = $_GET['c']) exit;
  if(!isset($_GET['cdn']) || !$method = $_GET['cdn']) exit;
  if(!isset($_GET['col']) || !$method = $_GET['col']) exit;
    
  $ord = $_GET['o'];
  $buy = $_GET['b'];
  $cat = $_GET['c'];
  $cdn = $_GET['cdn'];
  $col = $_GET['col'];

  $query  = "SELECT distinct cutno FROM prodcutmas";
  $query .= " WHERE ordno='$ord' and buyno ='$buy'";
  $query .= " and   prodcat = '$cat' and colno = '$col'";
  $query .= " and   prodcnum  = '$cdn'";	
  $result = mysql_query($query) or die(mysql_error());
  
  $varsel = '<select name="cutno" id="cutno" style="width: 200px" onchange="dispcutdate(this.value)">';
  $varsel .= '<option value =""></option>';                      
  if(mysql_num_rows($result)) 
  {
   	 while($row = mysql_fetch_assoc($result)) 
	 { 
	 	
	 	$prodcut = $row['cutno'];
	 	
	    $varsel .= '<option value="'.$prodcut.'">'.$prodcut.'</option>';
	 } 
  } 
  
	$varsel .= "</select>";
		  
	 		  
  echo $varsel;			
?>   