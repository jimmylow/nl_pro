<?php
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");		
	
	if(!isset($_GET['bu']) || !$method = $_GET['bu']) exit;	
    if(!isset($_GET['probuypre']) || !$method = $_GET['probuypre']) exit;
    if(!isset($_GET['protyp']) || !$method = $_GET['protyp']) exit; 
    if(!isset($_GET['procatst']) || !$method = $_GET['procatst']) exit;
    if(!isset($_GET['procatto']) || !$method = $_GET['procatto']) exit;	
     
	$bbuy    = $_GET['bu'];	 
	$bbuypre = $_GET['probuypre'];
	$bprotyp = $_GET['protyp'];
	$bcatst  = $_GET['procatst'];
	$bcatto  = $_GET['procatto'];

    $chkflg = '1';
    
	for ($i = $bcatst ; $i <= $bcatto; $i++)
	{	
	
    	$var_sql = " SELECT count(*) as cnt from pro_cat_master ";
    	$var_sql .= " WHERE pro_cat_prefix = '$bbuypre'";
    	$var_sql .= " and CAST('$i' as SIGNED) between CAST(pro_cat_frnum as SIGNED) and CAST(pro_cat_tonum AS SIGNED)";
    	$var_sql .= " and pro_type_cd = '$bprotyp' and pro_buy_cd = '$bbuy'";

        $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
    	$res_id = mysql_fetch_object($query_id);

    	if ($res_id->cnt > 0 ) {
    	   $chkflg = '0';
		   echo "1";
		   break;
		}
	}
	if ($chkflg == '1'){
    echo "0";
	}
?> 