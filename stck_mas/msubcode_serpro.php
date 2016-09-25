<?php
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	
	$var_loginid = $_SESSION['sid'];
	$var_menucode = "STKMAS07";
	include("../Setting/ChqAuth.php");
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	$sql  = " Delete From tmpsubcd where usernm = '$var_loginid'";
   	mysql_query($sql) or die("Unable To Prepare Temp Table".mysql_error());

	$sql = "SELECT rm_code, density, description, colour, cost_price, active_flag, minqty, maxqty ";
	$sql .= " FROM rawmat_subcode ";
	//$sql .= " where active_flag = 'ACTIVE'";
   	$sql .= " ORDER BY rm_code"; 
	$rs_result = mysql_query($sql); 
		   			
	$numi = 1;
	while ($row = mysql_fetch_assoc($rs_result)){
		$rmcode = htmlentities($row['rm_code']);
		$density = mysql_real_escape_string($row['density']);
		$desc = mysql_real_escape_string($row['description']);
		$cstpri = $row['cost_price'];
		$stat = $row['active_flag'];
		$minqty = $row['minqty'];
		$maxqty = $row['maxqty'];
		
		if ($stat=='ACTIVE')
		{
      		$stat = 'A';
		}else{
			$stat= 'D';
		}

		$sqlc = "select colour_desc from colour_master";
        $sqlc .= " where colour_code ='".$row['colour']."'";
        $sql_resultc = mysql_query($sqlc);
        $rowcol = mysql_fetch_array($sql_resultc);
        $colour = $rowcol[0];
        
		//$sqla = "select sum(totalqty * myr_unit_cost) / sum(totalqty) from rawmat_receive_tran ";
	    //$sqla .= " where item_code ='".htmlentities($rmcode)."' ";
	    //$sql_resulta = mysql_query($sqla);
	    //$row_avga = mysql_fetch_array($sql_resulta); 
	    
	    //echo $sqla;       
	    //if ($row_avga[0] == "" or $row_avga[0] == null){ 
	    //    $row_avga[0]  = 0.00;
	    //}
	    //$avg_cost = round($row_avga[0], 3);   
	    //if ($avg_cost== 0)
	    //{
	    //  	$avg_cost = $row['cost_price'];
	    //}
	    
	    $sqlo = "select sum(totalqty) from rawmat_tran ";
	    $sqlo .= " where item_code ='$rmcode'";
	    $sqlo .= " and tran_type in ('REC', 'ADJ', 'RTN', 'ISS', 'OPB', 'REJ')";
	    $sql_resulto = mysql_query($sqlo);
	    $row_bal = mysql_fetch_array($sql_resulto);        
	    if ($row_bal[0] == "" or $row_bal[0] == null){ 
	       $row_bal[0]  = 0.00;
	    }
	    $onhandbal = $row_bal[0];
	    
	    if ($onhandbal > $maxqty || $onhandbal < $minqty){
	    	$rmcode = "~".$rmcode;
	    }
		 
		$sqliq  = " Insert Into tmpsubcd (rmcode, density, description, colde, ";
        $sqliq .= "  cost_pri, onbal, stat, usernm, seqno) ";
        $sqliq .= " Values ('$rmcode', '$density', '$desc', '$colour', '$cstpri',";
        $sqliq .= "   '$onhandbal', '$stat', '$var_loginid', '$numi')";
        mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
        //echo $sqliq;
        //die;
		$numi = $numi + 1;
	}
 
	$aColumns = array('seqno', 'rmcode', 'density', 'description', 'colde','cost_pri','onbal', 'stat');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "rmcode";
	
	/* DB table to use */
	$sTable = "tmpsubcd";
	
	/* Database connection information */
	$gaSql['user']       = $var_userid;
	$gaSql['password']   = $var_password;
	$gaSql['db']         = $var_db_name;
	$gaSql['server']     = $var_server;
	
	/* REMOVE THIS LINE (it just includes my SQL connection user/pass) */
	/*include( $_SERVER['DOCUMENT_ROOT']."/datatables/mysql.php" );*/
	
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
	 * no need to edit below this line
	 */
	
	/* 
	 * Local functions
	 */
	function fatal_error ( $sErrorMessage = '' )
	{
		header( $_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error' );
		die( $sErrorMessage );
	}

	
	/* 
	 * MySQL connection
	 */
	if ( ! $gaSql['link'] = mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) )
	{
		fatal_error( 'Could not open connection to server' );
	}

	if ( ! mysql_select_db( $gaSql['db'], $gaSql['link'] ) )
	{
		fatal_error( 'Could not select database ' );
	}

	/* 
	 * Paging
	 */
	$sLimit = "";
	if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
	{
		$sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
			intval( $_GET['iDisplayLength'] );
	}
	
	
	/*
	 * Ordering
	 */
	$sOrder = "";
	if ( isset( $_GET['iSortCol_0'] ) )
	{
		$sOrder = "ORDER BY  ";
		for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		{
			if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
			{
				$sOrder .= "`".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."` ".
				 	mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
			}
		}
		
		$sOrder = substr_replace( $sOrder, "", -2 );
		if ( $sOrder == "ORDER BY" )
		{
			$sOrder = "";
		}
	}
	
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$sWhere = "";
	if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
	{
		$sWhere = "WHERE (";
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" )
			{
				$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
			}
		}
		$sWhere = substr_replace( $sWhere, "", -3 );
		$sWhere .= ')';
	}
	
	/* Individual column filtering */
	for ( $i=0 ; $i<count($aColumns) ; $i++ )
	{
		if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
		{
			if ( $sWhere == "" )
			{
				$sWhere = "WHERE ";
			}
			else
			{
				$sWhere .= " AND ";
			}
			$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
		}
	}
	
	if ( $sWhere == "" )
	{
		$sWhere = "WHERE usernm ='$var_loginid'";
	}
	else
	{
		$sWhere .= " AND usernm ='$var_loginid'";
	}
	
	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
		";


	$rResult = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_errno() );
	
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_errno() );
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT(`".$sIndexColumn."`)
		FROM   $sTable
	";
	$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_errno() );
	$aResultTotal = mysql_fetch_array($rResultTotal);
	$iTotal = $aResultTotal[0];
	
	
	/*
	 * Output
	 */
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
	
	while ( $aRow = mysql_fetch_array( $rResult ) )
	{
		$row = array();
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( $aColumns[$i] == "version" )
			{
				/* Special output formatting for 'version' column */
				$row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
			}
			else if ( $aColumns[$i] != ' ' )
			{
				/* General output */
				if ($aColumns[$i] == "rmcode"){
					$subcode = $aRow[ $aColumns[$i] ];
				}	
				$row[] = $aRow[ $aColumns[$i] ];
			}
		}
		
    	$urlpop = 'upd_rm_sub.php?rawmatcd='.htmlentities($subcode).'&menucd='.$var_menucode;
		$urlvm = 'vm_rm_sub.php?rawmatcd='.htmlentities($subcode).'&menucd='.$var_menucode;
		$urluplp = 'upd_rmpic_sub.php?rawmatcd='.htmlentities($subcode).'&menucd='.$var_menucode;
		$urlpoppic = "javascript:poptastic('".$urluplp."')";				
		
		$row[] = '<a href="'.$urlvm.'">[VIEW]</a>';
		
		if ($var_accupd == 0){
			$row[] = '<a href="#" title="You Are Not Authorise To Update Detail Item Code">[EDIT]</a>';
		}else{	
			$row[] = '<a href="'.$urlpop.'">[EDIT]</a>';
		}
		
		if ($var_accdel == 0){
			$row[] = '<input type="checkbox" name="rmscd[]" value="'.$subcode.'" DISABLED title="You Are Not Authorise To Active/Deactivate Item Code"/>';
		}else{		
			$row[] = '<input type="checkbox" name="rmscd[]" value="'.$subcode.'" />';
		}
		
		if ($var_accupd == 0){
			$row[] = '<a href="#" title="You Are Not Authorise To Manage Item Picture">[Upload]</a>';
		}else{	
			$row[] = '<a href="'.$urlpoppic.'">[Upload]</a>';
		}
		
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?>