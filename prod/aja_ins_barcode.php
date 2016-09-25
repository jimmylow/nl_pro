<?php
    include("../Setting/Configifx.php");
    $var_loginid = $_SESSION['sid'];

	
	$db_link  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
    mysql_query("SET character_set_results=utf8", $db_link) or die(mysql_error());
	mb_language('uni');
	mb_internal_encoding('UTF-8');
	mysql_select_db("$var_db_name", $db_link);
	mysql_query("set names 'utf8'",$db_link);
	
  if(!isset($_GET['areacd']) || !$method = $_GET['areacd']) exit; 
	$barcodeno=$_GET['areacd'];
	$workerid=$_GET['workerid'];
	$sewdate=$_GET['sewdate'];
	$scanflg = 0;

    if ($barcodeno <> "") {
	  $cntvalid = 0;	
	  $sql = "select count(*) as cnt from sew_barcode  ";
	  $sql .= " where barcodeno ='$barcodeno'";
	  $sql_result = mysql_query($sql);
	  $row = mysql_fetch_array($sql_result);
	  $cntvalid = $row[0];


	  if ($cntvalid==0){
		echo "<font color=red> Invalid Sewing Barcode </font>";
	  }else{
	  

		  $var_sql = " SELECT count(*) as cnt from sew_barcode ";
		  $var_sql .= " WHERE barcodeno = '$barcodeno'";
		  $var_sql.= " AND  workid is not null ";
		  $var_sql.= " AND  workid <> '' ";
		  $var_sql.= " AND  workid <> ' ' ";
		  //echo $var_sql . "</br>";


		  $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
		  $res_id = mysql_fetch_object($query_id);
		 
		  if ($res_id->cnt > 0 ) {
			
		   echo "<font color=red><strong></strong> This Barcode Has Been Keyed in Work DONE</strong></font>";
		   
		  }else {
			$sql = "select ticketno from sew_barcode ";
			$sql .= " where barcodeno ='$barcodeno'";
			$sql_result = mysql_query($sql);
			$row = mysql_fetch_array($sql_result);
			$ticketno = $row[0];
			$vartoday = date("Y-m-d");
			
			//to check if barcode 30 days after QC, cannot scan and insert into system//
			
      //Change to 45 days - 27/01/2014 - request by maggie

			$sql = "SELECT qcdate FROM sew_qc_tran ";
			$sql.= " WHERE ticketno = '$ticketno' ";
			$sql_result = mysql_query($sql);
			$row = mysql_fetch_array($sql_result);
			$qcdate = $row[0];
			
			if ($qcdate =='')
			{
				$qcdate  = $vartoday ;
			}
			
			
			
			//$add_days = 30;
			$add_days = 45;
			$qcduedate = date('Y-m-d',strtotime($qcdate) + (24*3600*$add_days));
			
			
			//echo $sql . "</br>";
			//echo '45 - '. $qcduedate . "</br>";
			//echo 'qc - '. $qcdate . "</br>";
			//echo '2day - '. $vartoday . "</br>";
			
			//echo $qcdate . "</br>";
			//echo $qcduedate. "</br>";
			//echo $vartoday;
			if ($qcduedate < $vartoday)
			
			{
				echo "<font color=red><strong></strong> Ticket number for this barcode is 45 DAYS more than QC date. </strong></font>";
			}else{
				$sql = "select FLOOR(productqty/12) as qtydoz, productqty mod 12 as qtypcs from sew_entry ";
				$sql .= " where ticketno in (select ticketno from sew_barcode where barcodeno = '$barcodeno')";
				$sql_result = mysql_query($sql);
				$row = mysql_fetch_array($sql_result);
				$qtydoz = $row[0];
				$qtypcs = $row[1];
				
		   
				$sql = "UPDATE sew_barcode ";
				$sql.= " SET workid ='$workerid', sewdate = '$sewdate', qtydoz = '$qtydoz', qtypcs = '$qtypcs', modified_by = '$var_loginid', modified_on = '$vartoday', prog_name = 'aja_ins_barcode'";
				$sql.= " WHERE barcodeno = '$barcodeno'";
				mysql_query($sql) or die("Error update Sewing Ticket Barcode:".mysql_error(). ' Failed SQL is -->'. $sql);		
			  
				echo "<font color=green>Sewing BarcodeUpdate </font>";
			}
		 
			// end checking
		  

		  }
      } 
    }  
?> 