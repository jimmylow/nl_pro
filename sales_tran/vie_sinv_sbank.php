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
    }else{
      $sinv = $_GET['i'];
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
	$var_sql  = " SELECT * from ship_invmas";
	$var_sql .= " Where invno = '$sinv'";
	$rs_result = mysql_query($var_sql); 
    $row2 = mysql_fetch_array($rs_result);
	$banknm   = htmlentities($row2['sbknm']);
	$bankadd1 = htmlentities($row2['sbkadd1']);
	$bankadd2 = htmlentities($row2['sbkadd2']);
	$bankadd3 = htmlentities($row2['sbkadd3']);
	$sbanktel = htmlentities($row2['sbktel']);
	$sbankfax = htmlentities($row2['sbkfax']);
	$sbankacc = htmlentities($row2['sbkaccno']);
	$sbankswi = htmlentities($row2['sbkswicd']);
?>
<body>
	
	<fieldset>
	<form name="InpSalesF" method="post" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">

	<table width="100%">
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
				<input type="button" value="Close" class="butsub" style="width: 60px; height: 32px" onclick="javascript:window.close()" /> 
							</td>
		</tr>
	</table>
	</form>
	</fieldset>
	
</body>

</html>
