<?php
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	
	$var_loginid = $_SESSION['sid'];
	$var_loginid = 'admin';
	$var_menucode = "PURC01";
	include("../Setting/ChqAuth.php");
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
	 $sql  = " Delete From tmpmpo where usernm = '$var_loginid'";
   	 mysql_query($sql) or die("Unable To Prepare Temp Table".mysql_error());
   	
   	$sql = "SELECT po_no, supplier, po_date, del_date";
	$sql .= " FROM po_master ";
	$sql .= " WHERE active_flag = 'ACTIVE'";
   	$sql .= " ORDER BY po_no"; 
	$rs_result = mysql_query($sql); 
		   			
	$numi = 1;
	while ($row = mysql_fetch_assoc($rs_result)){
		$pono = $row['po_no'];
		$suppcd = $row['supplier'];
		$podte = $row['po_date'];
		$dedte = $row['del_date'];
		
		$sqlc = "select supplier_desc from supplier_master";
        $sqlc .= " where supplier_code ='$suppcd'";
        $sql_resultc = mysql_query($sqlc);
        $rowcol = mysql_fetch_array($sql_resultc);
        $suoode = $rowcol[0];
        
        $cnt =0;
        $recvflg = "N";
 
 	$sqlcnt = "select count(*) from rawmat_receive";
        $sqlcnt .= " where po_number ='$pono'";
        $sql_resultcnt = mysql_query($sqlcnt);
        $rowcnt = mysql_fetch_array($sql_resultcnt);
        $cnt = $rowcnt[0];

 	$cnt = $rowcnt[0];
      
	if ($cnt>0)
        {
        	$recvflg = "Y";
        }


		$sqliq  = " Insert Into tmpmpo (seqno, pono, suppdesc, podate, ";
        $sqliq .= "  delidte, usernm, recvflg) ";
        $sqliq .= " Values ('$numi', '$pono', '$suoode', '$podte', '$dedte',";
        $sqliq .= "   '$var_loginid', '$recvflg')";
        mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
		$numi = $numi + 1;
	}
     
 
	$aColumns = array('seqno', 'pono', 'suppdesc', 'podate', 'delidte','recvflg');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "pono";
	
	/* DB table to use */
	$sTable = "tmpmpo";
	
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


	$rResult = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_error() );
	
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
				if ($aColumns[$i] == "pono"){
					$pon = $aRow[ $aColumns[$i] ];
				}
				if ($aColumns[$i] == "podate"){
					$row[] = date('d-m-Y', strtotime($aRow[ $aColumns[$i] ]));;
				}else{
					if ($aColumns[$i] == "delidte"){
						$row[] = date('d-m-Y', strtotime($aRow[ $aColumns[$i] ]));;
					}else{
						$row[] = $aRow[ $aColumns[$i] ];
					}
				}
				if ($aColumns[$i] == "recvflg"){
					$recvflg = $aRow[ $aColumns[$i] ];
				}


				
			}
		}
		
    	$urlpop = 'upd_po.php?po='.$pon.'&menucd='.$var_menucode;
		$urlvm = 'vm_po.php?po='.$pon.'&menucd='.$var_menucode;		
		
		if ($var_accvie == 0){
			$row[] = '<a href="#" title="You Are Not Authorice To View The record">[VIEW]</a>';
		}else{
			$row[] = '<a href="'.$urlvm.'" title="View Detail P/O">[VIEW]</a>';
		}
		
		$row[] = '<a href="m_po.php?p=Print&po='.$pon.'&menucd='.$var_menucode.'" title="Print P/O"><img src="../images/b_print.png" border="0" width="16" height="16" hspace="2" alt="Duplicate P/O" /></a>';	
		
		if ($var_accupd == 0){	
			$row[] = '<a href="#" title="You Are Not Authorice TO Amend Any Record">[EDIT]</a>';
		}else{	
			if ($recvflg=='N'){
				$row[] = '<a href="'.$urlpop.'" title="Update Detail P/O">[EDIT]</a>';
			}else{
				$row[] = '<a href="#" title="Item Received For This PO. Cannot Amend Any Record">[EDIT]</a>';
			}
		}
		if ($var_accdel == 0){	
			$row[] = '<input type="checkbox" DISABLED name="procd[]" value="'.$pon.'" title="You Are Not Authorice To Delete Record"/>';
		}else{
			//$row[] = '<input type="checkbox" name="procd[]" value="'.$pon.'" />';
			if ($recvflg=='N'){
				$row[] = '<input type="checkbox" name="procd[]" value="'.$pon.'" />';
			}else{
				$row[] = '<input type="checkbox" DISABLED name="procd[]" value="'.$pon.'" title="Item Received For This PO. Cannot DELETE Any Record"/>';

			}

		}	
		
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?>