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
      $vardecd      = $_GET['cd'];
	  $vardede      = $_GET['de'];
	  $var_menucode = $_GET['menucd'];
	}
    
     if ($_POST['Submit'] == "Update") {
    
 		$vardecd  = $_POST['deptcd'];
        $vardede  = $_POST['deptde'];
        $var_menucode  = $_POST['menudcode'];

         if ($vardecd <> "") {
        
         	$vartoday = date("Y-m-d");
         	$sql = "Update wor_payrate set paydesc ='$vardede',";
         	$sql .= " modified_by='$var_loginid',";
         	$sql .= " modified_on='$vartoday' WHERE paycode = '$vardecd'";      
       	 	mysql_query($sql) or die('Unable Update Description '.mysql_error()); 
        
         	$backloc = "../workefile/wo_payrate_mas.php?menucd=".$var_menucode;
         	echo "<script>";
         	echo 'location.replace("'.$backloc.'")';
         	echo "</script>";
      	}      
    }
    
    if ($_POST['Submit'] == "Back") {
       
         $var_menucode  = $_POST['menudcode'];
         $backloc = "../workefile/wo_payrate_mas.php?menucd=".$var_menucode;
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

function chkSubmit () {
	if (document.getElementById("deptde").value == "") {
      	alert ("Please fill in the Description to Continue");
      	document.InpDeptMas.deptde.focus();
      	return false;
   	}
}	
</script>
</head>
<?php
 	$sql = "select create_by, create_on, modified_by, modified_on";
   	$sql .= " from wor_payrate";
   	$sql .= " where paycode ='$vardecd'";   
   	$sql_result = mysql_query($sql) or die(mysql_error());
   	$row = mysql_fetch_array($sql_result);

	$create_by = $row[0];
    $create_on = date("d-m-Y", strtotime($row[1]));
    $modified_by= $row[2];
 	$modified_on = date("d-m-Y", strtotime($row[3]));
?>
 <!--<?php include("../sidebarm.php"); ?> -->
<body OnLoad="document.InpDeptMas.deptde.focus();">
 <?php include("../topbarm.php"); ?> 
<div class="contentc">

	<fieldset name="Group1" style="height: 270px; width: 718px;" class="style2">
	 <legend class="title">EDIT PAYRATE CODE MASTER</legend>
	 <br>
	  <form name="InpDeptMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px" onSubmit= "return chkSubmit()">
		 <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 147px" class="tdlabel">Payrate Code</td>
	  	    <td>:</td>
	  	    <td>
	  	       <input readonly="readonly" name="deptcd" id ="deptcd" type="text" value="<?php echo $vardecd; ?>">
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td>
	  	    </td> 
	  	    <td style="width: 147px" class="tdlabel"></td>
	   	  </tr> 
	   	   <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 147px" class="tdlabel">Description</td>
	  	    <td>:</td>
	  	    <td>
				<input name="deptde" id ="deptde" type="text" maxlength="100" style="width: 354px" value="<?php echo $vardede;?>">
			</td>
	  	  </tr> 
	  	  <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 147px" class="tdlabel">&nbsp;</td>
	  	    <td>&nbsp;</td>
	  	    <td>
	  	     &nbsp;</td>
	  	  </tr>  
	  	    <tr>
	   	    <td>
	  	    </td>
		   <td style="width: 147px; height: 22px;">Create By</td>
           <td style="height: 22px">:</td>
           <td style="width: 264px; height: 22px;">
			<input class="textnoentry1" name="create_by" id ="create_byid" type="text" readonly="readonly" style="width: 152px" value="<?php echo $create_by;?>"></td>
	  	    </tr>
			<tr>
	   	    <td>
	  	    </td>
		   <td style="width: 147px; height: 24px;">Create On</td>
           <td style="height: 24px">:</td>
           <td style="width: 264px; height: 24px;">
		   <input class="textnoentry1" name="create_on" id ="create_onid" type="text" readonly="readonly" style="width: 152px" value="<?php echo $create_on;?>"></td>
	  	    </tr>
			<tr>
	   	    <td>
	  	    </td>
		   <td style="width: 147px">Modified By</td>
           <td>:</td>
           <td style="width: 264px">
	  	    <input class="textnoentry1" name="modified_by" id ="modified_byid" type="text" readonly="readonly" style="width: 152px" value="<?php echo $modified_by;?>"></td>
	  	    </tr>
			<tr>
	   	    <td>
	  	    </td>
		   <td style="width: 147px">Modified On</td>
           <td>:</td>
           <td style="width: 264px">
	  	     <input class="textnoentry1" name="modified_on" id ="suppstateid0" type="text" readonly="readonly" style="width: 152px" value="<?php echo $modified_on; ?>"></td>
	  	    </tr>
	  	  <tr>
	  	   <td>
	  	   </td>
	  	   <td style="width: 147px">
	  	   </td>
	  	   <td>
	  	   </td>
	  	   <td>
	  	    <?php
				 $locatr = "wo_payrate_mas.php?menucd=".$var_menucode;
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

