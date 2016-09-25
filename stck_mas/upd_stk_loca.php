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
      $var_lotcd  = $_GET['lotcd'];
	  $var_lotde  = $_GET['lotde'];
	  $var_menucode = $_GET['menucd'];
	}
    
     if ($_POST['Submit'] == "Update") {
    
 		$lotcd  = $_POST['locacd'];
        $lotdesc = $_POST['locade'];
        $var_menucode  = $_POST['menudcode'];

        if ($lotcd <> "") {
        
         	$vartoday = date("Y-m-d H:i:s");
         	$sql = "Update stk_location set loca_desc ='$lotdesc',";
         	$sql .= " modified_by='$var_loginid',";
         	$sql .= " modified_on='$vartoday' WHERE loca_code = '$lotcd'";
       	 	mysql_query($sql) or die("Query Update :".mysql_error()); 
        
         	$backloc = "../stck_mas/stk_lotc.php?menucd=".$var_menucode;
         	echo "<script>";
         	echo 'location.replace("'.$backloc.'")';
         	echo "</script>";
      	}      
    }
    
    if ($_POST['Submit'] == "Back") {
       
         $var_menucode  = $_POST['menudcode'];
         $backloc = "../stck_mas/stk_lotc.php?menucd=".$var_menucode;
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


function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}		 	
		return xmlhttp;
}
	
function chkSubmit (getdata) {
 		
     	
 		if (document.getElementById("locade").value == "") {
      		alert ("Please fill in the Description to Continue");
      		document.InpColMas.locade.focus();
      		return false;
     	}
}     	
</script>
</head>
 <?php
 	$sql = "select create_by, 	create_on, 	modified_by, 	modified_on";
   	$sql .= " from stk_location";
   	$sql .= " where loca_code ='".$var_lotcd ."'";   

   	$sql_result = mysql_query($sql) or die(mysql_error());
   	 
   	$row = mysql_fetch_array($sql_result);

	$create_by = $row[0];
    $create_on = $row[1];
    $modified_by= $row[2];
 	$modified_on = $row[3];

?>

 
 <!--<?php include("../sidebarm.php"); ?> -->
<body OnLoad="document.InpColMas.locade.focus();">
 <?php include("../topbarm.php"); ?> 
<div class="contentc">

	<fieldset name="Group1" style="height: 238px; width: 718px;" class="style2">
	 <legend class="title">EDIT LOCATION CODE MASTER - <?php echo $var_clrcd; ?></legend>
	 <br>
	  <form name="InpColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px">
		 <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px">Location Code</td>
	  	    <td>:</td>
	  	    <td>
	  	   		<input class="inputtxt" name="locacd" id ="locacd" readonly="readonly" type="text" value="<?php echo $var_lotcd; ?>">
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
	  	    <td style="width: 138px" class="tdlabel">Location Description</td>
	  	    <td>:</td>
	  	    <td>
	  	     <input class="inputtxt" name="locade" id ="locade" type="text" maxlength="100" onchange ="upperCase(this.id)" style="width: 354px" value="<?php echo $var_lotde;?>">
			</td>
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
	  	   <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px" >
	  	   <input type=submit name = "Submit" value="Update" class="butsub" style="width: 60px; height: 32px" >
	  	   </td>
	  	  </tr>
	  	</table>
	   </form>	
	</fieldset>
</div>
</body>

</html>

