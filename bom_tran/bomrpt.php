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
 		 $clrcd  = $_GET['colcd'];
 		 $refopt = $_GET['opt'];
 		 $procd = substr($_GET['procd'], 1);
 		 $procdori = $_GET['procd'];
 		 
 		 //$str2 = substr($str, 4);
    }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>BOM PRINTING</title>
<style type="text/css">
<!--
@page { 
		size: A4;
  		margin: 1%;
  		font-size:10px;
  		font-family:sans-serif;
}
-->

table { margin: 1em; }
th    { padding: .3em; border: 1px #ccc solid; }

td.h    { 
	border-bottom: 1px #ccc solid;
	margin:0;
 	padding: 5px;
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
	   if ($refopt == "A"){
	   		$sqlq  = "SELECT distinct proclocd, procode from tmpbomprn ";
       		$sqlq .= " where usernm = '$var_loginid'";
       		$sqlq .= " order by bomno";     		
       }else{
       		$sqlq  = "SELECT distinct proclocd, procode from tmpbomprn ";
       		$sqlq .= " where proclocd ='$clrcd'"; 
       		$sqlq .= " and  procode  ='$procdori'"; 
       		$sqlq .= " and usernm = '$var_loginid'";
       }
	   //echo $sqlq;
	   $rs_resultq = mysql_query($sqlq) or die("Can't query Temp Table color: ".mysql_error()); 
				      	
	   while ($rowq = mysql_fetch_assoc($rs_resultq)){
			$vclrcd = $rowq['proclocd'];
			$vprocd = $rowq['procode'];
			
	
			$sqlcd  = "select distinct bomno, sordno, sorddte, proclode, buycd, sdeldte from tmpbomprn ";
        	$sqlcd .= " where proclocd ='$vclrcd'    and procode = '$vprocd'";
        	$sqlcd .= " and usernm = '$var_loginid'";
        	$sql_resultcd = mysql_query($sqlcd) or die("Can't query Temp Table 1:".mysql_error());
        	$rowcd = mysql_fetch_array($sql_resultcd);
        	$bomno = $rowcd[0];
        	$ordno = $rowcd[1]; 
        	$orddt = date('d-m-Y', strtotime($rowcd[2]));
        	$clrde = $rowcd[3];
        	$buycd = $rowcd[4];
        	$deldt = date('d-m-Y', strtotime($rowcd[5]));
        	
        	
        	if ($refopt == "A")
        	{
       		
	       		$varcol = "";
	        	
	        	$sql1  = "SELECT distinct prod_col ";
				$sql1 .= " FROM salesentrydet x left join pro_cd_master y on (x.sprocd = y.prod_code)";
	            $sql1 .= " Where x.sordno = '$ordno'"; 
	            $sql1 .= " AND   x.sbuycd = '$buycd'";
	            //echo $sql1 . "</br>";
	
				$rs_result1 = mysql_query($sql1) or die("Can't query Temp Table 2-A: ".mysql_error()); 
					      	
				while ($row1 = mysql_fetch_assoc($rs_result1))
				{
					if (empty($varcol))
					{
						$varcol= $varcol.$row1['prod_col'];
					}else{
						$varcol= $varcol.", ".$row1['prod_col'];
					}

				
				}
				$sql12 .= " ";
				
			}else{
				$sql12 .= " AND   y.prod_col = '$vclrcd' ";
				$sql12 .= " AND pronumcode = '$procd' ";
			}
				
			//echo 'SQL - '.$sql12 . "</br>";
        
        	$var_sql = " SELECT pro_buy_desc from pro_buy_master ";
    		$var_sql .= " WHERE pro_buy_code = '$buycd'";
			$result=mysql_query($var_sql);
			$row = mysql_fetch_array($result);
			$buydesc = $row['pro_buy_desc'];
		
			$vartxt = "";
			$totqt = 0;
			//$sql1  = "SELECT distinct prosiz, ordqty from tmpbomprn ";
        	//$sql1 .= " where proclocd ='$vclrcd'   and procode = '$vprocd'"; 
        	//$sql1 .= " and usernm = '$var_loginid'";
        	//$sql1 .= " order by prosiz";
        	// add here//
        	
        	$sql1  = "SELECT y.prod_size as prosiz, x.sproqty as ordqty ";
			$sql1 .= " FROM salesentrydet x left join pro_cd_master y on (x.sprocd = y.prod_code)";
            $sql1 .= " Where x.sordno = '$ordno'"; 
            $sql1 .= " AND   x.sbuycd = '$buycd'";
            $sql1 .= $sql12 ;
	    	$sql1 .= " ORDER BY y.prod_catpre, y.pronumcode, y.prod_col, y.prod_size";
	    	// end add here - cedric
	    	//echo $sql1;
			$rs_result1 = mysql_query($sql1) or die("Can't query Temp Table 2: ".mysql_error()); 
				      	
			while ($row1 = mysql_fetch_assoc($rs_result1)){
			//echo number_format($row1['ordqty']);
				if (empty($vartxt)){
					$vartxt = $vartxt.$row1['prosiz']."/". number_format($row1['ordqty']);
				}else{
					$vartxt = $vartxt.", ".$row1['prosiz']."/".number_format($row1['ordqty']);
				}
				
				$totqt = $totqt + $row1['ordqty'];
				//echo $totqt;
				
			}
			$qtydoz = 0;
			$qtypcs = 0;
			
			$qtydoz = FLOOR($totqt/12);
			$qtypcs = $totqt % 12;
			
			#-------------------------Product Code Number prefix ---------------------------------------
			$varcdtxt = "";
			$sqlc  = "select distinct y.prod_catpre, y.pronumcode";
			$sqlc .= " from tmpbomprn x, pro_cd_master y ";
        	$sqlc .= " where x.prodcd = y.prod_code";
        	$sqlc .= " and x.proclocd = '$vclrcd'   and x.procode = '$vprocd'";
        	$sqlc .= " and x.usernm = '$var_loginid'";
        	$sql_resultc = mysql_query($sqlc) or die("Can't query Product Table:".mysql_error());
        	while ($rowc = mysql_fetch_assoc($sql_resultc)){
        		$varcdt = $rowc['prod_catpre'].$rowc['pronumcode'];
				if (empty($varcdtxt)){
					$varcdtxt = $varcdtxt.$varcdt;
				}else{
					$varcdtxt = $varcdtxt.", ".$varcdt;
				}
			}
        	#-------------------------------------------------------------------------------------------
			// to get qty in doz and qty in pcs
			//select FLOOR(productqty/12) as qtydoz, productqty mod 12 as qtypcs from sew_entry
		?>
		<table width="900" cellspacing="0%" cellpadding="2%">
			<tr>
				<td colspan="8" align="center"><h3>NYOK LAN GARMENTS SDN BHD (202814-K)</h3></td>
			</tr>
			<tr>
				<td colspan="8" align="center"><h3>Bill Of Material</h3></td>
			</tr>
			<tr>
				<td style="width: 28px"></td>
				<td>BOM No</td>
				<td>:</td>
				<td><?php echo $bomno;?></td>
				<td style="width: 200px"></td>
				<td>Order No</td>
				<td>:</td>
				<td><?php echo $ordno; ?></td>
			</tr>
			<tr>
				<td style="width: 28px"></td>
				<td>Code</td>
				<td>:</td>
				<td><?php echo $varcdtxt; ?></td>
				<td></td>
				<td>Order Date</td>
				<td>:</td>
				<td><?php echo $orddt; ?></td>
			</tr>
			<tr>
				<td style="width: 28px"></td>
				<td>Color</td>
				<td>:</td>
				<td><?php if ($refopt =="A"){ echo $varcol; }else{echo $vclrcd; }?></td>
				<td></td>
				<td>Buyer</td>
				<td>:</td>
				<td><?php echo $buycd;?></td>
			</tr>
			<tr>
				<td style="width: 28px"></td>
				<td>Size / Qty</td>
				<td>:</td>
				<td colspan="5"><?php echo $vartxt; ?></td>
			</tr>
			<tr>
				<td style="width: 28px"></td>
				<td>Delivery Date</td>
				<td>:</td>
				<td><?php echo $deldt; ?></td>
				<td></td>
				<td>Total Quantity</td>
				<td>:</td>
				
				<td><?php echo $totqt; echo "pcs (". $qtydoz. "Doz  ". $qtypcs . " Pcs)" ;?></td>
			</tr>
			<tr><td colspan="8" style="height: 25px"></td></tr>
			<tr><td colspan="8">
			<table width="900" cellspacing="0%" cellpadding="2%">
				<thead>
				<tr style="border:2px">
					<th class="tab1" style="width: 30px">No</th>
					<th class="tab1" style="width: 100px">Item Code</th>
					<th class="tab1" style="width: 200px">Description</th>
					<?php
						unset($sizarr);
						$sql1  = "SELECT distinct prosiz from tmpbomprn ";
    	    			$sql1 .= " where proclocd ='$vclrcd'  and procode = '$vprocd'"; 
    	    			$sql1 .= " and usernm = '$var_loginid'";
    	    			$sql1 .= " order by prosiz";
						$rs_result1 = mysql_query($sql1) or die("Can't query Temp Table 3: ".mysql_error());
						
						$i = 0;
						while ($row1 = mysql_fetch_assoc($rs_result1)){
							$sizarr[$i] = $row1['prosiz'];
							$siz = $row1['prosiz'];
							$siz = "Unit/Doz";
							echo '<th class="tab1" style="width: 7%;">'.$siz."</th>";
							$i = $i + 1;
						}	
					?>
					<th class="tab1" style="width: 50px">UOM</th>
					<th class="tab1" style="width: 80px">Qty Needed</th>
					<th class="tab1" style="width: 80px">On hand</th>
				</tr>
				</thead>
				<tbody>
			<?php
		
				$i = 1;
				$lgarrsize = sizeof($sizarr) - 1;
				$sql1  = "SELECT distinct itmcd,  itmuom, onhandbal from tmpbomprn ";
    	    	$sql1 .= " where proclocd ='$vclrcd'  and procode = '$vprocd'"; 
    	    	$sql1 .= " and usernm = '$var_loginid'";
    	    	$sql1 .= " order by itmcd, itmseq";
    	    	//echo $sql1;
				$rs_result1 = mysql_query($sql1) or die("Can't query Temp Table 4: ".mysql_error()); 
			 	      	
				while ($row1 = mysql_fetch_assoc($rs_result1)){
					$itmcdd = $row1['itmcd'];
					$onhnd = $row1['onhandbal'];
					$itmuom= $row1['itmuom'];

					//$itmdesc = $row1['itmdesc'];
					
					
					$sqlcp2  = "SELECT max(itmdesc) from tmpbomprn ";
    	    		$sqlcp2 .= " where proclocd ='$vclrcd'   And itmcd = '$itmcdd'"; 
    	    		$sqlcp2 .= " and usernm = '$var_loginid' and procode = '$vprocd'";
					$resultcp2 = mysql_query($sqlcp2) or die("Can't query Temp Table 6-2: ".mysql_error());
					$rowcp2 = mysql_fetch_array($resultcp2);
					$itmdesc= $rowcp2[0];

					//$itmdesc = $row12['itmdesc'];

					if ($itmdesc == "" or $itmdesc == null){ 
        					  $itmdesc  = "&nbsp";
        			}
        			
        			//to select total order qty from buyer order #
        			
        			
        			if ($refopt == "A")
		        	{
						$sqlcpX  .= " ";
						
					}else{
						$sqlcpX  .= " AND   y.prod_col = '$vclrcd' ";
						$sqlcpX  .= " AND pronumcode = '$procd' ";
					}
				

					
					$totordqty = 0;
					$sqlcp2  = "SELECT sum(x.sproqty) as totordqty ";
					$sqlcp2  .= " FROM salesentrydet x left join pro_cd_master y on (x.sprocd = y.prod_code)";
            		$sqlcp2  .= " Where x.sordno = '$ordno'"; 
            		$sqlcp2  .= " And   x.sbuycd = '$buycd'";
            		$sqlcp2  .= $sqlcpX ;
	    			$sqlcp2  .= " ORDER BY y.prod_catpre, y.pronumcode, y.prod_col, y.prod_size";
	    			//echo $sqlcp2;
	    			$resultcp2 = mysql_query($sqlcp2) or die("Can't get Total order qty : ".mysql_error());
					$rowcp2 = mysql_fetch_array($resultcp2);
					$totordqty = $rowcp2[0];


        			// end select 
					
					$sqlcp  = "SELECT sum(uniconsump/12) from tmpbomprn ";
    	    		$sqlcp .= " where proclocd ='$vclrcd'   And itmcd = '$itmcdd'"; 
    	    		$sqlcp .= " and usernm = '$var_loginid' and procode = '$vprocd'";
    	    		//$sqlcp .= " and itmdesc = '$itmdesc'";
					$resultcp = mysql_query($sqlcp) or die("Can't query Temp Table 6: ".mysql_error());
					$rowcp = mysql_fetch_array($resultcp);
					$itmtcomp = $rowcp[0];
					
					$itmtcomp = $totordqty * $itmtcomp;
				
					$itmdesc = str_replace('^', '"', $itmdesc);
					$itmdesc = str_replace("|", "'", $itmdesc);
	
					echo "<tr>";
					echo "<td style='width: 30px' class='h'>".$i."</td>";
					echo "<td style='width: 100px' class='h'>".$itmcdd."</td>";
					echo "<td style='width: 200px' class='h'>".$itmdesc."</td>";
					
					$itmdesc = str_replace('"', '^', $itmdesc);
					$itmdesc = str_replace("'", '|', $itmdesc);
					
					for ($j = 0; $j <= $lgarrsize; $j++) {
	
						$sql2  = "SELECT uniconsump from tmpbomprn ";
    	    			$sql2 .= " where proclocd ='$vclrcd'   And itmcd = '$itmcdd'"; 
    	    			$sql2 .= ' and prosiz = \''.$sizarr[$j].'\' ';
    	    			$sql2 .= " and usernm = '$var_loginid' and procode = '$vprocd'";
    	    			//$sql2 .= " and itmdesc = '$itmdesc'";
						$result2 = mysql_query($sql2) or die("Can't query Temp Table 5: ".mysql_error());
						$row2 = mysql_fetch_array($result2);
						$itmsizuni = $row2['uniconsump'];
						if ($itmsizuni == "" or $itmsizuni == null){ 
        					  $itmsizuni  = "&nbsp";
        				}

						echo "<td class='h' align='right'>".$itmsizuni."</td>";
						
					}
					
					echo "<td style='width: 50px' class='h' align='right'>".$itmuom."</td>";
					echo "<td style='width: 80px' class='h' align='right'>".number_format($itmtcomp,'2',".",',')."</td>";
					echo "<td style='width: 80px' class='h' align='right'>".$onhnd."</td>";
					echo "</tr>";
					$i = $i + 1;
				}
			?>
				</tbody>
			</table>
			</td></tr>
			<tfoot>
			</tfoot>
		</table>
		<p style="page-break-before: always">
	<?php 
	}
	?>		
<!-- ############################# End Body ######################## -->
</body>
<script type="text/javascript" language="JavaScript1.2">confirmPrint()</script>
</html>
