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
      $itmcd = $_GET['pr'];
      $cstcd = $_GET['cs'];
      $desccd = $_GET['desc'];        
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

<title>RM Detail In Planing</title>
</head>
<?php
	$var_sql  = " SELECT rm_desc";
    $var_sql .= " from costing_matdet";
	$var_sql .= " Where rm_code = '$itmcd'";
	$var_sql .= " and costingno = '$cstcd'";
	$rs_result = mysql_query($var_sql); 
    $row2 = mysql_fetch_array($rs_result);
	$pcdde = htmlentities($row2[0]);
?>
<body>
	<fieldset>
	<table>
		<tr>
			<td></td>
			<td style="font-weight:bold">Item Code</td>
			<td>:</td>
			<td><input type="text" readonly="readonly" value="<?php echo htmlentities($itmcd); ?>">
				<label><?php echo $pcdde; ?></label>
			</td>
		</tr>
	</table>
	<br>
    <table width="100%">
		<tr>
			<th class="tabheader">No</th>
			<th class="tabheader">Buyer</th>
			<th class="tabheader">Sales Order</th>
			<th class="tabheader">Product Code</th>
			<th class="tabheader">Order Qty</th>
			<th class="tabheader">Unit Comps.</th>
			<th class="tabheader">Compsumtion</th>
		</tr>
		<?php
			$sql  = "select buycd, ordno, prodcd, sordqty, rm_ucoms, sum_comp";
            $sql .= " from costing_matdet";
			$sql .= " Where rm_code = '$itmcd'";
			$sql .= " and costingno = '$cstcd'";
			$sql .= " order by 1, 2, 3";
            $sql_result = mysql_query($sql) or die("Can't query Temp Table : ".mysql_error());
                
			$i = 1;
			$tqty = 0;
			$pqty = 0;
			$bqty = 0;	
            while ($row = mysql_fetch_assoc($sql_result)){

            	$buycd  = $row['buycd'];
                $ordno  = $row['ordno'];
                $prno   = $row['prodcd'];
                $ordqty = $row['sordqty'];
                $unicom = $row['rm_ucoms'];
                $scomp  = $row['sum_comp'];
            ?>
            <tr>
				<td><input name="seqno[]"  id="seqno"  value="<?php echo $i; ?>" readonly="readonly" style="width: 7px; border:0;"></td>
				<td><input name="probuy[]" id="probuy" value="<?php echo $buycd; ?>" readonly="readonly" style="width: 30px; border:0;"></td>
				<td><input name="proord[]" id="proord" value="<?php echo $ordno; ?>" readonly="readonly" style="width: 80px; border:0;"></td>
				<td><input name="prno[]"   id="prno"   value="<?php echo $prno; ?>" readonly="readonly" style="width: 100px; border:0;"></td>
				<td><input name="ordqty[]" id="ordqty" value="<?php echo $ordqty; ?>" readonly="readonly" style="width: 80px; border:0; text-align:right"></td>
				<td><input name="unicom[]" id="unicom" value="<?php echo $unicom; ?>" readonly="readonly" style="width: 80px; border:0; text-align:right"></td>
				<td><input name="scomp[]"  id="scomp" value="<?php echo $scomp; ?>" readonly="readonly" style="width: 80px; border:0; text-align:right"></td>
			</tr>    
			<?php
				$i = $i + 1;
				$tqty = $tqty + $scomp;
 			}
		?>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td style="text-align:right;">Total :</td>
			<td class="textnoentry1" style="text-align:right;"><?php echo number_format($tqty, "4",".",""); ?></td>
		</tr>
		<tr>
			<td colspan="9" align="center">
				<input type="button" value="Close" style="width: 60px; height: 32px" class="butsub" onclick="window.close()">
			</td>
		</tr>
	</table>
	</fieldset>
</body>

</html>
