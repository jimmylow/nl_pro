<?php
    include("../Setting/Configifx.php");
	
	$db_link  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
  	mysql_query("SET character_set_results=utf8", $db_link) or die(mysql_error());
	mb_language('uni');
	mb_internal_encoding('UTF-8');
	mysql_select_db("$var_db_name", $db_link);
	mysql_query("set names 'utf8'",$db_link);
	
  if(!isset($_GET['areacd']) || !$method = $_GET['areacd']) exit; 
	$ticketno=$_GET['areacd'];
	$qcdate=$_GET['sewdate'];
	$scanflg = 0;

    if ($ticketno <> "") {
	  $cntvalid = 0;	
	  $sql = "select count(*) as cnt from sew_entry  ";
	  $sql .= " where ticketno ='$ticketno'";
	  $sql_result = mysql_query($sql);
	  $row = mysql_fetch_array($sql_result);
	  $cntvalid = $row[0];
	  $var_loginid = $_SESSION['sid'];


	  if ($cntvalid==0){
		echo "<font color=red> Invalid Sewing Ticket No.</font>";
	  }else{
	  

		  $var_sql = " SELECT count(*) as cnt from sew_qc ";
		  $var_sql .= " WHERE ticketno = '$ticketno'";
		  //echo $var_sql . "</br>";


		  $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
		  $res_id = mysql_fetch_object($query_id);
		 
		  if ($res_id->cnt > 0 ) {
			
		   echo "<font color=red><strong></strong> This Ticket Has Been Keyed in QC Entry</strong></font>";
		   
		  }else {
		  	//here//
		  	
	        
	        
    		$sql = "SELECT productqty, ticketno, productcode, batchno, DATE_FORMAT(orderdate,'%d-%m-%Y')  as orderdate, buyer from sew_entry ";
			$sql .= " where ticketno = '". $ticketno. "'";
			$sql_result = mysql_query($sql);
			if ($sql_result <> FALSE)
			{
				$row = mysql_fetch_array($sql_result);
				$productqty= $row[0];
				$ticketno= $row[1];
				$productcode= $row[2];
				$batchno= $row[3];
				$orderdate= $row[4];
				$buyer = $row[5];
			}

	        $okqty = 0;
	        $okqty = $productqty;
	        $defectqty = 0;
	        $newproduct = '';
	        $qtydoz = 0;
	        
	        $vartoday = date("Y-m-d H:i:s");
			$sql = "INSERT INTO sew_qc values 
					('$ticketno','$qcdate', '$okqty', '$defectqty', '0','$newproduct',
					'$var_loginid', '$vartoday','$var_loginid', '$vartoday', 'Y')";
			mysql_query($sql) or die("Error in Sew QC Entry:".mysql_error(). ' Failed SQL is --> '. $sql);
			//echo $sql; break;
			
			$sql = "INSERT INTO sew_qc_tran values 
					('$ticketno','$qcdate', '$productcode', '$okqty', '1')";
			mysql_query($sql) or die("Error in Sew QC Trans 1:".mysql_error(). ' Failed SQL is --> '. $sql);
			

			
			$sql2 = "UPDATE sew_entry ";
			$sql2.= " SET qcdate = '$qcdate', qcqty = '$okqty' ";
			$sql2.= " WHERE ticketno = '$ticketno' ";
			mysql_query($sql2) or die("Error Update Sew Entry : ".mysql_error(). ' Failed SQL is --> '. $sql2);
		
			
		  //---------------- After QC-->, deduct from WIP, Add into FG Stock // CeDRiC WaN 20131115
		  // insert into WIP onhand balance table (MINUS WIP STOCK) ---------------//
	        $uprice = 0;
	        $negissueqty = 0 - $okqty;
	        $buyerord = '';
	        
	        $result = mysql_query("select buyerorder from sew_entry where ticketno = '$ticketno' ")or die ("Cant get buyer order # : " .mysql_error());
			$row = mysql_fetch_row($result);
			$buyerord = $row[0];


	    	$sql = "select FORMAT(totamt,2) as totamt from prod_matmain ";
			$sql .= " where prod_code = '". $productcode. "'";
			$sql_result = mysql_query($sql);
			if ($sql_result <> FALSE)
			{
				$row = mysql_fetch_array($sql_result);
				$uprice= $row[0];
			}
			//echo $sql; echo '</br>';
			//echo $uprice; echo '</br>';
			
			$result = mysql_query("select prod_desc from pro_cd_master where prod_code = '$productcode' ")or die ("Cant get desc from  pro_cd_master: " .mysql_error());
			$row = mysql_fetch_row($result);
			$desc = $row[0];
			
			if ($uprice=='')
			{
				$uprice = 0;
			}

			
	        $sql3 = "INSERT INTO wip_tran values 
			  		('ISS', '$ticketno', '$batchno','$buyerord', '$qcdate', '$productcode', '$uprice', '$desc', '$qtydoz', '$negissueqty','$var_loginid', '$vartoday','$var_loginid', '$vartoday')";
			mysql_query($sql3) or die("Error Insert WIP history table :".mysql_error(). ' Failed SQL is -->'. $sql3);	
			//echo $sql3; break;
			
			
			//Insert into FG Table//
			$sql4 = "INSERT INTO fg_tran values 
			  		('REC', '$ticketno', '$batchno','$buyerord', '$qcdate', '$productcode', '$uprice', '$desc', '$qtydoz', '$okqty','$var_loginid', '$vartoday','$var_loginid', '$vartoday')";
			mysql_query($sql4) or die("Error Insert FG history table :".mysql_error(). ' Failed SQL is -->'. $sql4);	
			//echo $sql3; break;

			
			
			// end of Insert into FG Table//

			
  
	      //---------------------end of insert ----------------------------//
	  
	  		// end here// 2014-04-11
		  
				echo "<font color=green>Sewing QC Updated </font>";
			}
		 
			// end checking
		  

		  }
      } 
      
?> 