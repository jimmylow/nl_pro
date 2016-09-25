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
    }

	if ($_POST['Submit'] == "Save") {
		$sbanknm   = mysql_real_escape_string($_POST['sbanknm']);
		$sbankadd1 = mysql_real_escape_string($_POST['sbankadd1']);
		$sbankadd2 = mysql_real_escape_string($_POST['sbankadd2']);
		$sbankadd3 = mysql_real_escape_string($_POST['sbankadd3']);
		$sbanktel  = mysql_real_escape_string($_POST['sbanktel']);
		$sbankfax  = mysql_real_escape_string($_POST['sbankfax']);
		$sbankacc  = mysql_real_escape_string($_POST['sbankacc']);
		$sbankswi  = mysql_real_escape_string($_POST['sbankswi']);
		
		if (!empty($sbanknm)){
			$sql = "delete from tmpinvshipremark where usernm = '$var_loginid'";
			mysql_query($sql) or die(mysql_error());
                        	
			$sql  = "INSERT INTO tmpinvshipremark (usernm, banknm, bankadd1, bankadd2, bankadd3, tel, fax, accno, swiftno) ";
			$sql .= " values ('$var_loginid', '$sbanknm', '$sbankadd1', '$sbankadd2', '$sbankadd3', '$sbanktel', '$sbankfax', ";
			$sql .= "  '$sbankacc','$sbankswi')"; 
			mysql_query($sql) or die("Error insert Sales Entry:".mysql_error(). ' Failed SQL is --> '. $sql);
		
			echo "<script>";
    	  	echo 'window.close()';
    		echo "</script>";
		}
    }
    
	 if ($_POST['Submit'] == "Copy") {

    	$sinvd = $_POST['scopyfrm'];
    	$sql = " select * from ship_invmas";
        $sql .= " where invno ='".$sinvd."'";
        $rs_result = mysql_query($sql);
        $rowq = mysql_fetch_assoc($rs_result);
        
		$banknm   = htmlentities($rowq['sbknm']);
		$bankadd1 = htmlentities($rowq['sbkadd1']);
	    $bankadd2 = htmlentities($rowq['sbkadd2']);
	    $bankadd3 = htmlentities($rowq['sbkadd3']);
	    $sbanktel = htmlentities($rowq['sbktel']);
	    $sbankfax = htmlentities($rowq['sbkfax']);
	    $sbankacc = htmlentities($rowq['sbkaccno']);
	    $sbankswi = htmlentities($rowq['sbkswicd']);
    }      
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<style media="all" type="text/css">
@import "../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css";
@import "../css/styles.css";

body{
	overflow:scroll;
}

</style>

<title>SHIPPER BANK DETAIL</title>
</head>
<?php
	if ($_POST['Submit'] <> "Copy") {
	$var_sql  = " SELECT * from tmpinvshipremark";
	$var_sql .= " Where usernm = '$var_loginid'";
	$rs_result = mysql_query($var_sql); 
    $row2 = mysql_fetch_array($rs_result);
	$banknm   = htmlentities($row2[1]);
	$bankadd1 = htmlentities($row2[2]);
	$bankadd2 = htmlentities($row2[3]);
	$bankadd3 = htmlentities($row2[4]);
	$sbanktel = htmlentities($row2[5]);
	$sbankfax = htmlentities($row2[6]);
	$sbankacc = htmlentities($row2[7]);
	$sbankswi = htmlentities($row2[8]);
	}
?>
<body>
	
	<fieldset>
	<form name="InpSalesF" method="post" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">

	<table width="100%">
		<tr>
			<td></td>
			<td style="font-weight:bold">Copy From</td>
			<td>:</td>
			<td>
				<select name="scopyfrm" id="scopyfrm" style="width: 200px" >
			 	<?php
             		 $sql = "select invno from ship_invmas ORDER BY invdte desc";
             		 $sql_result = mysql_query($sql);
             		 echo "<option size =30 selected></option>";
                       
			 		 if(mysql_num_rows($sql_result)) 
			 		 {
			 		  while($row = mysql_fetch_assoc($sql_result)) 
			 		  { 
						echo '<option value="'.$row['invno'].'">'.$row['invno'].'</option>';
			 		  } 
		     		 } 
	         	?>				   
	            </select>
				<input type="submit" name="Submit" value="Copy" class="butsub" style="width: 54px; height: 22px" onclick="" />
			</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td style="font-weight:bold">Bank Name</td>
			<td>:</td>
			<td>
				<input type="text" name="sbanknm" id="sbanknm" value="<?php echo htmlentities($banknm); ?>" style="width: 248px" />
			</td>
			<td></td>
			<td style="font-weight:bold">Tel</td>
			<td>:</td>
			<td>
				<input type="text" name="sbanktel" id="sbanktel" value="<?php echo htmlentities($sbanktel); ?>" style="width: 191px" />
			</td>
		</tr>
		<tr>
			<td></td>
			<td style="font-weight:bold">Address</td>
			<td>:</td>
			<td>
				<input type="text" name="sbankadd1" id="sbankadd1" value="<?php echo htmlentities($bankadd1); ?>" style="width: 244px" />
			</td>
			<td></td>
			<td style="font-weight:bold">Fax</td>
			<td>:</td>
			<td>
				<input type="text" name="sbankfax" id="sbankfax" value="<?php echo htmlentities($sbankfax); ?>" style="width: 187px" />
			</td>
		</tr>
		<tr>
			<td></td>
			<td style="font-weight:bold"></td>
			<td></td>
			<td>
				<input type="text" name="sbankadd2" id="sbankadd2" value="<?php echo htmlentities($bankadd2); ?>" style="width: 244px" />
			</td>
			<td></td>
			<td style="font-weight:bold">Account No</td>
			<td>:</td>
			<td>
				<input type="text" name="sbankacc" id="sbankacc" value="<?php echo htmlentities($sbankacc); ?>" style="width: 244px" />
			</td>
		</tr>
		<tr>
			<td></td>
			<td style="font-weight:bold"></td>
			<td></td>
			<td>
				<input type="text" name="sbankadd3" id="sbankadd3" value="<?php echo htmlentities($bankadd3); ?>" style="width: 238px" />
			</td>
			<td></td>
			<td style="font-weight:bold">SWIFT No</td>
			<td>:</td>
			<td>
				<input type="text" name="sbankswi" id="sbankswi" value="<?php echo htmlentities($sbankswi); ?>" />
			</td>
		</tr>


		<tr>
			<td colspan="8" align="center">
				<input type="submit" value="Save" name = "Submit" class="butsub" style="width: 60px; height: 32px" />
			</td>
		</tr>
	</table>
	</form>
	</fieldset>
	
</body>

</html>
