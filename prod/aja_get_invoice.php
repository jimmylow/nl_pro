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
     
	$sql = "SELECT x.sew_doid, x.buyer, x.dodate, x.totproduct, x.totdefect, x.totgrand  ";
	$sql.= " FROM sew_do  x ";
	$sql.= " where  x.buyer = '$supp_code' ";
	$sql.= " and x.posted = 'Y'  ";
	$sql.= " and x.invflg = 'N' ";
	$sql.= " ORDER BY x.sew_doid ";
	//echo $sql;
             	
	//$sql = "SELECT x.ticketno, x.productcode, x.seqno, qty FROM sew_qc_tran x, sew_entry y where x.ticketno = y.ticketno and buyer = 'MDF' and x.qcdate between '2013-10-01' and '2013-10-23' ORDER BY x.ticketno, x.seqno";


	$rs_result = mysql_query($sql); 
   
	$i = 1;
	if ($rs_result <> FALSE)
	{
	//here
		echo '<table id="itemsTable" class="general-table">
		<thead>
			<tr>
				  <th class="tabheader" style="width: 27px; height: 57px;">#</th>
				  <th class="tabheader">Do No.</th>
				  <th class="tabheader">Buyer Name</th>
					<th class="tabheader">DO Date</th>
				  <th class="tabheader">DO Amount</th>
				  <th class="tabheader">Tick To Select</th>
			 </tr>
		</thead>
		<tbody>';
	// end here
	
		while ($rowq = mysql_fetch_assoc($rs_result))
		{
			//here//
			$sql = "select supplier_desc from supplier_master ";
			$sql .= " where supplier_code = '". $supp_code."'";
			$sql_result = mysql_query($sql);
			if ($sql_result <> FALSE)
			{
				$row = mysql_fetch_array($sql_result);
				$supplier_desc= $row[0];
			}
			
			// end here//
					
			$dodate= date('Y-m-d', strtotime($rowq['dodate']));
			
			$ticketno = $rowq['ticketno'];
			if ($rowq['seqno']==2)
			{
				$rowq['ticketno']= '';
			}
				  
			echo '<tr class="item-row">';	
			echo '<td><input name="sequence[]" id="sequence" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>'; 
			echo '<td><input name="donum[]" value="'.$rowq['sew_doid'].'" id="sew_doid" readonly="readonly" style="border-style: none; border-color: none; border-width: 0; width: 75px;"></td>';
		  
			echo '<td><input name="supplier_desc[]" value="'.$supplier_desc.'" id="supplier_desc" style="width: 303px; border-style: none;" readonly="readonly"></td>'; 
			echo '<td><input name="dodate[]" class="tInput" value="'.$dodate.'" id="upriceid'.$i.'"  readonly="readonly" style="width: 75px; border:0;"></td>';   
			echo '<td ><input name="totgrand[]" tMark="1" id="totgrand'.$i.'" readonly="readonly" style="width: 75px; border:0; " value="'.$rowq['totgrand'].'"></td>';         
			echo '<td align="center"><input type="checkbox" name="procd[]"  value="'.$rowq['sew_doid'].'"/>'.'</td>';
			echo ' </tr>';
			
			$i = $i + 1;
		}
		echo '</tbody></table>';
	}else{
		echo 'No Invoice to display';
		
	}

	

    
?>
