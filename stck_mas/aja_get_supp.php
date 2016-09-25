<?php 

  include("../Setting/Configifx.php");
  include("../Setting/Connection.php");
	
	//$supp_code=$_GET['supp_code'];
	$supp_code=intval($_GET['supp_code']);

  $query="SELECT rm_code FROM rawmat_master WHERE active_flag = 'ACTIVE' ORDER BY rm_code ";

  $result=mysql_query($query);
?>
<select name="selmain_code" style="width: 200px" onchange="getItem(<?php echo $supp_code; ?>,this.value)">
 <option>-Select Master Code-</option>
  <?php while($row=mysql_fetch_array($result)) 
    echo '<option value="'.$row['rm_code'].'">'.$row['rm_code'].'</option>';
  ?>           
</select>
