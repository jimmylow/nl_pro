<?php 

  include("../Setting/Configifx.php");
	
	$db_link  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
  mysql_query("SET character_set_results=utf8", $db_link) or die(mysql_error());
	mb_language('uni');
	mb_internal_encoding('UTF-8');
	mysql_select_db("$var_db_name", $db_link);
	mysql_query("set names 'utf8'",$db_link);
	
	$subcode=$_GET['subcode'];
	//$supp_code=intval($_GET['supp_code']);

  //$query="SELECT rm_code FROM rawmat_master WHERE active_flag = 'ACTIVE'";

  //$result=mysql_query($query);


   echo '<table id="itemsTable" class="general-table">
          	<thead>
          	 <tr>
              <th class="tabheader" style="width: 27px; height: 57px;">#</th>
              <th class="tabheader">Subcode No</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">Cost Price</th>
              <th class="tabheader">New Cost Price</th>
             </tr>
            </thead>
            <tbody>';
     
             	$sql = "SELECT rm_code, description, cost_price ";
             	$sql.= " FROM rawmat_subcode ";
             	$sql.= " Where rm_code LIKE '".$subcode."%' ";
             	$sql.= " ORDER BY rm_code";
             	//echo $sql;

 
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){
					echo '<tr class="item-row">';	
					echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>'; 
					//echo '<td><input name="procomat[]" value="'.htmlentities($rowq['itemcode']).'" id="procomat'.$i.'" class="autosearch" style="width: 161px"></td>';
					echo '<td><input name="procomat[]" value="'.$rowq['rm_code'].'" id="procomat" style="width: 303px; border-style: none;" readonly="readonly"></td>';
          echo '<td><input name="procodesc[]" value="'.$rowq['description'].'" id="procodesc" style="width: 303px; border-style: none;" readonly="readonly"></td>';
          echo '<td><input name="costprice[]" readonly = "readonly" class="tInput" value="'.$rowq['cost_price'].'" id="costprice'.$i.'" onBlur="calcCost('.$i.');" style="width: 75px;"></td>'; 
          echo '<td><input name="newcostprice[]" class="tInput" value="'.$rowq['cost_price'].'" id="newcostprice'.$i.'" onBlur="calcCost('.$i.');" style="width: 75px;"></td>';              	
          echo ' </tr>';
               	
          $i = $i + 1;
         }
  echo '</tbody></table>';
  
    
?>
