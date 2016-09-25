<?php
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	$var_loginid = $_SESSION['sid'];
    
    if($var_loginid == "") { 
      echo "<script>";   
      echo "alert('Not Log In to the system');"; 
      echo "</script>"; 

      echo "<script>";
      echo 'top.location.href = "../login.htm"';
      echo "</script>";
 
    } else {
      $var_clrcd  = $_GET['clrcd'];
	  $var_clrde = htmlentities($_GET['clrde']);
	  $var_menucode = $_GET['menucd'];
	}
    
     if ($_POST['Submit'] == "Update") {
    
 		$var_clrcd  = mysql_real_escape_string($_POST['clrcdu']);
        $clrdescd   = mysql_real_escape_string($_POST['clrdeu']);
        $var_menucode  = $_POST['menudcode'];

         if ($var_clrcd <> "") {
        
         $vartoday = date("Y-m-d H:i:s");
         $sql = "Update pro_clr_master set clr_desc ='$clrdescd',";
         $sql .= " modified_by='$var_loginid',";
         $sql .= " modified_on='$vartoday' WHERE clr_code = '$var_clrcd'";
           
       	 mysql_query($sql); 
        
         $backloc = "../bom_master/prod_clr_master.php?menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
        
      }      
    }
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<style media="all" type="text/css">@import "../css/styles.css";
</style>

<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript"> 

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

</script>
</head>
<?php
 	$sql = "select created_by, created_on, modified_by, modified_on";
   	$sql .= " from pro_clr_master";
   	$sql .= " where clr_code ='$var_clrcd'";   
   	$sql_result = mysql_query($sql) or die(mysql_error()); 
   	$row = mysql_fetch_array($sql_result);

	$create_by = $row[0];
    $create_on = date("d-m-Y", strtotime($row[1]));
    $modified_by= $row[2];
 	$modified_on = date("d-m-Y", strtotime($row[3]));

?>

 <!--<?php include("../sidebarm.php"); ?> -->
<body OnLoad="document.InpColMas.clrdeu.focus();">

  <?php include("../topbarm.php"); ?> 
<div class="contentc">

	<fieldset name="Group1" style="height: 250px; width: 718px;" class="style2">
	 <legend class="title">EDIT PRODUCT COLOR CODE MASTER</legend>
	 <br>
	  <form name="InpColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px">
		 <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Product Color Code</td>
	  	    <td>:</td>
	  	    <td>
	  	    <?php
	  	       if (isset($var_clrcd)){
	  	       echo '<input readonly="readonly" class="inputtxt" name="clrcdu" id ="clrcdid" type="text" maxlength="20" onchange ="upperCase(this.id)" value="'.$var_clrcd.'">';
	  	       }else{
	  	       echo '<input readonly="readonly" class="inputtxt" name="clrcdu" id ="clrcdid" type="text" maxlength="20" onchange ="upperCase(this.id)">';
	  	       }
	  	    ?>
			
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td>
	  	    </td> 
	  	    <td style="width: 138px" class="tdlabel"></td>
	   	  </tr> 
	   	   <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Color Description</td>
	  	    <td>:</td>
	  	    <td>
	  	     <?php
	  	       if (isset($var_clrde)){
	  	       echo '<input class="inputtxt" name="clrdeu" id ="clrdeid" type="text" maxlength="50" style="width: 354px" onchange ="upperCase(this.id)" value="'.$var_clrde.'">';
	  	       }else{
	  	       echo '<input class="inputtxt" name="clrdeu" id ="clrdeid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 354px">';
	  	       }
	  	    ?>
			</td>
	  	  </tr>
	  	  <tr><td></td></tr>
		    <tr>
	  	  	<td></td>
	  	  	<td>Created By</td>
	  	  	<td>:</td>
	  	  	<td>
			<input type="text" class="textnoentry1" readonly="readonly" value="<?php echo $create_by; ?>" name="createby"></td>
	  	  </tr>
	  	  <tr><td></td></tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td>Created On</td>
	  	  	<td>:</td>
	  	  	<td>
			<input type="text" class="textnoentry1" readonly="readonly" value="<?php echo $create_on; ?>" name="createon"></td>
		  </tr>	
	  	  <tr><td></td></tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td>Modified By</td>
	  	  	<td>:</td>
	  	  	<td>
			<input type="text" class="textnoentry1" readonly="readonly" value="<?php echo $modified_by; ?>" name="modby"></td>
	  	  </tr>
	  	  <tr><td></td></tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td>Modified On</td>
	  	  	<td>:</td>
	  	  	<td>
			<input type="text" class="textnoentry1" readonly="readonly" value="<?php echo $modified_on; ?>" name="modon"></td>
	  	  </tr>
	  	  <tr><td></td></tr>
  
	  	  <tr>
	  	   <td></td>
	  	   <td></td>
	  	   <td></td>
	  	   <td>
	  	   <?php	 
    		 	$locatr = "prod_clr_master.php?menucd=".$var_menucode;
			
				echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
			?>

	  	   <input type=submit name = "Submit" value="Update" class="butsub" style="width: 60px; height: 32px" >
	  	   </td>
	  	  </tr>
	  	</table>
	   </form>	
	</fieldset>
</div>
</body>

</html>

