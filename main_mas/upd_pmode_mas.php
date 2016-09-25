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
      $var_pmcode  = $_GET['pmcodecd'];
	  $var_pmcodede = $_GET['pmcodede'];
	  $var_menucode = $_GET['menucd'];
	}
    
     if ($_POST['Submit'] == "Update") {
    
 		$var_pmcode  = $_POST['pmodecdu'];
        $pmodedescd = $_POST['pmodedeu'];
		$var_menucode  = $_POST['menudcode'];
		
        if ($var_pmcode <> "") {
        
         $vartoday = date("Y-m-d H:i:s");
         $sql = "Update pay_m_master set pmdesc ='$pmodedescd',";
         $sql .= " modified_by='$var_loginid',";
         $sql .= " modified_on='$vartoday' WHERE pmcode = '$var_pmcode'";
           
       	mysql_query($sql); 
        
        $backloc = "../main_mas/pmode_master.php?menucd=".$var_menucode;
 		echo "<script>";
        echo 'location.replace("'.$backloc.'")';
        echo "</script>";
        
      }      
    }
    
    if ($_POST['Submit'] == "Back") {
        $var_menucode  = $_POST['menudcode'];

        $backloc = "../main_mas/pmode_master.php?menucd=".$var_menucode;
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

</script>
</head>

<?php
 	$sql = "select create_by, 	create_on, 	modified_by, 	modified_on";
   	$sql .= " from pay_m_master";
   	$sql .= " where pmcode ='".$var_pmcode ."'";   

   	$sql_result = mysql_query($sql) or die(mysql_error());
   	 
   	$row = mysql_fetch_array($sql_result);

	$create_by = $row[0];
    $create_on = $row[1];
    $modified_by= $row[2];
 	$modified_on = $row[3];

?>

 
  <!--<?php include("../sidebarm.php"); ?> -->

<body OnLoad="document.InpPCodeMas.pmodedeu.focus();">
	 <?php include("../topbarm.php"); ?> 
  <div class="contentc">

	<fieldset name="Group1" style="height: 325px; width: 718px;" class="style2">
	 <legend class="title">EDIT TERM MASTER</legend>
	  <br>
	
	  <form name="InpPCodeMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 250px">
	    <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Payment Mode Code</td>
	  	    <td>:</td>
	  	    <td>
	  	      <input readonly="readonly" class="inputtxt" name="pmodecdu" id ="pmodecdid" type="text" maxlength="5" onchange ="upperCase(this.id)" value="<?php echo $var_pmcode; ?>" style="width: 44px">
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
	  	     <input class="inputtxt" name="pmodedeu" id ="pmodedeid" type="text" maxlength="60" style="width: 486px" onchange ="upperCase(this.id)" value="<?php echo $var_pmcodede; ?>">
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

