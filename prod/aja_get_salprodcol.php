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

  $query  = "SELECT distinct y.prod_col FROM salesentrydet x, pro_cd_master y ";
  $query .= " WHERE x.sordno='$ord' and x.sbuycd='$buy'";
  $query .= " and   y.prod_code = x.sprocd";
  $query .= " and   y.prod_catpre = '$cat'";
  $query .= " and   y.pronumcode  = '$cdn'";	
  $result = mysql_query($query) or die(mysql_error());
  
  $varsel = '<select name="cutprodcol" id="cutprodcol" style="width: 200px" onchange="disprmcode(this.value)">';
  $varsel .= '<option value =""></option>';                      
  if(mysql_num_rows($result)) 
  {
   	 while($row = mysql_fetch_assoc($result)) 
	 { 
	 	
	 	$prodcol = $row['prod_col'];
	 	
	    $varsel .= '<option value="'.$prodcol.'">'.$prodcol.'</option>';
	 } 
  } 
  
	$varsel .= "</select>";
		  
	 		  
  echo $varsel;			
?>           

