<?php
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	
	$var_loginid = $_SESSION['sid'];
	$var_menucode = "PRODSEW01";
	include("../Setting/ChqAuth.php");
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */ 
	$aColumns = array('sordno', 'sorddte', 'sexpddte', 'sbuycd', 'app_stat' , 'stat');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "sordno";
	
	/* DB table to use */
	$sTable = "salesentry";
	
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
	
	$sJoin = " LEFT JOIN (SELECT sordno AS sdo, app_stat FROM salesappr) AS s ON s.sdo = a.sordno ";
	//$sLimit = " LIMIT 1 ";
	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
		FROM salesentry AS a
		$sJoin
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
				if ($aColumns[$i] == "sordno"){
					$pon = $aRow[ $aColumns[$i] ];
					$row3[] = $aRow[ $aColumns[$i] ];
					$row[] = $aRow[ $aColumns[$i] ];
				}else{
					if ($aColumns[$i] == "sorddte"){
						if ($aRow[ $aColumns[$i] ] == '1970-01-01'){
							$row[] = NULL;
						}else{
							if ($aRow[ $aColumns[$i] ] == NULL){
								$row[] = NULL;
							}else{
								$row[] = date('d-m-Y', strtotime($aRow[ $aColumns[$i] ]));;
							}
						}
					}else{
						if ($aColumns[$i] == "sexpddte"){
							if ($aRow[ $aColumns[$i] ] == '1970-01-01'){
								$row[] = NULL;
							}else{
								if ($aRow[ $aColumns[$i] ] == NULL){
									$row[] = NULL;
								}else{
									$row[] = date('d-m-Y', strtotime($aRow[ $aColumns[$i] ]));;
								}
							}
						}else if ($aColumns[$i] != "app_stat"){
							$row[] = $aRow[ $aColumns[$i] ];							
						}
					}
				}
			}
		}
		$urlpop = 'upd_saleentry.php';
		$urlvie = 'vm_saleentry.php';
		$sstat = $aRow['app_stat'];
		
		if ($var_accvie == 0){
			$row[] = '<a href="#">[VIEW]</a>';
		}else{
			$row[] = '<a href="'.$urlvie.'?sorno='.$aRow['sordno'].'&buycd='.$aRow['sbuycd'].'&menucd='.$var_menucode.'">[VIEW]</a>';
		}	
		
		$row[] = '<a href="m_sale_form.php?p=Print&sno='.$aRow['sordno'].'&buycd='.$aRow['sbuycd'].'&menucd='.$var_menucode.'" title="Print Purchase Order"><img src="../images/b_print.png" border="0" width="16" height="16" hspace="2" alt="Duplicate Purchase Order" /></a>'; 

		if ($var_accupd == 0){
			$row[] = '<a href="#">[EDIT]</a>';
		}else{
			if ($sstat == "APPROVE"){
				$row[] = '<a href="#" title="This Purchase Order Is Approved; Edit Is Not Allow">[EDIT]</a>';
			}else{ 
				$row[] = '<a href="'.$urlpop.'?sorno='.$aRow['sordno'].'&buycd='.$aRow['sbuycd'].'&menucd='.$var_menucode.'">[EDIT]</a>';
			}
		}
		if ($var_accdel == 0){
			$row[] = '<input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />';
		}else{
		  if ($sstat == "APPROVE"){
			$row[] = '<input type="checkbox" title="This Purchase Order Is Approved; Edit Is Not Allow" DISABLED  name="procd[]" value="'.$values.'" />';
		  }else{	
			$values = implode(',', $aRow);	
			$row[] = '<input type="checkbox" name="salorno[]" value="'.$values.'" />';
		  }	
		}
		
		if ($var_accdel == 0){
			$row[] = '<input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />';
		}else{
		  if ($sstat == "APPROVE"){
			$row[] = '<input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />';
		  }else{	
			$values = implode(',', $aRow);	
			$row[] = '<input type="checkbox" name="salorno[]" value="'.$values.'" />';
		  }	
		}
		
		if ($var_accdel == 0){
			$row[] =  '<input type="checkbox" DISABLED  name="procd[]" value="'.$values.'" />';
		}else{
		  if ($sstat == "APPROVE"){
			$row[] =  '<input type="checkbox" title="This Purchase Order Is Approved; Cannot Delete" DISABLED  name="procd[]" value="'.$values.'" />';
		  }else{	
			$values = implode(',', $aRow);	
			if ($postat <> "CANCEL"){       		
				$row[] = '<input type="checkbox" name="salorno[]" value="'.$values.'" />';
			}else{
				$row[] = '<input type="checkbox" DISABLED  name="salorno[]" value="'.$values.'" />';
			}
		  }	
		}
    	
		//----------------------------------------------------
		
		$output['aaData'][] = $row;
	}
	
	echo json_encode( $output );
?>

