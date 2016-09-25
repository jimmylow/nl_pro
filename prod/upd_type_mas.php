<?php
	include("../Setting/Configifx.php");
	$db_link  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
   	mysql_query("SET character_set_results=utf8", $db_link) or die(mysql_error());
	mb_language('uni');
	mb_internal_encoding('UTF-8');
	mysql_select_db("$var_db_name", $db_link);
	mysql_query("set names 'utf8'",$db_link);
	$var_loginid = $_SESSION['sid'];
	$var_loginid = 'admin';
    
    if($var_loginid == "") { 
      echo "<script>";   
      echo "alert('Not Log In to the system');"; 
      echo 'window.close()';
      echo "</script>"; 
    } else {
      $var_typecd = $_GET['typecd'];
	  $var_typede = $_GET['typede'];
	}
    
     if ($_POST['Submit'] == "Update") {
    
 		$vartypecd = $_POST['typecd'];
        $vartypede = $_POST['typede'];
         if ($vartypecd <> "") {
        
         $vartoday = date("Y-m-d H:i:s");
         $sql = "Update type_master set type_desc ='$vartypede',";
         $sql .= " modified_by='$var_loginid',";
         $sql .= " modified_on='$vartoday' WHERE type_code = '$vartypecd'";
           
       	 mysql_query($sql); 
        
         echo "<script>";
         echo 'location.replace("../main_mas/type_master.php")';
         echo "</script>";
        
      }      
    }
    
    if ($_POST['Submit'] == "Back") {
    
 		 echo "<script>";
         echo 'location.replace("../main_mas/type_master.php")';
         echo "</script>";
   
    }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>EDIT COLOR CODE MASTER</title>

<style media="all" type="text/css">@import "../css/styles.css";
</style>
<script type="text/javascript"> 

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

</script>
</head>

<body OnLoad="document.InpTypeMas.typede.focus();">

	<fieldset name="Group1" style="height: 191px; width: 718px;" class="style2">
	 <legend class="title">EDIT TYPE MASTER</legend>
	  <br>
	
	  <form name="InpTypeMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Type Code</td>
	  	    <td>:</td>
	  	    <td>
	  	      <input readonly="readonly" class="inputtxt" name="typecd" id ="typecdid" type="text" value="<?php echo $var_typecd; ?>" style="width: 128px">
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
	  	     <input class="inputtxt" name="typede" id ="typedeid" type="text" maxlength="60" style="width: 354px" value="<?php echo $var_typede; ?>">
        	</td>
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

</body>

</html>

