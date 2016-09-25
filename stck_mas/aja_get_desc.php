<?php 

  include("../Setting/Configifx.php");
  include("../Setting/Connection.php");
	
	//$main_code=$_GET['main_code'];
	$main_code=intval($_GET['main_code']);

  $query="SELECT rm_code FROM rawmat_master WHERE active_flag = 'ACTIVE' ORDER BY rm_code ";

  $result=mysql_query($query);
?>
<select name="selmain_code" style="width: 200px" onchange="getItem(<?php echo $main_code; ?>,this.value)">
 <option>-Select Master Code-</option>
  <?php while($row=mysql_fetch_array($result)) 
    echo '<option value="'.$row['rm_code'].'">'.$row['rm_code'].'</option>';
  ?>           
</select>
