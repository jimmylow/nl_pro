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
      $var_shiptcd  = $_GET['shiptecd'];
	  $var_shiptde = $_GET['shiptede'];
	  $var_menucode = $_GET['menucd'];
	}
    
     if ($_POST['Submit'] == "Update") {
    
 		$var_shiptecd  = $_POST['shiptecdu'];
        $var_shiptede = $_POST['shiptedeu'];
		$var_menucode  = $_POST['menudcode'];
         if ($var_shiptecd <> "") {
        
         $vartoday = date("Y-m-d H:i:s");
         $sql = "Update ship_term_master set shiptede ='$var_shiptede',";
         $sql .= " modify_by='$var_loginid',";
         $sql .= " modify_on='$vartoday' WHERE shiptecd = '$var_shiptecd'";
          
       	 mysql_query($sql); 
        
         $backloc = "../main_mas/ship_term_master.php?menucd=".$var_menucode;
        
 		echo "<script>";
        echo 'location.replace("'.$backloc.'")';
        echo "</script>";
        
      }      
    }
    
     if ($_POST['Submit'] == "Back") {
        $var_menucode  = $_POST['menudcode'];
        $backloc = "../main_mas/ship_term_master.php?menucd=".$var_menucode;
        
 		echo "<script>";
        echo 'location.replace("'.$backloc.'")';
        echo "</script>";
    }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>EDIT SHIPPING TERM CODE</title>

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

</script>
</head>
<?php
 	$sql = "select modify_by,	modify_on, 	create_by, 	create_on";
   	$sql .= " from ship_term_master";
   	$sql .= " where shiptecd ='".$_GET['shiptecd']  ."'";   
   	//echo $sql;

   	$sql_result = mysql_query($sql) or die(mysql_error());
   	 
   	$row = mysql_fetch_array($sql_result);

	$create_by = $row[0];
    $create_on = $row[1];
    $modified_by= $row[2];
 	$modified_on = $row[3];

?>
 
  <!--<?php include("../sidebarm.php"); ?> -->
<body OnLoad="document.InpShipTeMas.shiptedeu.focus();" style="height: 331px">
 <?php include("../topbarm.php"); ?> 
  <div class="contentc">

	<fieldset name="Group1" style="height: 319px; width: 718px;" class="style2">
	 <legend class="title">EDIT SHIPPING TERM : <?php echo $var_shiptcd; ?></legend>
	  <br>
	
	  <form name="InpShipTeMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px">
	     <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Ship Term Code</td>
	  	    <td>:</td>
	  	    <td>
	  	      <input readonly="readonly" class="inputtxt" name="shiptecdu" id ="shiptecdid" type="text" value="<?php echo $var_shiptcd; ?>" style="width: 71px">
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
	  	     <input class="inputtxt" name="shiptedeu" id ="shiptedeid" type="text" maxlength="60" style="width: 515px" value="<?php echo $var_shiptde; ?>">
        	</td>
	  	  </tr>  
	  	    <tr>
	  	    <td>
	  	    </td> 
	  	    <td style="width: 138px" class="tdlabel"></td>
	   	    </tr>
	   	    
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

