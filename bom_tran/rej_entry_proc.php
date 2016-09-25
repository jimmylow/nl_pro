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
      $var_menucode = $_GET['menucd'];
      $varprocd     = $_GET['procd'];
	}
	
	 if ($_POST['Submit'] == "Save") {
		$vmprodno       = mysql_real_escape_string($_POST['procd']);
		$vmrmk         = mysql_real_escape_string($_POST['rejrem']);
		$var_menucode  = $_POST['menudcode'];
		
		$var_sql = " SELECT count(*) as cnt from procos_appr";
	    $var_sql .= " Where pro_code = '$vmprodno'";
	    $query_id = mysql_query($var_sql) or die ("Cant Check Costing Approval Product Code");
	    $res_id = mysql_fetch_object($query_id);
             
        $vartoday = date("Y-m-d H:i:s");
	    if ($res_id->cnt > 0 ){    			
			 	$sql  = "Update procos_appr Set stat = 'REJECT', modified_by = '$var_loginid', modified_on = '$vartoday', ";
			 	$sql .= " statrmk ='$vmrmk'";
             	$sql .=	" Where pro_code ='".$vmprodno."'";
                mysql_query($sql) or die(mysql_error()." 1");		 	 
		}else{
		   	 	$sql  = "Insert Into procos_appr values";
             	$sql .=	" ('$vmprodno ','$var_loginid', '$vartoday', 'REJECT', '$vmrmk')";
                mysql_query($sql) or die(mysql_error()." 2");	
		}
		
        $backloc = "../bom_tran/procost_appr.php?stat=1&menucd=".$var_menucode;
        echo "<script>";
        echo "window.close();";
        echo "window.opener.location.reload();";
        echo "</script>"; 
        
		    
    }    
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" >

<style media="all" type="text/css">
@import "../css/styles.css";
</style>

<title>NL SYSTEM - Remark Entry Sales Order</title>
</head>
<?php
	    $sql = "select statrmk from procos_appr";
        $sql .= " where pro_code ='".mysql_real_escape_string($varprocd)."' ";
        $sql_result = mysql_query($sql) or die("error query remark :".mysql_error());
        $row = mysql_fetch_array($sql_result);
		$rmkupd = htmlentities($row[0]);
?>

<body>
	<center>
		<form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<input name="procd" type="hidden" value="<?php echo $varprocd ;?>">

		<table>
			<tr>
				<td align="center" colspan="4">
				  <h4>Remark Entry For Reject Product Costing Product Code : <?php echo $varprocd ;?></h4>	
				</td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td></td>
				<td>Remark</td>
				<td>:</td>
				<td>
					<input class="inputtxt" name="rejrem" id="rejrem" type="text" maxlength="200" style="width: 419px;" value="<?php echo $rmkupd; ?>">
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<td align="center" colspan="4">
				<input type="button" value="Close" style="width: 75px; height: 32px" class="butsub" onclick="window.close()">
				<input type="submit" name = "Submit" value="Save" style="width: 75px; height: 32px" class="butsub">
     			</td>
			</tr>	
		</table>
		</form>
	</center>
</body>

</html>
