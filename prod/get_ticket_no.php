<?php
$q=htmlentities($_GET['q']);

 if ($q <> "s") {
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");

	$yr = '';
	$mth = '';
	$ticketno = '';
	
	//eg : CURRENT_DATE - INTERVAL 3 DAY (get 3 days back punya date)
	//$result = mysql_query("select distinct MONTH(CURRENT_DATE - INTERVAL 3 DAY) ")or die ("Error 2 : " .mysql_error());
	$result = mysql_query("select distinct MONTH(CURRENT_DATE) ")or die ("Error 2 : " .mysql_error());
	$row = mysql_fetch_row($result);
	$mth = $row[0];
	
	$result = mysql_query("select distinct YEAR(CURRENT_DATE) ")or die ("Error 3 : " .mysql_error());
	$row = mysql_fetch_row($result);
	$yrlong = $row[0];
	$yr = substr($yrlong, -1);
	
	$sysno = '';
	$sqlchk = " select sysno from sew_sys_no ";
	$sqlchk.= "  where type = '". $q. "'";
	$sqlchk.= "  and month = '". $mth. "'";
	$sqlchk.= "  and yr = '". $yrlong. "'";

	$dumsysno= mysql_query($sqlchk) or die(mysql_error());
	while($row = mysql_fetch_array($dumsysno))
	{
		$sysno = $row['sysno'];        
	}
	if ($sysno ==NULL)
	{
		$sysno = '0';
				$sysno_sql = "INSERT INTO sew_sys_no values ('$q', '$yrlong', '$mth','$sysno')";

		mysql_query($sysno_sql) or die ("Error 4 :".mysql_error());

	}
	$newsysno = $sysno + 1;
	
	$newsysno  = str_pad($newsysno , 4, '0', STR_PAD_LEFT);
	$ticketno = $yr.$mth.$q.$newsysno;

	echo $ticketno;

	


                      
   
mysql_close($db_link);

  } else {
  
    echo "";
  }

?> 