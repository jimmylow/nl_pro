<?php 

  include("../Setting/Configifx.php");
	
	$db_link  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
  mysql_query("SET character_set_results=utf8", $db_link) or die(mysql_error());
	mb_language('uni');
	mb_internal_encoding('UTF-8');
	mysql_select_db("$var_db_name", $db_link);
	mysql_query("set names 'utf8'",$db_link);
	
	$supp_code=$_GET['supp_code'];
	//$supp_code=intval($_GET['supp_code']);

  //$query="SELECT rm_code FROM rawmat_master WHERE active_flag = 'ACTIVE'";

  //$result=mysql_query($query);


   echo '<table id="itemsTable" class="general-table">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 27px; height: 57px;">#</th>
              <th class="tabheader">Raw Material Item</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Unit Cost</th>
              <th class="tabheader" style="width: 78px">Order Qty</th>
              <th class="tabheader">Tot Received</th>
              <th class="tabheader">Qty/Pack</th>
              <th class="tabheader">Receive Qty</th>
             </tr>
            </thead>
            <tbody>';
     
             	$sql = "SELECT x.seqno, x.itemcode,  z.description, z.uom, x.qty, x.uprice  ";
             	$sql.= " FROM po_master w, po_trans x, rawmat_subcode y, rawmat_master z ";
             	$sql.= " Where w.po_no= '".$supp_code."' AND w.po_no = x.po_no AND w.active_flag = 'ACTIVE' AND x.itemcode = y.rm_code AND y.main_code = z.rm_code ";
             	$sql.= " ORDER BY x.seqno";
             	//echo $sql;
             	

 
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){
					$sql2 = " select sum(totalqty) as receivedqty  ";
					$sql2.= " FROM rawmat_receive_tran ";
					$sql2.= " WHERE po_number =  '". $supp_code. "'";
					$sql2.= " AND item_code =  '". $rowq['itemcode']."'";
					
					$receivedqty = 0;
					$sql_result2 = mysql_query($sql2) or die("Cant Get Total Received Qty ".mysql_error());;
					$row2 = mysql_fetch_array($sql_result2);
					$receivedqty = $row2[0];
					if ($receivedqty=='' or $receivedqty== ' ' or $receivedqty ==NULL){ $receivedqty=0; }

             	
					echo '<tr class="item-row">';	
					echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>'; 
					echo '<td><input name="procomat[]" value="'.htmlentities($rowq['itemcode']).'" id="procomat'.$i.'" class="autosearch" style="width: 161px"></td>';
					echo '<td><input name="procodesc[]" value="'.$rowq['description'].'" id="procodesc" style="width: 303px; border-style: none;" readonly="readonly"></td>';
					echo '<td><input name="procouom[]" value="'.$rowq['uom'].'" id="procouom" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 48px;"></td>';
					echo '<td><input name="uprice[]" tMark="1" id="uprice" readonly="readonly" style="width: 75px; border:0;" value="'.$rowq['uprice'].'"></td>';
					echo '<td><input name="procomark[]" tMark="1" id="procomark'.$i.'" readonly="readonly" style="width: 75px; border:0;" value="'.$rowq['qty'].'"></td>';         
					echo '<td><input name="receivedqty[]" tMark="1" id="receivedqty'.$i.'" readonly="readonly" style="width: 75px; border:0;" value="'.$receivedqty.'"></td>';
					echo '<td><input name="qtyperpack[]" class="tInput" value="'.$rowq['qtyperpack'].'" id="qtyperpack'.$i.'" onBlur="calcCost('.$i.');" style="width: 75px;"></td>';  
					echo '<td><input name="issueqty[]" class="tInput" value="'.$rowq['totalqty'].'" id="issueqtyid'.$i.'" onBlur="calcCost('.$i.');" style="width: 75px;"></td>';              	
					echo ' </tr>';
               	
          $i = $i + 1;
         }
  echo '</tbody></table>';
    
?>
