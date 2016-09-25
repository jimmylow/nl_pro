<?php 

  include("../Setting/Configifx.php");
  include("../Setting/Connection.php");	

  $country=$_GET['country'];
  $ticketno = $_GET['ticketno'];
    
  $sql = "SELECT productcode from sew_entry where ticketno = '$ticketno'";
  $sql_result = mysql_query($sql);
  $row = mysql_fetch_array($sql_result);
  $productcode = $row[0];
  //echo 'kkk - '.$sql;


  $result=mysql_query($sql) or die (mysql_error());
  
?>
	<select style="width:150px" name="selmaincode">
	<?php
	 	echo "<option size =30 selected>".$productcode."</option>";
	?> 
	  <?php while($row=mysql_fetch_array($result)) 
	    echo '<option value="'.$row['sprocd'].'">'.$row['sprocd'].'</option>';
	  ?>           
	</select>
