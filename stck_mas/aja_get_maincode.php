<?php 

  include("../Setting/Configifx.php");
  include("../Setting/Connection.php");	

  $country=$_GET['country'];
  $query="SELECT rm_code FROM rawmat_master WHERE category='$country' and active_flag = 'ACTIVE'";

  $result=mysql_query($query);
?>
	<select style="width:100px" name="selmaincode" onchange="showUser(this.value)">
	 	<option></option>
	  <?php while($row=mysql_fetch_array($result)) 
	    echo '<option value="'.$row['rm_code'].'">'.$row['rm_code'].'</option>';
	  ?>           
	</select>
