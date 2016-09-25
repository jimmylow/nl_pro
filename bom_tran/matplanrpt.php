<?php
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	$var_loginid = $_SESSION['sid'];

    if($var_loginid == "") { 
         echo "<script>";   
         echo "alert('Not Log In to the system');"; 
         echo "</script>"; 

         echo "<script>";
         echo 'top.location.href = "./index.html"';
         echo "</script>";
    }else{
 		 $refval = $_GET['opt'];
    }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Material Planning Report</title>
<style type="text/css">
<!--
@page { 
		size: A3 landscape;
  		margin: 2mm;
  		font-size:10px;
  		font-family:sans-serif;
}
-->

th    {border-top: 1px #ccc solid; border-bottom: 1px #ccc solid;}

td.h    { 
	border-bottom: 1px #ccc solid;
	margin:0;
 	padding: 2px;
}

@media print 
{
    .noPrint 
    {
        display:none;
    }
}
</style>    
<script type="text/javascript">
function confirmPrint()
{
   i = confirm("Do you want to print this Report?");		
   if(i)
	{
	  window.print();		  
	  setTimeout("window.close()",5000);
	}
}	  
</script>
</head>

<body >
	<div style="float: left;" id="print">
<input id="print-bnt" class ="noPrint" type="button" value="Print" onclick="confirmPrint()" style="width: 60px; height: 32px; background-color: #FFCC33; font-weight: bold"> </div>
<!-- ########################### Start Body ############################### -->
		<?php
			$budesc = '';
			$sql1  = "SELECT distinct sbuycd, pro_buy_desc from costing_matord ";
    	    $sql1 .= " where costingno ='$refval'"; 
			$rs_result1 = mysql_query($sql1) or die("Can't query costing_matord: ".mysql_error());
			while ($row1 = mysql_fetch_assoc($rs_result1)){
				$bucd = $row1['sbuycd'];
				$bude = $row1['pro_buy_desc'];
				if($budesc == ''){
					$budesc = trim($bucd)."(".trim($bude).")";
				}else{
					$budesc = trim($budesc).", ".trim($bucd)."(".trim($bude).")";
				}

			}
			
			$sql2  = "SELECT remark from costing_mat ";
    	    $sql2 .= " where costingno ='$refval'"; 
			$resultcp2 = mysql_query($sql2) or die("Can't query costing_mat: ".mysql_error());
			$rowcp2 = mysql_fetch_array($resultcp2);
			$rmk    = $rowcp2['remark'];
			
			$ordesc = '';
			$sql1  = "SELECT distinct sordno from costing_matord ";
    	    $sql1 .= " where costingno ='$refval'"; 
			$rs_result1 = mysql_query($sql1) or die("Can't query costing_matord: ".mysql_error());
			while ($row1 = mysql_fetch_assoc($rs_result1)){
				$orcd = $row1['sordno'];
				if($ordesc == ''){
					$ordesc = trim($orcd);
				}else{
					$ordesc = trim($ordesc).", ".trim($orcd);
				}

			}
			
			$cntprd = 0;
			$sql2  = "SELECT count(distinct procd) from costing_matord ";
    	    $sql2 .= " where costingno ='$refval'"; 
			$resultcp2 = mysql_query($sql2) or die("Can't query costing_matord: ".mysql_error());
			$rowcp2 = mysql_fetch_array($resultcp2);
			$cntprd = $rowcp2['count(distinct procd)'];

		?>
		<table width="100%">
			<tr>
				<td colspan="8" align="center"><h4>NYOK LAN GARMENTS SDN BHD (202814-K)</h4></td>
			</tr>
			<tr>
				<td colspan="8" align="center"><h4>MATERIAL PANNING REPORT</h4></td>
			</tr>
			<tr>
				<td style="width: 28px"></td>
				<td>Costing No</td>
				<td>:</td>
				<td><?php echo $refval;?></td>
				<td style="width: 200px"></td>
				<td>Print On</td>
				<td>:</td>
				<td><?php echo Date('d-m-Y'); ?></td>
			</tr>
			<tr>
				<td style="width: 28px"></td>
				<td>Customer</td>
				<td>:</td>
				<td><?php echo $budesc; ?></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td style="width: 28px"></td>
				<td>Remark</td>
				<td>:</td>
				<td><?php echo $rmk; ?></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td style="width: 28px"></td>
				<td>Order No</td>
				<td>:</td>
				<td colspan="5"><?php echo $ordesc; ?></td>
			</tr>
			<tr><td colspan="8">
			<table width="100%" cellspacing="0%" cellpadding="2%">
				<thead>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<th colspan="<?php echo $cntprd; ?>">Unit Consumption</th>
					<th colspan="<?php echo $cntprd; ?>">Qty Use</th>
					<td></td>
					<td></td>
					<td></td>
					<th>Qty Need</th>
					<th>Order</th>
				</tr>
				<tr>
					<th style="width: 100px">Item Code</th>
					<th style="width: 150px">Description</th>
					<th style="width: 50px">UOM</th>
					<th style="width: 80px">U.Price</th>
					<?php
						unset($sizarr);
						unset($prdarr);
						unset($qtyarr);
						$tordqty = 0;
						$sql1  = "SELECT distinct procd from costing_matord ";
    	    			$sql1 .= " where costingno ='$refval'"; 
    	    			$sql1 .= " order by procd";
						$rs_result1 = mysql_query($sql1) or die("Can't query costing_matord: ".mysql_error());
						$i = 0;
						while ($row1 = mysql_fetch_assoc($rs_result1)){
							$prdarr[$i] = $row1['procd'];
							
							$sql2  = "SELECT prod_size from pro_cd_master ";
    	    				$sql2 .= " where prod_code ='$prdarr[$i]'"; 
							$resultcp2 = mysql_query($sql2) or die("Can't query pro_cd_master: ".mysql_error());
							$rowcp2 = mysql_fetch_array($resultcp2);
							$sizarr[$i] = $rowcp2['prod_size'];
							
							$sql2  = "SELECT distinct sordqty from costing_matdet ";
    	    				$sql2 .= " where prodcd ='$prdarr[$i]' and costingno ='$refval'"; 
							$resultcp2 = mysql_query($sql2) or die("Can't query costing_matdet: ".mysql_error());
							$rowcp2 = mysql_fetch_array($resultcp2);
							$qtyarr[$i] = $rowcp2['sordqty'];
							$tordqty = $tordqty + $qtyarr[$i];

							echo '<th style="width: 7%;">'.$sizarr[$i]."</th>";
							$i = $i + 1;
						}
						for ($j = 0; $j <= ($i-1); $j++){
							echo '<th style="width: 7%;">'.$sizarr[$j]."</th>";
						}
					?>
					<th style="width: 80px">T/QTY</th>
					<th style="width: 80px">T $</th>
					<th style="width: 80px">STOCK</th>
					<th style="width: 80px">To Order</th>
					<th style="width: 80px">Qty</th>
					<th style="width: 80px">P/O No</th>
					<th style="width: 80px">Amount</th>
				</tr>
				</thead>
				
				<tbody>
			<?php	
				$k = 1;
				$titmcost = 0;
				$sql1  = "SELECT distinct rm_code, rm_desc, rm_uom from costing_matdet ";
    	    	$sql1 .= " where costingno ='$refval'"; 
    	    	$sql1 .= " order by seqno";
				$rs_result1 = mysql_query($sql1) or die("Can't query costing_matdet: ".mysql_error()); 
				while ($row1 = mysql_fetch_assoc($rs_result1)){
					$itmcdd = $row1['rm_code'];
					$itmcde = substr(htmlentities($row1['rm_desc']), 0, 19);
					$itmuom = htmlentities($row1['rm_uom']);
					
					$sql2  = "SELECT cost_price from rawmat_subcode ";
    	    		$sql2 .= " where rm_code ='$itmcdd'"; 
					$resultcp2 = mysql_query($sql2) or die("Can't query rawmat_subcode: ".mysql_error());
					$rowcp2 = mysql_fetch_array($resultcp2);
					$itmpri = $rowcp2['cost_price'];
					if (empty($itmpri)){$itmpri = 0;}

					echo "<tr>";
					echo "<td class='h'>".$itmcdd."</td>";
					echo "<td class='h'>".$itmcde."</td>";
					echo "<td class='h'>".$itmuom."</td>";
					echo "<td class='h' style='text-align:right'>".number_format($itmpri, 3, ".", ",")."</td>";
					
					for ($j = 0; $j <= ($i-1); $j++) {
						$sql2  = "SELECT rm_ucoms from costing_matdet ";
    	    			$sql2 .= " where costingno ='$refval'   And prodcd = '$prdarr[$j]'"; 
    	    			$sql2 .= " and rm_code = '$itmcdd'";
 						$result2 = mysql_query($sql2) or die("Can't query costing_matdet: ".mysql_error());
						$row2 = mysql_fetch_array($result2);
						$itmsizuni = $row2['rm_ucoms'];
						if (empty($itmsizuni)){$itmsizuni = 0;}
						echo "<td class='h' style='text-align:right'>".number_format($itmsizuni, 3, ".", ",")."</td>";	
					}
					
					$tqtyuseitm = 0;
					for ($j = 0; $j <= ($i-1); $j++) {
						$sql2  = "SELECT rm_ucoms from costing_matdet ";
    	    			$sql2 .= " where costingno ='$refval'   And prodcd = '$prdarr[$j]'"; 
    	    			$sql2 .= " and rm_code = '$itmcdd'";
 						$result2 = mysql_query($sql2) or die("Can't query costing_matdet: ".mysql_error());
						$row2 = mysql_fetch_array($result2);
						$itmsizuni = $row2['rm_ucoms'];
						if (empty($itmsizuni)){$itmsizuni = 0;}
						$qtytuse = $itmsizuni * $qtyarr[$j];
						$tqtyuseitm = $tqtyuseitm + $qtytuse;
						echo "<td class='h' style='text-align:right'>".number_format($qtytuse, 3, ".", ",")."</td>";	
					}
					$tamtuseitm = 0;
					$tamtuseitm = $tqtyuseitm * $itmpri;
					$titmcost = $titmcost + $tamtuseitm;
					echo "<td class='h' style='text-align:right'>".number_format($tqtyuseitm, 3, ".", ",")."</td>";
					echo "<td class='h' style='text-align:right'>".number_format($tamtuseitm, 2, ".", ",")."</td>";
					
					#----------------------Available Qty For RM CODE-------------------
					$sqlonh  = "select sum(totalqty) from rawmat_tran ";
        			$sqlonh .= " where item_code ='$itmcdd'";
        			$sqlonh .= " and tran_type in ('REC', 'ADJ', 'RTN', 'ISS', 'OPB', 'REJ')";
        			$sql_resultonh = mysql_query($sqlonh);
        			$rowonh = mysql_fetch_array($sql_resultonh);        
        			if ($rowonh[0] == "" or $rowonh[0] == null){ $rowonh[0]  = 0.00;}
        			$onhnd = $rowonh[0];
        
        			$sqlbk  = "select sum(bookqty-sumrelqty) from booktab02 ";
        			$sqlbk .= " where bookitm ='$itmcdd'";
        			$sqlbk .= " and compflg = 'N'";
        			$sql_resultbk = mysql_query($sqlbk);
        			$rowbk = mysql_fetch_array($sql_resultbk);        
        			if ($rowbk[0] == "" or $rowbk[0] == null){ $rowbk[0]  = 0.00;}
        			$currbk = $rowbk[0];
        			$avail = number_format(($onhnd - $currbk),"2",".","");
        			#----------------------Available Qty For RM CODE-------------------
					echo "<td class='h' style='text-align:right'>".number_format($avail, 3, ".", ",")."</td>";
					
					$sql = "select sum(plpurqty), sum(plbkqty) from costing_purbook ";
     				$sql .= " where costing_no ='$refval' And itmcode = '$itmcdd'";
     				$sql_result = mysql_query($sql);
     				$row = mysql_fetch_array($sql_result);
     				$itmspur = number_format($row[0], 2, ".",",");
     				$itmsbok = number_format($row[1], 2, ".",",");
					echo "<td class='h' style='text-align:right'>".number_format($itmsbok, 3, ".", ",")."</td>";
					echo "<td class='h' style='text-align:right'></td>";
					echo "<td class='h' style='text-align:right'></td>";
					echo "<td class='h' style='text-align:right'></td>";
					echo "</tr>";
					$k = $k + 1;
				}
			?>
				</tbody>
				<tfoot>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td colspan="<?php echo $cntprd; ?>"></td>
						<td colspan="<?php echo $cntprd; ?>"></td>
						<td></td>
						<td class='h' style='text-align:right'><?php echo number_format($titmcost, 2, ".", ","); ?></td>	
					</tr>
				</tfoot>
			</table>
			<?php
				$tsales = 0;
				$sql1  = "SELECT sordno, sbuycd, procd from costing_matord ";
    	    	$sql1 .= " where costingno ='$refval'"; 
				$rs_result1 = mysql_query($sql1) or die("Can't query costing_matord: ".mysql_error());
				while ($row1 = mysql_fetch_assoc($rs_result1)){
					$orcd = $row1['sordno'];
					$bucd = $row1['sbuycd'];
					$prcd = $row1['procd'];
				
					$sql = "select sum(sproqty * sprounipri) from salesentrydet ";
     				$sql .= " where sordno ='$orcd' And sbuycd = '$bucd'";
     				$sql .= " and sprocd = '$prcd'";
     				$sql_result = mysql_query($sql);
     				$row = mysql_fetch_array($sql_result);
     				$amtsale = $row['sum(sproqty * sprounipri)'];
     				$tsales = $tsales + $amtsale;
				}

				$sql = "select sexpddte from salesentry ";
     			$sql .= " where sordno ='$orcd' And sbuycd = '$bucd'";
     			$sql_result = mysql_query($sql);
     			$row = mysql_fetch_array($sql_result);
     			$dteship = $row['sexpddte'];
				
				if ($tsales == 0){
				$perpur = 0;
				}else{
				$perpur = $titmcost / $tsales;
				}
			?>
			<table style="width:30%">
				<tr>
					<td>Total Purchase</td>
					<td>:</td>
					<td>RM</td>
					<td class='h' style='text-align:right'><?php echo number_format($titmcost, 2, ".", ","); ?></td>
				</tr>
				<tr>
					<td>Total Stock</td>
					<td>:</td>
					<td>RM</td>
					<td class='h' style='text-align:right'></td>
				</tr>
				<tr>
					<td>Total Sales</td>
					<td>:</td>
					<td>RM</td>
					<td class='h' style='text-align:right'><?php echo number_format($tsales, 2, ".", ","); ?></td>
				</tr>
				<tr>
					<td>Shipment Date</td>
					<td>:</td>
					<td></td>
					<td class='h'><?php echo date("d-m-Y", strtotime($dteship)); ?></td>
				</tr>
				<tr>
					<td>% Of Purchase</td>
					<td>:</td>
					<td></td>
					<td class='h' style='text-align:right'><?php echo number_format($perpur, 2, ".", ","); ?></td>
					<td>%</td>
				</tr>
				<tr>
					<td>% Of Total Order</td>
					<td>:</td>
					<td></td>
					<td class='h' style='text-align:right'></td>
					<td>%</td>
				</tr>
				<tr>
					<td>Total Quantity</td>
					<td>:</td>
					<td></td>
					<td class='h' style='text-align:right'><?php echo number_format($tordqty, 0, ".", ","); ?></td>
				</tr>
				</table>
				<table style="width:30%">
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td>
						<table>
							<?php
								for ($j = 0; $j <= ($i-1); $j++) {
									echo "<tr>";
									echo "<td>".$prdarr[$j]."</td>";
									echo "<td>".number_format($qtyarr[$j], 0, ".", ",")."pcs"."</td>";
									echo "<td>X</td>";
									
									$sql = "select sprounipri from salesentrydet ";
     								$sql .= " where sordno ='$orcd' And sbuycd = '$bucd'";
     								$sql .= " and sprocd = '$prdarr[$j]'";
     								$sql_result = mysql_query($sql);
     								$row = mysql_fetch_array($sql_result);
     								$prisale = $row['sprounipri'];
     								$salesdisp = $qtyarr[$j] * $prisale;
 									echo "<td>".$prisale."</td>";
									echo "<td>=</td>";
									echo "<td>RM</td>";
									echo "<td>".number_format($salesdisp, 2, ".", ",")."</td>";
									echo "</tr>";	
								}
							?>
						</table>
					</td>
				</tr>

			</table>
			</td></tr>
		</table>
		<p style="page-break-before: always">	
<!-- ############################# End Body ######################## -->
</body>
<script type="text/javascript" language="JavaScript1.2">confirmPrint()</script>
</html>
