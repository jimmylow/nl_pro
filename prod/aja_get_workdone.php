<?php 

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	
	$var_loginid = $_SESSION['sid'];
	//$var_loginid = 'admin';
	$var_menucode = "PRODSEW02";
	include("../Setting/ChqAuth.php");

	
	$db_link  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
	mysql_query("SET character_set_results=utf8", $db_link) or die(mysql_error());
	mb_language('uni');
	mb_internal_encoding('UTF-8');
	mysql_select_db("$var_db_name", $db_link);
	mysql_query("set names 'utf8'",$db_link);

	$jobno= '%'. $_GET['jobno']. '%';
	//$ticketno='%'.$_GET['ticketno'].'%';

	//$jobno=  $_GET['jobno'];
	$ticketno= $_GET['ticketno'];

	echo '<table id="itemsTable" class="general-table">
		<thead>
			<tr>
				  <th class="tabheader" style="width: 27px;">#</th>
				   <th class="tabheader">Worker ID</th>
				  <th class="tabheader">Sewing Date</th>
				  <th class="tabheader">Ticket No.</th>
				  <th class="tabheader">Barcode</th>
				  <th class="tabheader">Product Code</th>
				  <th class="tabheader">Type</th>
				  <th class="tabheader" style="width: 78px">Job</th>
				  <th class="tabheader">Qty (Doz)</th>
				  <th class="tabheader">Qty (Pcs)</th>
				  <th class="tabheader" style="width: 12px">Detail</th>
                  <th class="tabheader" style="width: 12px">Update</th>
			 </tr>
		</thead>
		<tbody>';
		//$ticketno = '104B0427';
		//$jobno = '023';
		
	 $sql  = "SELECT x.workid as workid, x.sewdate as sewdate, x.ticketno, x.barcodeno as barcodeno, ";
	 $sql .= "  x.prod_code, y.sewtype as sewtype, prod_jobid, qtydoz, qtypcs "; 

	 $sql .= " FROM sew_barcode x, sew_entry y "; 
	 $sql .= " WHERE x.ticketno = y.ticketno ";
	 $sql .= " AND workid IS NOT NULL ";
	 $sql .= " AND workid <>  ''";
	 $sql .= " AND workid <>  ' '";
	 //$sql .= " AND x.ticketno like '$ticketno'";
	 $sql .= " AND x.prod_jobid like '$jobno'";
	 $sql .= " AND x.ticketno  =  '$ticketno'";
	 //$sql .= " AND x.prod_jobid =  '$jobno'";
	 if ($var_loginid == 'supera')
	 {
	 	echo $sql;
	 }
             	
	
	$rs_result = mysql_query($sql); 
   
	$i = 1;
	while ($rowq = mysql_fetch_assoc($rs_result))
	{
				
				
				
		$amt = 0;
		$amt = number_format($totamt* $rowq['qty'], 2);
		
		$ticketno = $rowq['ticketno'];
		$sewdate= date("Y-m-d", strtotime(htmlentities($rowq['sewdate'])));
		$barcodeno = $rowq['barcodeno'];
		
		$prod_code= $rowq['prod_code'];
		$sewtype= $rowq['sewtype'];
		$prod_jobid= $rowq['prod_jobid'];
		$qtydoz= $rowq['qtydoz'];
		$qtypcs= $rowq['qtypcs'];

		if ($rowq['seqno']==2)
		{
			$rowq['ticketno']= '';
		}
		echo '</br>';		  
		echo '<tr class="item-row">';	
		echo '<td><input name="sequence[]" id="sequence" value="'.$i.'" readonly="readonly" style="width: 27px; border:0;"></td>'; 
		echo '<td><input name="workid[]" value="'.$rowq['workid'].'" id="workid" readonly="readonly" style="border-style: none; border-color: none; border-width: 0; width: 70px;"></td>';
	  
		echo '<td><input name="sewdate[]" value="'.$sewdate.'" id="sewdate'.$i.'" readonly="readonly" style="width: 100px; border-style: none;"></td>';
		echo '<td><input name="ticketno[]" value="'.$ticketno.'" id="ticketno" style="width: 80px; border-style: none;" readonly="readonly"></td>'; 
		echo '<td><input name="barcodeno[]" value="'.$barcodeno.'" id="barcodeno" style="width: 120px; border-style: none;" readonly="readonly"></td>'; 
        echo '<td><input name="prod_code[]" value="'.$prod_code.'" id="prod_code" style="width: 120px; border-style: none;" readonly="readonly"></td>'; 
        echo '<td><input name="sewtype[]" value="'.$sewtype.'" id="sewtype" style="width: 50px; border-style: none;" readonly="readonly"></td>'; 
		echo '<td><input name="prod_jobid[]" value="'.$prod_jobid.'" id="prod_jobid" style="width: 50px; border-style: none;" readonly="readonly"></td>'; 
		echo '<td><input name="qtydoz[]" value="'.$qtydoz.'" id="qtydoz" style="width: 50px; border-style: none;" readonly="readonly"></td>'; 
		echo '<td><input name="qtypcs[]" value="'.$qtypcs.'" id="qtypcs" style="width: 50px; border-style: none;" readonly="readonly"></td>'; 
		
		$urlpop = 'upd_sew_workdone.php?barcodeno='.$barcodeno.'&menucd='.$var_menucode;
		$urlvm = 'vm_sew_workdone.php?barcodeno='.$barcodeno.'&menucd='.$var_menucode;		
		
    	if ($var_accvie == 0){
    		echo '<td align="center"><a href="#" title="You Are Not Authorice To View  The Record">[VIEW]</a>';'</td>';
    	}else{
    		echo '<td align="center"><a href="'.$urlvm.' ">[VIEW]</a></td>';
    	}
    
    	if ($var_accupd == 0){
    		echo '<td align="center"><a href="#" title="You Are Not Authorice To Amend Any Record">[EDIT]</a>';'</td>';
    	}else{
    		echo '<td align="center"><a href="'.$urlpop.' ">[EDIT]</a></td>';
    	}


		echo ' </tr>';
		
		$i = $i + 1;
	}
	echo '</tbody></table>';
    
?>
