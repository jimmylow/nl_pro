<?php
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	$var_loginid = $_SESSION['sid'];
    
    if($var_loginid == "") { 
       echo "<script>";   
      echo "alert('Not Log In to the system');"; 
      echo "</script>"; 

      echo "<script>";
      echo 'top.location.href = "../index.php"';
      echo "</script>";
 
    } else {
      $var_uomcd  = $_GET['uomcd'];
	  $var_uomde = $_GET['uomde'];
	  $var_uompck = $_GET['uompck'];
	  $var_menucode = $_GET['menucd'];
	}
    
     if ($_POST['Submit'] == "Update") {
    
 		$var_uomcd  = $_POST['uomcdu'];
        $uomdescd = $_POST['uomdeu'];
        $uompck = $_POST['uompck'];
		$var_menucode  = $_POST['menudcode'];
         if ($var_uomcd <> "") {
        
         $vartoday = date("Y-m-d H:i:s");
         $sql = "Update uom_master set uom_desc ='$uomdescd',";
         $sql .= " uom_pack='$uompck',";
         $sql .= " modified_by='$var_loginid',";
         $sql .= " modified_on='$vartoday' WHERE uom_code = '$var_uomcd'";
    
       	 mysql_query($sql); 
        
         $backloc = "../stck_mas/uommas.php?menucd=".$var_menucode;
    
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
        
      }      
    }

	if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menudcode'];

         $backloc = "../stck_mas/uommas.php?menucd=".$var_menucode;
    
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<style media="all" type="text/css">@import "../css/styles.css";
.style1 {
	margin-left: 9px;
}
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
 	$sql = "select create_by, creation_time, modified_by, modified_on ";
   	$sql .= " from uom_master";
   	$sql .= " where uom_code ='".$var_uomcd ."'";   

   	$sql_result = mysql_query($sql) or die(mysql_error());
   	 
   	$row = mysql_fetch_array($sql_result);

	$create_by = $row[0];
    $create_on = $row[1];
    $modified_by= $row[2];
 	$modified_on = $row[3];

?>

<body OnLoad="document.InpColMas.uomdeu.focus();">
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc">

	<fieldset name="Group1" style="height: 341px; width: 715px;">
	 <legend class="title">EDIT STOCK UOM MASTER</legend>
	  <br>
	
	  <form name="InpColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px">
	    <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">UOM Code</td>
	  	    <td>:</td>
	  	    <td>
	  	    <?php
	  	       if (isset($var_uomcd)){
	  	       echo '<input readonly="readonly" class="inputtxt" name="uomcdu" id ="uomcdid" type="text" maxlength="20" onchange ="upperCase(this.id)" value="'.$var_uomcd.'">';
	  	       }else{
	  	       echo '<input readonly="readonly" class="inputtxt" name="uomcdu" id ="uomcdid" type="text" maxlength="20" onchange ="upperCase(this.id)">';
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
	  	    <td style="width: 138px" class="tdlabel">UOM Description</td>
	  	    <td>:</td>
	  	    <td>
	  	     <?php
	  	       if (isset($var_uomde)){
	  	       echo '<input class="inputtxt" name="uomdeu" id ="uomdeid" type="text" maxlength="50" style="width: 354px" onchange ="upperCase(this.id)" value="'.$var_uomde.'">';
	  	       }else{
	  	       echo '<input class="inputtxt" name="uomdeu" id ="uomdeid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 354px">';
	  	       }

	  	    ?>

			</td>
	  	  </tr> 
	  	  	   <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">UOM Pack</td>
	  	    <td>:</td>
	  	    <td>
	 	       <input class="inputtxt" name="uompck" id ="uompckid" type="text" maxlength="50" style="width: 354px" onchange ="upperCase(this.id)" value="<?php echo $var_uompck; ?>">
	  	     </td>
	  	  </tr> 
	  	  </tr> 
	  	  <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">&nbsp;</td>
	  	    <td>&nbsp;</td>
	  	    <td>
	 	       &nbsp;</td>
	  	  </tr> 
	  	  <tr>
	  	    	<td style="height: 22px"></td>
		   <td style="width: 745px; height: 22px;">Create By</td>
           <td style="height: 22px">:</td>
           <td style="width: 264px; height: 22px;">
			<input class="textnoentry1" name="create_by" id ="create_byid" type="text" readonly="readonly" style="width: 152px" value="<?php echo $create_by;?>"></td>
	  	    </tr>
			<tr>
	  	    	<td style="height: 24px"></td>
		   <td style="width: 745px; height: 24px;">Create On</td>
           <td style="height: 24px">:</td>
           <td style="width: 264px; height: 24px;">
		   <input class="textnoentry1" name="create_on" id ="create_onid" type="text" readonly="readonly" style="width: 152px" value="<?php echo $create_on;?>"></td>
	  	    </tr>
			<tr>
	  	    	<td>&nbsp;</td>
		   <td style="width: 745px">Modified By</td>
           <td>:</td>
           <td style="width: 264px">
	  	    <input class="textnoentry1" name="modified_by" id ="modified_byid" type="text" readonly="readonly" style="width: 152px" value="<?php echo $modified_by;?>"></td>
	  	    </tr>
			<tr>
	  	    	<td>&nbsp;</td>
		   <td style="width: 745px">Modified On</td>
           <td>:</td>
           <td style="width: 264px">
	  	     <input class="textnoentry1" name="modified_on" id ="suppstateid0" type="text" readonly="readonly" style="width: 152px" value="<?php echo $modified_on; ?>"></td>
	  	    </tr>


	  	  <tr>
	  	   <td>
	  	   </td>
	  	   <td>
	  	   </td>
	  	   <td>
	  	   </td>
	  	   <td>
	  	   <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px" ><input type=submit name = "Submit" value="Update" class="butsub" style="width: 60px; height: 32px" >
	  	   </td>
	  	  </tr>
	  	</table>
	   </form>
	</fieldset>
   </div>
</body>

</html>

