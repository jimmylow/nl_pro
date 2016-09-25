<?php 

  include("../Setting/Configifx.php");
	
	$db_link  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
  mysql_query("SET character_set_results=utf8", $db_link) or die(mysql_error());
	mb_language('uni');
	mb_internal_encoding('UTF-8');
	mysql_select_db("$var_db_name", $db_link);
	mysql_query("set names 'utf8'",$db_link);
	
	$prod_code=$_GET['productcode'];
	$vid=$_GET['vid'];
?>
	
	<select name="jobcode[]" id="jobcode<?php echo $vid; ?>" style="width: 50px" onchange="get_rate(this.value, '<?php echo $vid ?>', '<?php echo $prod_code ?>')">
<?php			 
    $sql = "select prod_jobid from pro_jobmodel where prod_code = '$prod_code' order by prod_jobid ASC ";
    $sql_result = mysql_query($sql);
    echo "<option size =50 selected></option>";
                       
			  if(mysql_num_rows($sql_result)) 
			  {
          while($row = mysql_fetch_assoc($sql_result)) 
          { 
            echo '<option value="'.$row['prod_jobid'].'"';;
            if ($vmcustcd == $row['prod_jobid']) { echo "selected"; }
            echo '>'.$row['prod_jobid'].'</option>';
          } 
		    } 
	         			   
	  echo "</select>";

?>
