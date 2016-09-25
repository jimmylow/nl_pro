<?php 

  include("../Setting/Configifx.php");
  include("../Setting/Connection.php");
	
  if(!isset($_GET['o']) || !$method = $_GET['o']) exit;
  if(!isset($_GET['b']) || !$method = $_GET['b']) exit;
  if(!isset($_GET['c']) || !$method = $_GET['c']) exit;
  if(!isset($_GET['cdn']) || !$method = $_GET['cdn']) exit;
    
  $ord = $_GET['o'];
  $buy = $_GET['b'];
  $cat = $_GET['c'];
  $cdn = $_GET['cdn'];

  $query  = "SELECT colno FROM prodcutmas";
  $query .= " WHERE ordno='$ord' and buyno ='$buy'";
  $query .= " and   prodcat = '$cat'";
  $query .= " and   prodcnum  = '$cdn'";	
  $result = mysql_query($query) or die(mysql_error());
  
  $varsel = '<select name="cutprodcol" id="cutprodcol" style="width: 200px" onchange="disprmcode(this.value)">';
  $varsel .= '<option value =""></option>';                      
  if(mysql_num_rows($result)) 
  {
   	 while($row = mysql_fetch_assoc($result)) 
	 { 
	 	
	 	$prodcol = $row['colno'];
	 	
	    $varsel .= '<option value="'.$prodcol.'">'.$prodcol.'</option>';
	 } 
  } 
  
	$varsel .= "</select>";
		  
	 		  
  echo $varsel;			
?>   