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
      $var_grpcd  = $_GET['grpcd'];
	  $var_grpde = $_GET['grpde'];
	  $var_menucode = $_GET['menucd'];
	  

	}
    
     if ($_POST['Submit'] == "Update") {
        $var_grpcd  = $_POST['itmgrpcd'];
        $var_grpde = $_POST['itmgrpde'];

		$var_menucode  = $_POST['menudcode'];
         if ($var_grpcd <> "") {
        
         $vartoday = date("Y-m-d H:i:s");
         $sql = "Update item_group_master set itm_grp_desc ='$var_grpde',";
         $sql .= " modified_by='$var_loginid',";
         $sql .= " modified_on='$vartoday' WHERE itm_grp_cd = '$var_grpcd'";
         mysql_query($sql); 
        
         $backloc = "../stck_mas/itm_group_master.php?menucd=".$var_menucode;
 		 echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
      }      
    }
    
    if ($_POST['Submit'] == "Back") {
	    $var_menucode  = $_POST['menudcode'];
        $backloc = "../stck_mas/itm_group_master.php?menucd=".$var_menucode;
        
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
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript"> 

	function upperCase(x)
	{
	var y=document.getElementById(x).value;
	document.getElementById(x).value=y.toUpperCase();
	}

	function isNumberKey(evt)
   {
   	var charCode = (evt.which) ? evt.which : event.keyCode
   	 if (charCode != 46 && charCode > 31 
   	&& (charCode < 48 || charCode > 57))
	   return false;
    return true;
   }

</script>
</head>
 <?php
 	$sql = "select create_by, create_on, modified_by, modified_on ";
   	$sql .= " from item_group_master";
   	$sql .= " where itm_grp_cd ='".$var_grpcd ."'";   

   	$sql_result = mysql_query($sql) or die(mysql_error());
   	 
   	$row = mysql_fetch_array($sql_result);

	$create_by = $row[0];
    $create_on = $row[1];
    $modified_by= $row[2];
 	$modified_on = $row[3];

?>

 
<body OnLoad="document.InpGrpMas.itmgrpde.focus();">
  <?php include("../topbarm.php"); ?> 
   <!--<?php include("../sidebarm.php"); ?> -->
   <div class="contentc">
	<form name="InpGrpMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
     <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
    	<fieldset name="Group1" style="height: 204px; width: 610px;" class="style2">
	 <legend class="title">EDIT ITEM GROUP CODE</legend>
	
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Item Group Code</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="itmgrpcd" id ="itmgrpcdid" type="text" readonly="readonly" value="<?php echo $var_grpcd; ?>">
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td style="width: 138px" class="tdlabel"></td>
	  	    <td></td> 
            <td><div id="msgcd"></div></td> 
	   	  </tr> 
	   	   <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Description</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="itmgrpde" id ="itmgrpdeid" type="text" maxlength="80" style="width: 354px" onchange ="upperCase(this.id)" value="<?php echo $var_grpde; ?>"/>
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td style="width: 138px" class="tdlabel"></td>
	  	    <td></td> 
            <td><div id="msgcd"></div></td> 
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
	</fieldset>
  </form>
  </div>
</body>

</html>

