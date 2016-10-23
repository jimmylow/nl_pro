<?php
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	
	$var_loginid = $_SESSION['sid'];
	//$var_loginid = 'admin';
	$var_menucode = "BOMTRAN01";
	include("../Setting/ChqAuth.php");
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Easy set variables
	 */
	
	/* Array of database columns which should be read and sent back to DataTables. Use a space where
	 * you want to insert a non-database field (for example a counter or static image)
	 */
   	 
	$aColumns = array('prod_code', 'create_by', 'docdate');
	
	/* Indexed column (used for fast and accurate table cardinality) */
	$sIndexColumn = "prod_code";
	
	/* DB table to use */
	$sTable = "prod_matmain";
	
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
	//print_r($_GET);
	//if ( isset( $_GET['iSortCol_0'] ) )
	//{
		$sOrder = "ORDER BY  prod_code";
		//for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
		//{
		//	if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
		//	{
		//		$sOrder .= "`".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."` ".
		//		 	mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
		//	}
		//}
		
		//$sOrder = substr_replace( $sOrder, "", -2 );
		//if ( $sOrder == "ORDER BY" )
		//{
		//	$sOrder = "";
		//}
	//}
	
	
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
		
	/*
	 * SQL queries
	 * Get data to display
	 */
	$sQuery = "
		SELECT SQL_CALC_FOUND_ROWS `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
		, rpt.prod_desc FROM   $sTable AS m
		INNER JOIN (select prod_desc, prod_code AS pcode from pro_cd_master)
		AS rpt ON rpt.pcode = m.prod_code
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
	
	$j = 1;
	while ( $aRow = mysql_fetch_array( $rResult ) )
	{
		$row = array();
		$row[] = $j;
		for ( $i=0 ; $i < count($aColumns); $i++ )
		{
			if ( $aColumns[$i] == "version" ){
				/* Special output formatting for 'version' column */
				$row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
			}else if( $aColumns[$i] != ' ' ){
				/* General output */
				if ($aColumns[$i] == "prod_code"){
					$row[] = $aRow[ $aColumns[$i] ];
					$pcd = htmlentities($aRow[ $aColumns[$i] ]);
				}else{
					if ($aColumns[$i] == "create_by"){
						//$sql2 = "select prod_desc from pro_cd_master ";
        				//$sql2 .= " where prod_code ='".$pcd."'";
        				//$sql_result2 = mysql_query($sql2);
        				//$row2 = mysql_fetch_array($sql_result2);
        				//$pcdde = $row2[0];
        				//$row[] = $pcdde;
						$row[] = $aRow[ "prod_desc" ];
					}else{
						if ($aColumns[$i] == "docdate"){
							$row[] = date('d-m-Y', strtotime($aRow[ $aColumns[$i] ]));;
						}else{
							$row[] = $aRow[ $aColumns[$i] ];
						}
					}	
				}	
			}
		}

		//$sql1 = "select count(*) from salesentrydet";
        //$sql1 .= " where sprocd='".$pcd."' ";
        //$sql_result1 = mysql_query($sql1) or die("error query sales entry:".mysql_error());
        //$row2 = mysql_fetch_array($sql_result1);
		//$cnt = $row2[0];
		
		// to check the approve product costing, control delete
        //$sqlc = "select stat from procos_appr";
        //$sqlc .= " where pro_code ='$pcd' ";
        //$sql_resultc = mysql_query($sqlc) or die("error query approve table :".mysql_error());
        //$rowc = mysql_fetch_array($sql_resultc);
		//$apstat = $rowc[0];

    	$urlpop = 'upd_procost.php?procd='.$pcd.'&menucd='.$var_menucode;
		$urlvm = 'vm_procost.php?procd='.$pcd.'&menucd='.$var_menucode;		
		$urlprt = 'm_pro_cost.php?p=Print&prod_code='.$pcd.'&menucd='.$var_menucode;
		
		if ($var_accvie == 0){
			$row[] = '<a href="#" title="You Are Not Authorice To View The record">[VIEW]</a>';
		}else{
			$row[] = '<a href="'.$urlvm.'" title="View Detail Costing">[VIEW]</a>';
		}
		
		$row[] = '<a href="'.$urlprt.'" title="Print Product Costing"><img src="../images/b_print.png" border="0" width="16" height="16" hspace="2" alt="Duplicate Product Costing" /></a>';	
		
		if ($var_accupd == 0){	
			$row[] = '<a href="#" title="You Are Not Authorice To Update Product Costing">[EDIT]</a>';
		}else{	
			if ($apstat == 'APPROVE'){
				$row[] = '<a href="#" title="This Product Costing Has Been Approved Not Allowed To Edit">[EDIT]</a>';
			}else{
				$row[] = '<a href="'.$urlpop.'" title="'.$apstat.'">[EDIT]</a>';
			}
		}
		if ($var_accdel == 0 or $cnt <> 0){	
			$row[] = '<input type="checkbox" DISABLED name="procd[]" value="'.$pcd.'" title="You Are Not Authorice To Delete Record"/>';
		}else{
			$row[] = '<input type="checkbox" name="procd[]" value="'.$pcd.'" />';
		}	

		//----------------------------------------------------

		$output['aaData'][] = $row;
		$j = $j + 1;
	}
	
	echo json_encode( $output );
?>