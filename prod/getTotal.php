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

	$totproduct = 0;
	$totdefect = 0;
	$totgrand = 0;


	$sql = "SELECT sum(qty *totamt) as totproduct  ";
	$sql.= " FROM sew_qc_tran x, sew_entry y, prod_matmain z, pro_cd_master a ";
	$sql.= " where x.ticketno = y.ticketno ";
	$sql.= " and x.productcode = z.prod_code ";
	$sql.= " and a.prod_code = z.prod_code ";
	$sql.= " and buyer = '$supp_code' ";
	$sql.= " and x.qcdate between '$frdate' and '$todate' ";
	$sql.= " and seqno = '1'";

	
	//test here//
     $result = mysql_query($sql) or die ("Error : ".mysql_error());

     $data = mysql_fetch_object($result); 
    
     $totproduct = $data->totproduct;     
     if ($totproduct == '' | $totproduct == ' ' | $totproduct== NULL)
     {
		$totproduct = 0.00;
     }
     
	
	$sql = "SELECT sum(qty *totamt) as totdefect  ";
	$sql.= " FROM sew_qc_tran x, sew_entry y, prod_matmain z, pro_cd_master a ";
	$sql.= " where x.ticketno = y.ticketno ";
	$sql.= " and x.productcode = z.prod_code ";
	$sql.= " and a.prod_code = z.prod_code ";
	$sql.= " and buyer = '$supp_code' ";
	$sql.= " and x.qcdate between '$frdate' and '$todate' ";
	$sql.= " and seqno = '2'";

	
	//test here//
     $result = mysql_query($sql) or die ("Error : ".mysql_error());

     $data = mysql_fetch_object($result); 
    
     $totdefect = $data->totdefect;     
     
     if ($totdefect == '' | $totdefect == ' ' | $totdefect== NULL)
     {
		$totdefect = 0.00;
     }
     
     
    $totgrand = 0; 
    $totgrand = $totproduct + +$totdefect;
    $totgrand = number_format($totgrand, 2);
    $totproduct = number_format($totproduct, 2);
    $totdefect = number_format($totdefect, 2);
    
  
    echo $totproduct."^".$totdefect."^".$totgrand;         
   
	mysql_close($db_link);



?> 