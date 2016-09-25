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
 		 $sel = $_GET['sel'];
    }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>SEWING GARMENT LISTING</title>
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
.auto-style2 {
	font-family: "Times New Roman", Times, serif;
	font-size: medium;
	border: 1px solid black;
	padding: 3px;
	height: 30px;
	font-weight: normal;
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
		 
	 ?>	
		<table width="900" cellspacing="0%" cellpadding="2%">
			<tr>
				<td align="center"><h4>&nbsp;NYOK LAN GARMENTS SDN BHD (202814-K)</h4></td>
			</tr>
			<tr>
				<td align="center"><h4>GARMENT WIP LISTING</h4></td>
			</tr>
			<tr><td>
			<table cellspacing="0%" cellpadding="0%" border="1" style="border-collapse:collapse">
				<thead>
				<tr>
					<th class="auto-style2" style="width: 10px">No.</th>
					<th class="auto-style2" style="width: 10px">Prod Date</th>
					<th class="auto-style2" style="width: 60px">Ticket No.</th>
					<th class="auto-style2" style="width: 50px">Barcode No.</th>
					<th class="auto-style2" style="width: 50px">Product Code</th>
					<th class="auto-style2" style="width: 50px">Worker ID</th>
					<th class="auto-style2" style="width: 50px">Sewing Date</th>
					<th class="auto-style2" style="width: 50px">Sec</th>
					<th class="auto-style2" style="width: 50px">Job</th>
					<th class="auto-style2" style="width: 50px">Rate</th>
					<th class="auto-style2" style="width: 50px">Product Qty</th>
				</tr>
				</thead>
				<tbody>
				<?php
					$sql2 = "";
					if ($sel==1)
					{
						$sql2 = " AND workid between '$fb' and '$tb'";
					}
					if ($sel==2)
					{
						$sql2 = " AND x.ticketno between '$fb' and '$tb'";
					}
				
					if ($sel==3)
					{
						$sql2 = " AND x.prod_code between '$fb' and '$tb'";
					}
				
					$sql .= "SELECT productiondate, x.ticketno, barcodeno, x.prod_code, workid, DATE_FORMAT(sewdate,'%m-%d-%Y') as sewdate, ";
					$sql .= " prod_jobsec, prod_jobid, prod_jobrate, productqty ";
					$sql .= " FROM sew_barcode X, sew_entry Y ";
					$sql .= " WHERE x.ticketno = y.ticketno ";
					$sql .= " AND  prod_code= productcode ";
					$sql .= $sql2;
					$sql .= " ORDER BY productiondate, x.ticketno, barcodeno ";


					$sql_result2 = mysql_query($sql);  
					$numi = 1;                     
					while($row2 = mysql_fetch_assoc($sql_result2)){
						$ticketno= htmlentities($row2['ticketno']);
						$productiondate= htmlentities($row2['productiondate']);
						$barcodeno = htmlentities($row2['barcodeno']);
						$prod_code = htmlentities($row2['sum(x.prod_code)']);
						$workid = htmlentities($row2['workid']);
						$sewdate= htmlentities($row2['sewdate']);
						$prod_jobsec= htmlentities($row2['prod_jobsec']);
						$prod_jobid= htmlentities($row2['prod_jobid']);
						$prod_jobrate= htmlentities($row2['prod_jobrate']);
						$productqty = htmlentities($row2['productqty']);
				?>
					<tr>
						<td class="tab1" style="height: 30" align="center"><?php echo $numi; ?></td>
						<td class="tab1" style="height: 30" align="center"><?php echo $productiondate; ?></td>
						<td class="tab1" align="right"><?php echo $ticketno." ".$prodsizn; ?></td>
						<td class="tab1" align="left"><?php echo $barcodeno ; ?></td>
						<td class="tab1" align="right"><?php echo $prod_code; ?></td>
						<td class="tab1" align="right"><?php echo $workid ; ?></td>
						<td class="tab1" align="right"><?php echo $sewdate; ?></td>
						<td class="tab1" align="right"><?php echo $prod_jobsec; ?></td>
						<td class="tab1" align="right"><?php echo $prod_jobid; ?></td>
						<td class="tab1" align="right"><?php echo $prod_jobrate; ?></td>
						<td class="tab1" align="right"><?php echo $productqty ; ?></td>		
					</tr>
					<?php $numi = $numi + 1; } ?>
				</tbody>
			</table>
			</td></tr>
		</table>
		
		<p style="page-break-before: always">
		<?php
	//}
?>	
<!-- ############################# End Body ######################## -->
</body>
<!--<script type="text/javascript" language="JavaScript1.2">confirmPrint()</script> -->
</html>
