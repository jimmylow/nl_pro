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
      $var_countcd  = $_GET['countcd'];
	  $var_countde = $_GET['countde'];
	  $var_menucode = $_GET['menucd'];
	}
    
     if ($_POST['Submit'] == "Update") {
    
 		$var_countcd  = $_POST['countcdu'];
        $countdescd = $_POST['countdeu'];
        $var_menucode  = $_POST['menudcode'];
         if ($var_countcd <> "") {
        
         $vartoday = date("Y-m-d H:i:s");
         $sql = "Update country_master set country_desc ='$countdescd',";
         $sql .= " update_by='$var_loginid',";
         $sql .= " update_on='$vartoday' WHERE country_code = '$var_countcd'";
           
       	 mysql_query($sql); 
        
        $backloc = "../main_mas/country_mas.php?menucd=".$var_menucode;
 		 echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
      }      
    }
    
     if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menudcode'];
         $backloc = "../main_mas/country_mas.php?menucd=".$var_menucode;
        
 		 echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<style media="all" type="text/css">
@import "../css/styles.css";
</style>

<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript"> 

	function upperCase(x)
	{
		var y=document.getElementById(x).value;
		document.getElementById(x).value=y.toUpperCase();
	}

	function chkSubmit (getdata) { 	
 		if (document.getElementById("countdeid").value == "") {
      	alert ("Please fill in the Country Description to Continue");
      	return false;
     	}

 	}

</script>
</head>
<?php
 	$sql = "select create_by, 	create_on, 	update_by, 	update_on";
   	$sql .= " from country_master";
   	$sql .= " where country_code ='".$var_countcd ."'";   

   	$sql_result = mysql_query($sql) or die(mysql_error());
   	 
   	$row = mysql_fetch_array($sql_result);

	$create_by = $row[0];
    $create_on = $row[1];
    $modified_by= $row[2];
 	$modified_on = $row[3];

?>

  
  <!--<?php include("../sidebarm.php"); ?>--> 
<body OnLoad="document.InpCountMas.countdeu.focus();">

<?php include("../topbarm.php"); ?> 
  <div class="contentc" style="height: 407px">

	<fieldset name="Group1" style="height: 353px; width: 718px;" class="style2">
	 <legend class="title">EDIT COUNTRY MASTER</legend>
	  <br>
	
	  <form name="InpCountMas" method="POST" onSubmit= "return chkSubmit(this)" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 250px">
		<input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Country Code</td>
	  	    <td>:</td>
	  	    <td>
	  	      <input readonly="readonly" class="inputtxt" name="countcdu" id ="countcdid" type="text" maxlength="10" onchange ="upperCase(this.id)" value="<?php echo $var_countcd; ?>" style="width: 71px">
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
	  	    <td style="width: 138px" class="tdlabel">Description</td>
	  	    <td>:</td>
	  	    <td>
	  	     <input class="inputtxt" name="countdeu" id ="countdeid" type="text" maxlength="100" style="width: 515px" onchange ="upperCase(this.id)" value="<?php echo $var_countde; ?>">
        	</td>
	  	  </tr> 
	  	  </tr>  
	  	    <tr>
	  	    <td>
	  	    </td> 
	  	    <td style="width: 138px" class="tdlabel"></td>
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

