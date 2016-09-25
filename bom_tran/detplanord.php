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
      $procd = $_GET['pr'];
      $popt  = $_GET['opt'];
      $colcd = $_GET['col'];      
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

.style2 {
	margin-right: 0px;
}
</style>

<title>Sales Order Detail In Planing</title>
</head>

<body>
	<fieldset>
	<table>
		<tr>
			<td></td>
			<?php 
				if ($popt == 'a'){
			?>
				<td style="font-weight:bold">Colour Code</td>
			<?php
				}else{
			?>		
				<td style="font-weight:bold">Product Code</td>
			<?php
				}
			?>		
			<td>:</td>
			<td>
			<?php
				if ($popt == 'a'){
			?>
				<input type="text" readonly="readonly" value="<?php echo htmlentities($colcd); ?>" style="width: 61px">
			<?php
				}else{
			?>	
				<input type="text" readonly="readonly" value="<?php echo htmlentities($procd); ?>">
			<?php
				}
			?>		
			</td>
		</tr>
	</table>
	<br>
    <table width="100%">
            <?php
				if ($popt == 'a'){
			?>
			<tr>
				<th class="tabheader" style="width: 5px">No</th>
				<th class="tabheader" style="width: 80px">Buyer</th>
				<th class="tabheader" style="width: 100px">Sales Order</th>
				<th class="tabheader" style="width: 171px">Product Code</th>
				<th class="tabheader" style="width: 100px">Order Qty</th>
			</tr>
			<?php
				}else{
			?>	
			<tr>
				<th class="tabheader" style="width: 5px">No</th>
				<th class="tabheader" style="width: 80px">Buyer</th>
				<th class="tabheader" style="width: 150px">Sales Order</th>
				<th class="tabheader" style="width: 171px">Order Qty</th>
			</tr>
			<?php
				}
			?>	
		<?php
			if ($popt == 'a'){
				$sql  = "select sbuycd, sordno, procd, sum(ordqty)";
            	$sql .= " from tmpplanpro01";
				$sql .= " Where usernm = '$var_loginid'";
				$sql .= " and procdcol = '$colcd'";
				$sql .= " group by 1, 2, 3";
            	$sql_result = mysql_query($sql) or die("Can't query Temp Table : ".mysql_error());
			}else{
				$sql  = "select sbuycd, sordno, sum(ordqty)";
            	$sql .= " from tmpplanpro01";
				$sql .= " Where usernm = '$var_loginid'";
				$sql .= " and procd = '$procd'";
				$sql .= " group by 1, 2";
            	$sql_result = mysql_query($sql) or die("Can't query Temp Table : ".mysql_error());
            }    
                
			$i = 1;
			$tqty = 0;	
            while ($row = mysql_fetch_assoc($sql_result)){
				if ($popt == 'a'){
					$buycd = $row['sbuycd'];
                	$ordno = $row['sordno'];
                	$prdno = $row['procd'];
                	$prodqt = number_format($row['sum(ordqty)'], 0,".",",");
				}else{
            		$buycd = $row['sbuycd'];
                	$ordno = $row['sordno'];
                	$prodqt = number_format($row['sum(ordqty)'], 0,".",",");
            	}
            	
            	if ($popt == 'a'){
            ?>
            	<tr>
					<td><input name="seqno[]" id="seqno" value="<?php echo $i; ?>" readonly="readonly" style="width: 5px; border:0;"></td>
					<td><input name="probuy[]" id="probuy" value="<?php echo $buycd; ?>" readonly="readonly" style="width: 80px; border:0;"></td>
					<td><input name="proord[]" id="proord" value="<?php echo $ordno; ?>" readonly="readonly" style="width: 100px; border:0;"></td>
					<td style="width: 171px"><input name="procd[]" id="prcd" value="<?php echo $prdno; ?>" readonly="readonly" style="width: 150px; border:0;"></td>
					<td><input name="proqty[]" id="proqty" value="<?php echo $prodqt; ?>" readonly="readonly" style="width: 100px;text-align:right; border:0;"></td>
				</tr>  
            	<?php
            	}else{
            	?>
            	<tr>
					<td><input name="seqno[]" id="seqno" value="<?php echo $i; ?>" readonly="readonly" style="width: 5px; border:0;"></td>
					<td><input name="probuy[]" id="probuy" value="<?php echo $buycd; ?>" readonly="readonly" style="width: 80px; border:0;"></td>
					<td><input name="proord[]" id="proord" value="<?php echo $ordno; ?>" readonly="readonly" style="width: 150px; border:0;"></td>
					<td style="width: 171px"><input name="proqty[]" id="proqty" value="<?php echo $prodqt; ?>" readonly="readonly" style="width: 100px;text-align:right; border:0;"></td>
				</tr>    
			<?php
				}
				$i = $i + 1;
				$tqty = $tqty + $prodqt; 
 			}
		?>
		<tr>
			<?php
		    	if ($popt == 'a'){
		    ?>
		    <td></td>
			<td></td>
			<td></td>
			<td style="text-align:right;">Total :</td>
			<td class="textnoentry1" style="width: 171px"><?php echo $tqty; ?></td>
		    <?php
		    	}else{
		    ?>	
			<td></td>
			<td></td>
			<td style="text-align:right;">Total :</td>
			<td class="textnoentry1" style="width: 171px"><?php echo $tqty; ?></td>
			<?php } ?>
		</tr>
		<tr>
		    <?php
		    	if ($popt == 'a'){
		    ?>
		    <td colspan="5" align="center">
				<input type="button" value="Close" style="width: 60px; height: 32px" class="butsub" onclick="window.close()">
			</td>
		    <?php 
		    	}else{
		    ?>		
			<td colspan="4" align="center">
				<input type="button" value="Close" style="width: 60px; height: 32px" class="butsub" onclick="window.close()">
			</td>
			<?php } ?>
		</tr>
	</table>
	</fieldset>
</body>

</html>
