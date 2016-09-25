<?php 

	include("../Setting/Configifx.php");
	
	$db_link  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
	mysql_query("SET character_set_results=utf8", $db_link) or die(mysql_error());
	mb_language('uni');
	mb_internal_encoding('UTF-8');
	mysql_select_db("$var_db_name", $db_link);
	mysql_query("set names 'utf8'",$db_link);

	$supp_code=$_GET['supp_code'];
	$frdate=$_GET['frdate'];
	$todate=$_GET['todate'];

	$frdate= date('Y-m-d', strtotime($frdate));		
	$todate= date('Y-m-d', strtotime($todate));	

	echo '<table id="itemsTable" class="general-table">
		<thead>
			<tr>
				  <th class="tabheader" style="width: 27px; height: 57px;">#</th>
				  <th class="tabheader">Ticket No.</th>
				  <th class="tabheader">Product</th>
				  <th class="tabheader">UOM</th>
				  <th class="tabheader">Description</th>
				  <th class="tabheader">Qty</th>
				  <th class="tabheader" style="width: 78px">Unit Price</th>
				  <th class="tabheader">Amount</th>
			 </tr>
		</thead>
		<tbody>';
     
	$sql = "SELECT x.ticketno, x.productcode, x.seqno, qty ";
	$sql.= " FROM sew_qc_tran x, sew_entry y ";
	$sql.= " where x.ticketno = y.ticketno ";
	$sql.= " and buyer = '$supp_code' ";
	$sql.= " and x.qcdate between '$frdate' and '$todate' ";
	$sql.= " ORDER BY x.ticketno, x.seqno";
	// echo $sql;
             	
	//$sql = "SELECT x.ticketno, x.productcode, x.seqno, qty FROM sew_qc_tran x, sew_entry y where x.ticketno = y.ticketno and buyer = 'MDF' and x.qcdate between '2013-10-01' and '2013-10-23' ORDER BY x.ticketno, x.seqno";


	$rs_result = mysql_query($sql); 
   
	$i = 1;
	while ($rowq = mysql_fetch_assoc($rs_result))
	{
		//here//
		$sql = "select prod_desc, prod_uom from pro_cd_master ";
		$sql .= " where prod_code = '". $rowq['productcode']."'";
		$sql_result = mysql_query($sql);
		if ($sql_result <> FALSE)
		{
			$row = mysql_fetch_array($sql_result);
			$prod_desc= $row[0];
			$prod_uom= $row[1];
		}
		
		$sql = "select totamt from prod_matmain ";
		$sql .= " where prod_code = '". $rowq['productcode']. "'";
		//$sql .= " where prod_code = 'M902-BK-FR'";
		
		$sql_result = mysql_query($sql);
		if ($sql_result <> FALSE)
		{
			$row = mysql_fetch_array($sql_result);
			$totamt= $row[0];
			$totamt = $totamt /12;
		}
		if ($totamt =='' | $totamt == ' ' | $totamt == NULL)
		{
			$totamt = 0.00;
		}
		
		$totamt = number_format($totamt, 2);


		// end here//
				
				
				
		$amt = 0;
		$amt = number_format($totamt* $rowq['qty'], 2);
		
		$ticketno = $rowq['ticketno'];
		if ($rowq['seqno']==2)
		{
			$rowq['ticketno']= '';
		}
			  
		echo '<tr class="item-row">';	
		echo '<td><input name="sequence[]" id="sequence" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>'; 
		echo '<td><input name="ticketno2[]" value="'.$rowq['ticketno'].'" id="ticketno2" readonly="readonly" style="border-style: none; border-color: none; border-width: 0; width: 70px;"></td>';
	  
		echo '<td><input name="productcode[]" value="'.htmlentities($rowq['productcode']).'" id="productcode'.$i.'" readonly="readonly" style="width: 161px; border-style: none;"></td>';
		echo '<td><input name="procouom[]" value="'.$prod_uom.'" id="procodesc" style="width: 35px; border-style: none;" readonly="readonly"></td>'; 
		echo '<td><input name="procodesc[]" value="'.$prod_desc.'" id="procodesc" style="width: 303px; border-style: none;" readonly="readonly"></td>'; 
		echo '<td><input name="issueqty[]" class="tInput" value="'.$rowq['qty'].'" id="issueqtyid'.$i.'" onBlur="calcCost('.$i.');" style="width: 75px;"></td>';   
		echo '<td><input name="uprice[]" class="tInput" value="'.$totamt.'" id="upriceid'.$i.'"  onBlur="calcCost('.$i.');" style="width: 75px; "></td>';   
		echo '<td ><input name="amount[]" tMark="1" id="amount'.$i.'" readonly="readonly" style="width: 75px; border:0; " value="'.$amt.'"></td>';         
		echo '<td><input name="seqno[]" class="tInput" value="'.$rowq['seqno'].'" id="seqnoid'.$i.'"  readonly="readonly" style="display:none; width: 75px; border:0;"></td>';   
		echo '<td><input name="ticketno[]" class="tInput" value="'.$ticketno.'" id="ticketno'.$i.'"  readonly="readonly" style="display:none; width: 75px; border:0;"></td>';
		

		echo ' </tr>';
		
		$i = $i + 1;
	}
	echo '</tbody></table>';
    
?>
