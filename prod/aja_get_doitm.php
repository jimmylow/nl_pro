<?php 

	include("../Setting/Configifx.php");
	
	$db_link  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
	mysql_query("SET character_set_results=utf8", $db_link) or die(mysql_error());
	mb_language('uni');
	mb_internal_encoding('UTF-8');
	mysql_select_db("$var_db_name", $db_link);
	mysql_query("set names 'utf8'",$db_link);


	$sew_doid=$_GET['sew_doid'];

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
     
	$sql = "SELECT x.ticketno, x.productcode, x.seqno, issueqty, uprice , FORMAT(amount,2) as  amount, sequence, uom";
	$sql.= " FROM sew_do_tran x";
	$sql.= " WHERE sew_doid = '$sew_doid' ";
	$sql.= " ORDER BY sequence";
	//echo $sql;
             	
	//$sql = "SELECT x.ticketno, x.productcode, x.seqno, qty FROM sew_qc_tran x, sew_entry y where x.ticketno = y.ticketno and buyer = 'MDF' and x.qcdate between '2013-10-01' and '2013-10-23' ORDER BY x.ticketno, x.seqno";


	$rs_result = mysql_query($sql); 
   
	$i = 1;
	while ($rowq = mysql_fetch_assoc($rs_result))
	{
		//here//
		$sql = "select prod_desc from pro_cd_master ";
		$sql .= " where prod_code = '". $rowq['productcode']."'";
		$sql_result = mysql_query($sql);
		if ($sql_result <> FALSE)
		{
			$row = mysql_fetch_array($sql_result);
			$prod_desc= $row[0];
		}
		
		$sql = "select FORMAT(totamt,2) as totamt from prod_matmain ";
		$sql .= " where prod_code = '". $rowq['productcode']. "'";

		$sql_result = mysql_query($sql);
		if ($sql_result <> FALSE)
		{
			$row = mysql_fetch_array($sql_result);
			$totamt= $row[0];
		}
		if ($totamt =='' | $totamt == ' ' | $totamt == NULL)
		{
			$totamt = 0.00;
		}
		$totamt = number_format($rowq['amount'], 2);


		// end here//
				
				
				
		$amt = 0;
		$amt = number_format($rowq['uprice']* $rowq['issueqty'], 2);
		
		$ticketno = $rowq['ticketno'];
		if ($rowq['seqno']==2)
		{
			$rowq['ticketno']= '';
		}
			  
		echo '<tr class="item-row">';	
		echo '<td><input name="sequence[]" id="sequence" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>'; 
		echo '<td><input name="ticketno2[]" value="'.$rowq['ticketno'].'" id="ticketno2" readonly="readonly" style="border-style: none; border-color: none; border-width: 0; width: 75px;"></td>';
	  
		echo '<td><input name="productcode[]" value="'.htmlentities($rowq['productcode']).'" id="productcode'.$i.'" readonly="readonly" style="width: 161px; border-style: none;"></td>';
		echo '<td><input name="procouom[]" value="'.$rowq['uom'].'" id="procouom" style="width: 35px; border-style: none;" readonly="readonly"></td>'; 
		echo '<td><input name="procodesc[]" value="'.$prod_desc.'" id="procodesc" style="width: 303px; border-style: none;" readonly="readonly"></td>'; 
		echo '<td><input name="issueqty[]" class="tInput" value="'.$rowq['issueqty'].'" id="issueqtyid'.$i.'" onBlur="calcCost('.$i.');" style="width: 75px;"></td>';   
		echo '<td><input name="uprice[]" class="tInput" value="'.$rowq['uprice'].'" id="upriceid'.$i.'"  onBlur="calcCost('.$i.');"  style="width: 75px; "></td>';   
		echo '<td ><input name="amount[]" tMark="1" id="amount'.$i.'" readonly="readonly" style="width: 75px; border:0; " value="'.$amt.'"></td>';         
		echo '<td><input name="seqno[]" class="tInput" value="'.$rowq['seqno'].'" id="seqnoid'.$i.'"  readonly="readonly" style="display:none; width: 75px; border:0;"></td>';   
		echo '<td><input name="ticketno[]" class="tInput" value="'.$ticketno.'" id="ticketno'.$i.'"  readonly="readonly" style="display:none; width: 75px; border:0;"></td>';
		

		echo ' </tr>';
		
		$i = $i + 1;
	}
	echo '</tbody></table>';
    
?>
