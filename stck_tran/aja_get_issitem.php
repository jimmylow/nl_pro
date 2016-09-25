<?php 

  include("../Setting/Configifx.php");
	
	$db_link  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
  mysql_query("SET character_set_results=utf8", $db_link) or die(mysql_error());
	mb_language('uni');
	mb_internal_encoding('UTF-8');
	mysql_select_db("$var_db_name", $db_link);
	mysql_query("set names 'utf8'",$db_link);
	$issue_id=$_GET['issue_id'];

   	
   echo '<table id="itemsTable" class="general-table">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 27px; height: 57px;">#</th>
              <th class="tabheader">Raw Material Item</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader" style="width: 78px">Issue Qty</th>
              <th class="tabheader">Return Qty</th>
             </tr>
            </thead>
            <tbody>';
     
             	$sql = "SELECT seqno, item_code, description, oum, totalqty  ";
             	$sql.= " FROM rawmat_issue_tran";
             	$sql.= " Where rm_issue_id = '".$issue_id."'";
             	$sql.= " ORDER BY seqno";
             	//echo $sql;
 
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){
					echo '<tr class="item-row">';	
					echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>'; 
					echo '<td><input name="procomat[]" value="'.htmlentities($rowq['item_code']).'" id="procomat'.$i.'" class="autosearch" style="width: 161px"></td>';
          echo '<td><input name="procodesc[]" value="'.$rowq['description'].'" id="procodesc" style="width: 303px; border-style: none;" readonly="readonly"></td>';
          echo '<td><input name="procouom[]" value="'.$rowq['oum'].'" id="procouom" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 48px;"></td>';
          echo '<td><input name="procomark[]" tMark="1" id="procomark'.$i.'" readonly="readonly" style="width: 75px; border:0;" value="'.$rowq['totalqty'].'"></td>';         
          echo '<td><input name="returnqty[]" class="tInput" value="" id="returnqtyid'.$i.'" onBlur="calcCost('.$i.');" style="width: 75px;"></td>';              	
          echo ' </tr>';
               	
          $i = $i + 1;
         }
  echo '</tbody></table>';
    
?>
