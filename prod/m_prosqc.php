<?php
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	
	$var_loginid = $_SESSION['sid'];
	$var_menucode = "PRODSEW03";
	include("../Setting/ChqAuth.php");
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */ 
	$aColumns = array('ticketno', 'qcdate', 'batchno', 'productcode', 'creation_time', 'modified_by');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "ticketno";
	
	/* DB table to use */
	//$sTable = "sew_qc x, sew_entry y";
	$sTable = " sew_qc x INNER JOIN (SELECT ticketno AS t_no, batchno, productcode FROM sew_entry) AS y on y.t_no = x.ticketno ";
	
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
		// $sWhere = "WHERE x.ticketno = y.ticketno";
	}
	else
	{
		//$sWhere .= " AND x.ticketno = y.ticketno";
	}
	
	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
		FROM   $sTable
		$sWhere
		$sOrder
		$sLimit
		";
	$rResult = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( '1. MySQL Error: ' . mysql_error() );
	
	/* Data set length after filtering */
	$sQuery = "
		SELECT FOUND_ROWS()
	";
	$rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( '2. MySQL Error: ' . mysql_errno() );
	$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "
		SELECT COUNT('".$sIndexColumn."')
		FROM   $sTable
	";
	$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( '3. MySQL Error: ' . mysql_errno() );
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
				if ($aColumns[$i] == "ticketno"){
					$pon = $aRow[$i];
					$row3[] = $aRow[$i];
					$row[] = $aRow[$i];
				}else{
					if ($aColumns[$i] == "qcdate"){
						if ($aRow[ $aColumns[$i] ] == '1970-01-01'){
							$row[] = NULL;
						}else{
							$row[] = date('d-m-Y', strtotime($aRow[$i]));;
						}
					}else{
						if ($aColumns[$i] == "creation_time"){
							if ($aRow[ $aColumns[$i] ] == '1970-01-01'){
								$row[] = NULL;
							}else{
								$row[] = date('d-m-Y', strtotime($aRow[$i]));;
							}
						}else{
							$row[] = $aRow[ $aColumns[$i] ];
						}
					}
				}
			}
		}

    	$urlpop = 'upd_sew_qc.php?ticketno='.$pon.'&menucd='.$var_menucode;
		$urlvm = 'vm_sew_qc.php?ticketno='.$pon.'&menucd='.$var_menucode;
				
		// to check the sewing QC, if have record, cannot delete
		$cnt = 0;
        $sql1 = "select count(*) from sew_do x, sew_do_tran y";
        $sql1 .= " where ticketno='".$pon."' ";
        $sql1 .= " and x.sew_doid = y.sew_doid ";
		$sql1 .= " and posted = 'Y'";
        $sql_result1 = mysql_query($sql1) or die("error query sew qc :".mysql_error());
        $row2 = mysql_fetch_array($sql_result1);
		$cnt = $row2[0];
		
		if ($var_accvie == 0){
			$row[] = '<a href="#" title="You Are Not Authorice To View The record">[VIEW]</a>';
		}else{
			$row[] = '<a href="'.$urlvm.'" title="View Detail">[VIEW]</a>';
		} 
		
		if ($var_accupd == 0){	
			$row[] = '<a href="#" title="You Are Not Authorice To Update Record">[EDIT]</a>';
		}else{
			if ($cnt <> 0 ){
	            $row[] = '<a href="#" title="This Ticket already in DO. Not Allowed To Edit">[EDIT]</a>';
	        }else{
		        $row[] = '<a href="'.$urlpop.'" title="Update Record">[EDIT]</a>';'</td>';
	        }
		}
		
		if ($var_accdel == 0 or $cnt <> 0){	
			$row[] = '<input type="checkbox" DISABLED name="procd[]" value="'.$pon.'" title="This Ticket# Already In Work Done/QC. Not Allowed To Delete" value="'.$values.'"/>';
		}else{
			$values = implode(',', $row3);
			$row[] = '<input type="checkbox" name="procd[]" value="'.$values.'" />';
		}	
		//here

		//----------------------------------------------------
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?>

