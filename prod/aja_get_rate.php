<?php 

  include("../Setting/Configifx.php");
  include("../Setting/Connection.php");	

  $prod_code=$_GET['x'];
  $ticketno = $_GET['ticketno'];
  
  $sql="SELECT prod_jobid, prod_jobrate FROM pro_jobmodel WHERE prod_code='$prod_code' order by prod_jobseq ";
  
  //$sql = "SELECT productcode from sew_entry where ticketno = '$ticketno'";
  $sql_result = mysql_query($sql);
  $row = mysql_fetch_array($sql_result);
  $productcode = $row[0];
  //echo 'kkk - '.$sql;


  $result=mysql_query($sql) or die (mysql_error());
  
?>
	<select style="width:350px" name="selmaincode" tabindex="7" onchange="getRate(this.value)">
	<?php
	 	//echo "<option size =30 selected>".$prod_code."</option>";
	?> 
	  <?php while($row=mysql_fetch_array($result)) 
	    echo '<option value="'.$row['prod_jobid'].'">'.$row['prod_jobid'].'  - Job Rate - : '. $row['prod_jobrate']. '</option>';
	  ?>           
	</select>
