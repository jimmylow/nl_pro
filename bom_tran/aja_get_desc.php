<?php 

  include("../Setting/Configifx.php");
	
	$db_link  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
  	mysql_query("SET character_set_results=utf8", $db_link) or die(mysql_error());
	mb_language('uni');
	mb_internal_encoding('UTF-8');
	mysql_select_db("$var_db_name", $db_link);
	mysql_query("set names 'utf8'",$db_link);
	
	$prod_jobid=$_GET['supp_code'];
	$productcode=$_GET['productcode'];

   echo '<table id="itemsTable" class="general-table">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 27px; height: 57px;">#</th>
              <th class="tabheader">Product Code</th>
              <th class="tabheader">Sec</th>
              <th class="tabheader">Job ID</th>
              <th class="tabheader">Rate</th>
             </tr>
            </thead>
            <tbody>';
                $cnt =0;
     			$cnt = strlen($productcode ); 
     			if ($cnt > 0)
     			{
     				$sql2 = " AND prod_code LIKE '". $productcode . "%'";
     			}else{
     				$sql2 = " AND prod_code LIKE '%". $productcode."%'";
     			}
     			
             	$sql = "SELECT prod_code, prod_jobsec, prod_jobid, prod_jobrate  ";
             	$sql.= " FROM pro_jobmodel ";
             	$sql.= " WHERE prod_jobid = '".$prod_jobid."'" ;
             	$sql.=  $sql2;
             	$sql.= " ORDER BY prod_code";
             	//echo $sql;
 
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){
             		echo '<tr class="item-row">';	
					echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>'; 
					echo '<td><input name="prod_code[]" value="'.$rowq['prod_code'].'" id="sec" style="width: 303px; border-style: none;" readonly="readonly"></td>';
					echo '<td><input name="prod_jobsec[]" value="'.$rowq['prod_jobsec'].'" id="sec" style="width: 303px; border-style: none;" readonly="readonly"></td>';
					echo '<td><input name="prod_jobid[]" value="'.$rowq['prod_jobid'].'" id="jobid" readonly="readonly" style="border-style: none; border-color: inherit; border-width: 0; width: 48px;"></td>';
					echo '<td><input name="prod_jobrate[]" class="tInput" value="'.$rowq['prod_jobrate'].'" id="rate'.$i.'" onBlur="calcCost('.$i.');" style="width: 75px;"></td>';              	
					echo '<td><input name="ori_jobrate[]" type="hidden" value="'.$rowq['prod_jobrate'].'"</td>';              	
					echo ' </tr>';
               	
          $i = $i + 1;
         }
  echo '</tbody></table>';
    
?>
