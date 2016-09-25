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
 		 $fdte = date("Y-m-d", strtotime($_GET['f']));
 		 $tdte = date("Y-m-d", strtotime($_GET['t']));
 		 $buyer = $_GET['b'];
 		 
 		 //$frdte  = date("Y-m-d", strtotime($_POST['rptofdte']));

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
  		margin:1cm;
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
	font-size:large;
	border: 0px solid black;
	padding: 3px;
    height:30px;
}
.auto-style3 {
	font-family: "Times New Roman", Times, serif;
	font-size: large;
	border: 0px solid black;
	padding: 0px;
	height: 30px;
	font-weight: normal;
	text-decoration: underline;
}
.auto-style4 {
	font-size: medium;
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
				<td align="center"><h4>&nbsp;<span class="auto-style4">NYOK LAN GARMENTS SDN BHD (202814-K)</span></h4></td>
			</tr>
			<tr>
				<?php 
					$from = date("d-m-Y", strtotime($_GET['f']));
					$to   = date("d-m-Y", strtotime($_GET['t']));
				?>
				<td align="center"><h4 class="auto-style4">SEWING TICKET SUMMARY FROM <?php echo $from;?> TO <?php echo $to;?>
				<br><h4 class="auto-style4">
						<?php 
							$sql = "SELECT customer_desc FROM customer_master ";
							$sql.= " WHERE customer_code = '$buyer'";
							
			        		$sql_result = mysql_query($sql);
        					$row2 = mysql_fetch_array($sql_result);
							$customer_desc= $row2[0];						
							
							echo $customer_desc;
						?>
					</h4>
				</td>
			</tr>
			<tr><td>
			<table cellspacing="0%" cellpadding="0%" border="0" style="border-collapse:collapse">
				<thead>
				<tr>
					<th class="auto-style3" style="width: 118px">DATE</th>
					<th class="auto-style3" style="width: 130px">BATCH NO</th>
					<th class="auto-style3" style="width: 107px">TICKET</th>
					<th class="auto-style3" style="width: 155px">PRODUCT CODE</th>
					<th class="auto-style3" style="width: 118px">QC DATE</th>
					<th class="auto-style3" style="width: 165px">PO NO</th>
					<th class="auto-style3" style="width: 130px">REMARKS</th>
				</tr>
				</thead>
				<tbody>
				<?php
					$sqlc = " CREATE TEMPORARY TABLE tmpprodsum2 (seqno smallint, productiondate date, batchno varchar(40), ticketno varchar(40), productcode varchar(40), buyerorder varchar(40), pdate date, batchno2 varchar(40), usernm varchar(40))";
					mysql_query($sqlc) or die("Cant Create Temp Table ". $sql);
					//echo $sqlc;
					
					$sqld  = " Delete From tmpprodsum2 where usernm = '$var_loginid'";
        			//mysql_query($sqld) or die("Unable To Prepare Temp Table For Printing".mysql_error());
        			mysql_query($sqld) or die("Unable To Prepare Temp Table For Printing:".mysql_error(). ' Failed SQL is -->'. $sqld);						
			
					$sql = "SELECT productiondate, batchno, ticketno, productcode , buyerorder ";
					$sql .= " FROM sew_entry  ";
					$sql .= " WHERE productiondate between '$fdte' and '$tdte' ";
					$sql .= " AND buyer = '$buyer' ";
					$sql .= " ORDER BY productiondate, batchno, ticketno ";
//echo $sql . "</br>";
					$sql_result2 = mysql_query($sql);  
					$numi = 1;                     
					while($row2 = mysql_fetch_assoc($sql_result2)){
						//$productiondate= htmlentities($row2['productiondate']);
						$productiondate= $row2['productiondate'];
						$batchno= htmlentities($row2['batchno']);
						$ticketno= htmlentities($row2['ticketno']);
						$prod_code = htmlentities($row2['productcode']);
						$buyerorder= htmlentities($row2['buyerorder']);
						
						
						
						$var_sql = " SELECT count(*) as cnt from tmpprodsum2";
		  		      	$var_sql .= " Where productiondate= '$productiondate'";
		  		      	$var_sql .= " and batchno= '$batchno'";

	      				$query_id = mysql_query($var_sql) or die ("Cant Check Product Costing");
	      				$res_id = mysql_fetch_object($query_id);
          		   
	      				if ($res_id->cnt > 0 ){					
							$sqliq  = " INSERT INTO tmpprodsum2";
							$sqliq .= " VALUES ('2', '$productiondate', '$batchno', '$ticketno', '$prod_code', '$buyerorder',";
	        				$sqliq .= "   '$productiondate', '$batchno',  '$var_loginid' )";
	        				mysql_query($sqliq) or die("Unable Save In Temp Table:".mysql_error(). ' Failed SQL is -->'. $sqliq);						
	        				//echo $sqliq . "</br>";
	        			}else{
	        				$sqliq  = " INSERT INTO tmpprodsum2";
							$sqliq .= " VALUES ('1', '$productiondate', '$batchno', '$ticketno', '$prod_code', '$buyerorder',";
	        				$sqliq .= "   '$productiondate', '$batchno',  '$var_loginid' )";
	        				mysql_query($sqliq) or die("Unable Save In Temp Table:".mysql_error(). ' Failed SQL is -->'. $sqliq);						
	        				//echo $sqliq . "</br>";
	        			}
	        		}
	        		
	        		$sqle .= "SELECT seqno, productiondate, batchno, ticketno, productcode, buyerorder ";
					$sqle .= " FROM tmpprodsum2";
					$sqle .= " WHERE productiondate between '$fdte' and '$tdte' ";
					$sqle .= " ORDER BY productiondate, batchno, ticketno ";
//echo '</br>';
//echo $sqle;
//echo '</br>';

					$sql_result2 = mysql_query($sqle);  
					$numi = 1;                     
					while($row2 = mysql_fetch_assoc($sql_result2)){
					
						$seqno= htmlentities($row2['seqno']);
						$productiondate= date('d-m-Y', strtotime($row2['productiondate']));
						$batchno= htmlentities($row2['batchno']);
						$ticketno= htmlentities($row2['ticketno']);
						$prod_code = htmlentities($row2['productcode']);
						$buyerorder= htmlentities($row2['buyerorder']);
						if ($seqno== '2')
						{
							$productiondate = '';
							$batchno = '';
							$buyerorder = '';
						}

						
				?>
					<tr>
						<td class="tab1" style="height: 30; width: 165px;" align=" center"><?php echo $productiondate; ?></td>
						<td class="tab1" align="center" style="width: 144px"><?php echo $batchno?></td>
						<td class="tab1" align="center" style="width: 107px"><?php echo $ticketno?></td>
						<td class="tab1" align="right" style="width: 119px"><?php echo $prod_code; ?></td>
						<td class="tab1" align="right"></td>
						<td class="tab1" align="center" style="width: 165px"><?php echo $buyerorder; ?></td>
						<td class="tab1" align="right"></td>
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
