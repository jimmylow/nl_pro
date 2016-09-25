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
      $vartrx_no     = $_GET['trx_no'];
	}
	
	 if ($_POST['Submit'] == "Save") {
		$vmprodno       = mysql_real_escape_string($_POST['trx_no']);
		$vmrmk         = mysql_real_escape_string($_POST['rejrem']);
		$var_menucode  = $_POST['menudcode'];
		
 			
	 	$sql  = "Update po_over Set stat = 'REJECTED', appr_by = '$var_loginid', appr_on = '$vartoday', ";
	 	$sql .= " remark ='$vmrmk'";
     	$sql .=	" Where trx_no ='".$vmprodno."'";
        mysql_query($sql) or die("Error Update Approval Table : ".mysql_error(). ' Failed SQL is --> '. $sql);	 
        
		
		
		$sql = "SELECT * FROM rawmat_receive_over ";
     	$sql .= " Where rm_receive_id='".$vmprodno."'"; 
		$sql .= " ORDER BY seqno";  
		$rs_result = mysql_query($sql); 
	   //echo $sql; break;
	    $i = 1;
		while ($rowq = mysql_fetch_assoc($rs_result)){
		
			$item_code = $rowq['item_code'];
			
			
			$sql = "DELETE FROM rawmat_tran "; 
			$sql .= "WHERE rm_receive_id ='".$vmprodno."' AND tran_type = 'REC'";  
			$sql .= " AND item_code = '". $item_code."'";
			mysql_query($sql) or die("Error Delete Raw Mat Trans  : ".mysql_error(). ' Failed SQL is --> '. $sql);	
		}


	
         	 	 	 
                
	 

        $backloc = "../stck_tran/poover_appr.php?stat=1&menucd=".$var_menucode;
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
        $sql .= " where pro_code ='".mysql_real_escape_string($vartrx_no)."' ";
        $sql_result = mysql_query($sql) or die("error query remark :".mysql_error());
        $row = mysql_fetch_array($sql_result);
		$rmkupd = htmlentities($row[0]);
?>

<body>
	<center>
		<form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<input name="trx_no" type="hidden" value="<?php echo $vartrx_no ;?>">

		<table>
			<tr>
				<td align="center" colspan="4">
				  <h4>Remark Entry For Reject Receiving Code : <?php echo $vartrx_no ;?></h4>	
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
