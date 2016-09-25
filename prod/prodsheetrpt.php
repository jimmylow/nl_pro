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
 		 $fb = $_GET['f'];
 		 $tb = $_GET['t'];
 		 set_time_limit(180);
    }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>PRODUCTION SHEET</title>
<style type="text/css">
<!--
@page { 
		size: A4;
  		margin: 1%;
  		font-size:7px;
  		font-family:Times New Roman;
}
-->


td.h    { 
	border-bottom: 1px #ccc solid;
	margin:0;
 	padding: 1px;
 	font-size:7px;
	font-family:Times New Roman;

}

@media print 
{
    .noPrint 
    {
        display:none;
    }
    
     .page-footer {
                display: block;
                position: absolute;
                bottom: 10;
                font-family:"Times New Roman", Times, serif;
				font-size:small;

     }
}

.tab1{
	font-family:"Times New Roman", Times, serif;
	font-size:medium;
	 border: 1px solid black;
    padding: 3px;
    height:30px;
}
.auto-style1 {
	font-size: large;
}
.auto-style2 {
	font-family: "Times New Roman", Times, serif;
	font-size: medium;
	border: 1px solid black;
	padding: 3px;
	height: 30px;
	font-weight: normal;
}
.auto-style4 {
	font-size: large;
	font-weight: bold;
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
					
	$sql .= " select distinct x.batchno, y.prod_catpre, y.pronumcode ";
	$sql .= "  from sew_entry x, pro_cd_master y ";
	$sql .= "  where x.productcode = y.prod_code ";
	$sql .= "  and   x.batchno between '$fb' and '$tb'";
	$sql .= "  and   x.stat = 'ACTIVE'";
	$sql .= "  order by x.batchno, y.pronumcode";
	//echo $sql;
	$sql_result = mysql_query($sql);                       
	while($row = mysql_fetch_assoc($sql_result)) 
	{
		$batn = htmlentities($row['batchno']);
		$ppre = htmlentities($row['prod_catpre']);
		$numc = htmlentities($row['pronumcode']);
		$prcode = trim($ppre).trim($numc);
		
		$sql1  = "select orderdate, productiondate, buyer, buyerorder, grpcd, deliverydate, areacd ";
		$sql1 .= " from sew_entry ";
    	$sql1 .= " where batchno ='$batn'";
    	//echo $sql1."<br>";
    	$sql_result1 = mysql_query($sql1);
    	$row1 = mysql_fetch_array($sql_result1);
    	$ordate = $row1['orderdate'];
    	$tidate = $row1['productiondate'];
    	$pbuyer = $row1['buyer'];
    	$buyord = $row1['buyerorder'];
    	$portcd = $row1['grpcd'];
    	$deldte = $row1['deliverydate'];
    	$area = $row1['areacd'];
    	
    	$var = $ordate;
		if (empty($var)){
			$ordate = '0000-00-00';
		}else{	
			$date = str_replace('/', '-', $var);
			$ordate = date('d-m-Y', strtotime($date));
		}
		
		$var = $tidate;
		if (empty($var)){
			$tidate = '0000-00-00';
		}else{	
			$date = str_replace('/', '-', $var);
			$tidate = date('d-m-Y', strtotime($date));
		}
		
		$var = $deldte;
		if (empty($var)){
			$deldte = '0000-00-00';
		}else{	
			$date = str_replace('/', '-', $var);
			$deldte = date('d-m-Y', strtotime($date));
		}   
		

		$sql2  = "select area_desc ";
		$sql2 .= " from area_master ";
    	$sql2 .= " where area_code ='$area'";
    	//echo $sql1."<br>";
    	$sql_result1 = mysql_query($sql2);
    	$row1 = mysql_fetch_array($sql_result1);
    	$area_desc= $row1['area_desc'];
 
	 ?>	
		<table width="900" cellspacing="0%" cellpadding="2%">
			<tr>
				<td colspan="12" align="center"><h4>&nbsp;NYOK LAN GARMENTS SDN BHD (202814-K)</h4></td>
			</tr>
			<tr>
				<td colspan="12" align="center"><h5 class="auto-style1">PRODUCTION SHEET (PS)</h5></td>
			</tr>
			<tr>
				<td style="width: 5px"></td>
				<td style="width: 73px" class="auto-style4">Batch No</td>
				<td style="width: 5px">:</td>
				<td style="width: 150px" class="auto-style4"><?php echo $batn; ?></td>
				<td style="width: 5px"></td>
				<td style="width: 100px">Order Date</td>
				<td style="width: 5px">:</td>
				<td style="width: 150px"><?php echo $ordate; ?></td>
				<td style="width: 5px"></td>
				<td style="width: 100px">Date</td>
				<td style="width: 5px">:</td>
				<td><?php echo date("d-m-Y"); ?></td>
			</tr>
			<tr>
				<td></td>
				<td style="width: 73px" class="auto-style4">Code</td>
				<td>:</td>
				<td class="auto-style4"><?php echo $prcode; ?></td>
				<td></td>
				<td>Ticket Date</td>
				<td>:</td>
				<td><?php echo $tidate; ?></td>
				<td></td>
				<td>Time</td>
				<td>:</td>
				<td><?php echo date("H:i:s"); ?></td>
			</tr>
			<tr>
				<td></td>
				<td style="width: 73px">Buyer</td>
				<td>:</td>
				<td><?php echo $pbuyer; ?></td>
				<td></td>
				<td>Sewing Date</td>
				<td>:</td>
				<td>____________________</td>
				<td></td>
				<td>P/O No.</td>
				<td>:</td>
				<td><?php echo $buyord; ?></td>

			</tr>
			<tr>
				<td></td>
				<td style="width: 73px">Port</td>
				<td>:</td>
				<td><?php echo $portcd; ?></td>
				<td></td>
				<td>Finishing Date</td>
				<td>:</td>
				<td>____________________</td>
				<td></td>
				<td>Delivery Date</td>
				<td>:</td>
				<td><?php echo $deldte; ?></td>
			</tr>
			<tr>
				<td></td>
				<td style="width: 73px">Area</td>
				<td>:</td>
				<td><?php echo $area_desc; ?></td>
				<td></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr><td colspan="12" style="height: 10px"></td></tr>
			<tr><td colspan="12">
				<?php
					$sqlc = " CREATE TEMPORARY TABLE jobidt (idj varchar(10), seq smallint, colno smallint)";
					mysql_query($sqlc) or die("Cant Create Temp Table ". $sql);

					$sqlc = " delete from jobidt";
					mysql_query($sqlc) or die("Cant Create Temp Table ". $sql);

					$ccol = "";
					$csiz = "";
					$prodc = "";
					$sql2 = " select distinct y.prod_col, y.prod_size ";
					$sql2 .= "  from sew_entry x, pro_cd_master y ";
					$sql2 .= "  where x.productcode = y.prod_code ";
					$sql2 .= "  and   x.batchno = '$batn'";
					$sql2 .= "  and   y.prod_catpre = '$ppre' and y.pronumcode = '$numc'";
					$sql2 .= "  order by y.prod_col, y.prod_size";
					$sql_result2 = mysql_query($sql2);                       
					while($row2 = mysql_fetch_assoc($sql_result2)){
						$ccol = htmlentities($row2['prod_col']);
						$csiz = htmlentities($row2['prod_size']);
						$prodc = $prcode."-".trim($ccol)."-".trim($csiz);
						
						$jid = "";
						$colno = 1;
						$sql3k = " select prod_jobid, prod_jobseq ";
						$sql3k .= "  from pro_jobmodel ";
						$sql3k .= "  where prod_code = '$prodc'";
						$sql3k .= "  order by prod_jobseq";
						$sql_result3k = mysql_query($sql3k);                   
						while($row3k = mysql_fetch_assoc($sql_result3k)){
							$jid = htmlentities($row3k['prod_jobid']);
							$jsq = $row3k['prod_jobseq'];
							
							$sqlc = " insert into jobidt values ('$jid', '$jsq', '$colno')";
							mysql_query($sqlc) or die("Cant Insert Temp Table ". $sql);
							$jid = "";
							$colno = $colno + 1;
						}	
						$ccol = "";
						$csiz = "";
						$prodc = "";
					}	

				?>
			<?php
				$frli = 0;
				$toli = $frli + 14;
				$sql33 = "select count(distinct idj) from jobidt";
				$sql_result33 = mysql_query($sql33);
				$row33 = mysql_fetch_array($sql_result33);
				$cntcol = $row33['count(distinct idj)'];
				while($cntcol > 0){
			?>			
				<table cellspacing="0%" cellpadding="0%" border="1" style="border-collapse:collapse">
					<thead>
						<tr>
							<th class="auto-style2" style="width: 10px">Ticket</th>
							<th class="auto-style2" style="width: 60px">Col  Size</th>
							<th class="auto-style2" style="width: 50px">Qty</th>

							<?php
						
								$jid = "";
								$sql3 = " select distinct idj";
								$sql3 .= "  from jobidt ";
								$sql3 .= "  where colno between '$frli' and '$toli'";
								$sql3 .= "  order by seq";
								$sql_result3 = mysql_query($sql3);                       
								while($row3 = mysql_fetch_assoc($sql_result3)){
									$jid = htmlentities($row3['idj']);
									echo '<th class="auto-style2" style="width: 42px">'.$jid.'</th>';
									$jid = "";
								
								}	
							?>
						</tr>
					</thead>
				<tbody>
				<?php

					$sql2 = " select distinct x.ticketno, y.prod_col, y.prod_size, sum(x.productqty) ";
					$sql2 .= "  from sew_entry x, pro_cd_master y ";
					$sql2 .= "  where x.productcode = y.prod_code ";
					$sql2 .= "  and   x.batchno = '$batn'";
					$sql2 .= "  and   y.prod_catpre = '$ppre' and y.pronumcode = '$numc'";
					$sql2 .= "  group by 1, 2, 3";	
					$sql2 .= "  order by x.ticketno, y.prod_col, y.prod_size";
					//echo $sql2;

					$sql_result2 = mysql_query($sql2);                       
					while($row2 = mysql_fetch_assoc($sql_result2)){
						$ticketnn = htmlentities($row2['ticketno']);
						$prodcoln = htmlentities($row2['prod_col']);
						$prodsizn = htmlentities($row2['prod_size']);
						$prodqtyn = htmlentities($row2['sum(x.productqty)']);
						
						$sql33 = "select count(distinct idj) from jobidt";
						$sql_result33 = mysql_query($sql33);
						$row33 = mysql_fetch_array($sql_result33);
						$detcnt = $row33['count(distinct idj)'];

				?>
						<tr>
						<td class="tab1"><?php echo $ticketnn; ?></td>
						<td class="tab1"><?php echo $prodcoln." ".$prodsizn; ?></td>
						<td class="tab1"><?php echo $prodqtyn; ?></td>
					<?php

							$jid = "";
							$sql34 = " select distinct idj ";
							$sql34 .= "  from jobidt ";
							$sql34 .= "  where colno between '$frli' and '$toli'";
							$sql34 .= "  order by seq";
							$sql_result34 = mysql_query($sql34);                       
							while($row34 = mysql_fetch_assoc($sql_result34)){
								$jid = htmlentities($row34['idj']);
								echo '<td class="tab1"></td>';
								$jid = "";
								
							}
					}				
					?>	
						</tr>				
					</tbody>
				</table>
			<?php
					$frli = $toli + 1;
					$toli = $toli + 14;
					$cntcol = $cntcol - 14;
					echo "<br>";
				}		
				$sqlc = " DROP TABLE jobidt";
				mysql_query($sqlc) or die("Cant Drop Temp Table ". $sql);

			?>
			</td></tr>
			<tfoot>
			</tfoot>
		</table>
		
		<p style="page-break-before: always">
		<div class="page-footer">Note : Wherever the goods are, "Production Sheet" must follow with goods.</div>
<?php
	}
?>	
<!-- ############################# End Body ######################## -->
</body>
<!--<script type="text/javascript" language="JavaScript1.2">confirmPrint()</script> -->
</html>
