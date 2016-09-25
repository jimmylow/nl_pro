<?php

	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	$var_loginid = $_SESSION['sid'];
    
    if($var_loginid == "") { 
      echo "<script>";   
      echo "alert('Not Log In to the system');"; 
      echo "</script>"; 

      echo "<script>";
      echo 'top.location.href = "../index.php"';
      echo "</script>";
    } else {
    
      $var_stat = $_GET['stat'];
      $var_menucode = $_GET['menucd'];
      $var_plandte = $_GET['pdte'];
      $var_planrmk = $_GET['prmk'];
      $var_planopt = $_GET['popt'];
      include("../Setting/ChqAuth.php");
    }
    
    if ($_POST['Submit'] == "Save") {
    	  $var_plandte   = $_POST['plandte'];
    	  $var_planrmk   = $_POST['planrmk'];
    	  $var_planopt   = $_POST['planopt'];
    	  	
    	  /*----------------------------- Cash Bill details ------------------------------------ */
          $chk_invno_query = mysql_query("select count(*) from `ctrl_sysno` where `descrip` = 'PLANNO'; ", $db_link);
          $chk_invno_res = mysql_fetch_array($chk_invno_query) or die("cant Get Cash Bill No Info".mysql_error());
              
          if ($chk_invno_res[0] > 0 ) {
             $get_invno_query = mysql_query("select noctrl from `ctrl_sysno` where `descrip` = 'PLANNO'", $db_link);
                  
             $get_invno_res = mysql_fetch_object($get_invno_query) or die("Cant Get Cash Bill No ".mysql_error()); 

             $var_invno = vsprintf("%06d",$get_invno_res->noctrl+1); 
             $var_invno = "NLPL-".$var_invno; 
                  
 		     mysql_query("update `ctrl_sysno` set `noctrl` = `noctrl` + 1
                          where `descrip` = 'PLANNO';", $db_link) 
              or die("Cant Update Cash Bill Auto No ".mysql_error());              
               
           }else{ 

		   	mysql_query("insert into `ctrl_sysno` 
                       (`descrip`, `noctrl`)
             values ('PLANNO', 1);",$db_link) or die("Cant Insert Into Cash Bill Auto No");

             $var_invno = "NLPL-000001";
           }  
           /*--------------------------- end Inv no details ---------------------------------- */
            
		   /*--------------------------- Insert Into costing_mat ----------------------------- */	 
           $vartoday = date("Y-m-d"); 
           $sql = " Insert Into costing_mat Values";
		   $sql .= " ('$var_invno', '$var_plandte', '$var_planrmk', '$var_loginid', '$vartoday', '$var_loginid', '$vartoday', '$var_planopt', 'ACTIVE')";
		   mysql_query($sql) or die ("Cant insert : ".mysql_error());
		   /*--------------------------- Insert Into costing_mat ----------------------------- */
		
		   /*--------------------------- Insert Into costing_matord ----------------------------- */
	   	   $sql  = "select distinct sordno, sbuycd, procd, procdcol ";
           $sql .= " from tmpplanpro01";
           $sql .= " Where usernm = '$var_loginid' And planflg = 'Y'";
           $sql_result = mysql_query($sql) or die("Can't query Temp Table : ".mysql_error());             	
           
           $i = 1;
           while ($row = mysql_fetch_assoc($sql_result)){
				$pordno  = mysql_real_escape_string($row['sordno']);
				$probuy  = $row['sbuycd'];
				$sprocd  = $row['procd'];
				$sprocol = $row['procdcol'];
			 						
				$query =  "SELECT pro_buy_desc FROM pro_buy_master "; 
				$query .=" WHERE pro_buy_code='$probuy'";
				$result=mysql_query($query) or die("Cant Get Buyer Description :".mysql_error());
				$row1 = mysql_fetch_array($result);
				$buydesc = $row1['pro_buy_desc'];
					
				$query1 =  "SELECT sorddte FROM salesentry "; 
				$query1 .=" WHERE sordno ='$pordno' and sbuycd = '$probuy'";
				$result1=mysql_query($query1) or die("Cant Get Order Date :".mysql_error());
				$row2 = mysql_fetch_array($result1);
				$porddte = $row2[0];

				if ($pordno <> ""){
					$sql = "INSERT INTO costing_matord values 
					   		('$var_invno', '$pordno', '$probuy', '$buydesc', '$porddte', '$i', '$var_planopt', '$sprocd', '$sprocol')";
					mysql_query($sql) or die ("Cant insert : ".mysql_error());
           		}	
           		$i = $i + 1;
			}
			/*--------------------------- Insert Into costing_matord ----------------------------- */
			
			/*--------------------------- Insert Into costing_matdet ----------------------------- */
			if ($var_invno != ""){	
  				$sql  = "select * ";
           		$sql .= " from tmpplancol";
           		$sql .= " Where usernm = '$var_loginid' And planflg = 'Y'";
           		$sql_result = mysql_query($sql) or die("Can't query Temp Table : ".mysql_error());     					
           		
           		$i = 1;
           		while ($row = mysql_fetch_assoc($sql_result)){
						
					$mabuycd   = $row['buycd'];
					$maordno   = $row['orderno'];
					$maprocd   = $row['procd'];
					$maitm     = stripslashes(mysql_real_escape_string($row['rm_code']));
					$maitmdesc = mysql_real_escape_string($row['rm_cddesc']);
					$maitmuom  = $row['rm_uom'];
					$maucomps  = $row['rm_comps'];
					$maordqty  = $row['ordqty'];
					$masumcomp = $row['sumcomps'];
					$maitmseq  = $row['rmseqno'];
					$maprcol   = $row['procdcol'];
					if ($maucomps == ""){ $maucomps = 0;}
					if ($maordqty == ""){ $maordqty = 0;}
					if ($masumcomp == ""){ $masumcomp = 0;}
													
					$sql  = "INSERT INTO costing_matdet ";
					$sql .= " (costingno, buycd, ordno, prodcd, rm_code, rm_desc, rm_uom, rm_ucoms, ";
					$sql .= "  sordqty, sum_comp, seqno, prod_colcd)";
					$sql .= " values ";
					$sql .=	" ('$var_invno', '$mabuycd', '$maordno', '$maprocd','$maitm','$maitmdesc','$maitmuom','$maucomps', ";
					$sql .= "  '$maordqty', '$masumcomp', '$maitmseq', '$maprcol')";		
					mysql_query($sql) or die("Can't Insert Into Detail Table :".mysql_error());
				}
			}
			/*--------------------------- Insert Into costing_matdet ----------------------------- */
			
			
			/*--------------------------- Insert Into costing_purbook ----------------------------- */
			if ($var_invno != ""){	
  				if(!empty($_POST['planmat']) && is_array($_POST['planmat'])) 
				{	
					foreach($_POST['planmat'] as $row=>$matcd ) {
						$matcode    = $matcd;
						$matseqno   = $_POST['seqno'][$row];
						$matdesc    = mysql_real_escape_string($_POST['plandes'][$row]);
						$matuom     = $_POST['planuom'][$row];
						$matcoucost = $_POST['plantco'][$row];
						$matpur     = $_POST['planpur'][$row];
						$matbok     = $_POST['planbok'][$row];
					
						if ($matcode <> "")
						{
							if ($matcoucost == ""){ $matcoucost = 0;}
							if ($matpur  == ""){ $matpur  = 0;}
							if ($matbok == ""){ $matbok = 0;}
								
							$sql = "INSERT INTO costing_purbook (costing_no, itmcode, consump, itmdesc, itmuom, plpurqty, plbkqty) values 
						    		('$var_invno', '$matcode', '$matcoucost', '$matdesc','$matuom','$matpur','$matbok')";		
							mysql_query($sql) or die("Query 2 :".mysql_error());;
           				}	
					}
				}

			}
			/*--------------------------- Insert Into costing_matdet ----------------------------- */
		
			echo "<script>";   
      	    echo "alert('Costing No :".$var_invno."');"; 
      		echo "</script>";	 
			
			/*-------------------------- Continus With Auto Book Item ------------------------------*/
            /* $sql = "Select count(*) from costing_purbook where plbkqty <> 0 and costing_no = '$var_invno'";
        	 $sql_result = mysql_query($sql);
        	 $row = mysql_fetch_array($sql_result);
        	 $cntbook = $row[0];
             if ($cntbook == "" or empty($cntbook)){$cntbook = 0;}     
			
             if ($cntbook != 0){	
				echo "<script>"; 
				echo "if(confirm('Autobook With Selected Book Item?'))";
				echo "{";
						#------------Getting Booking No-------------------------------
						$chk_invno_query = mysql_query("select count(*) from `ctrl_sysno` where `descrip` = 'BOOKNO'; ", $db_link);
              			$chk_invno_res = mysql_fetch_array($chk_invno_query) or die("Cant Get Book No Info".mysql_error());
              
              			if ($chk_invno_res[0] > 0 ) {
                  			$get_invno_query = mysql_query("select noctrl from `ctrl_sysno` where `descrip` = 'BOOKNO'", $db_link);                 
                  			$get_invno_res = mysql_fetch_object($get_invno_query) or die("Cant Get Book No ".mysql_error()); 
                  			$var_invno = vsprintf("%06d",$get_invno_res->noctrl+1); 
                  			$var_invno = "BK".$var_invno; 
                  
 		  		  			mysql_query("update `ctrl_sysno` set `noctrl` = `noctrl` + 1 
 		  		  							where `descrip` = 'BOOKNO';", $db_link) 
                           	or die("Cant Update Book No Auto No ".mysql_error());              
                		}else{ 
				  			$sql  = "insert into ctrl_sysno (descrip, noctrl) values ";
				  			$sql .= "	('BOOKNO', 1)";
		   		  			mysql_query($sql, $db_link) or die("Cant Insert Into Book No Auto No".mysql_error());
                  			$var_invno = "BK000001";
                		}  
						#-------------------------------------------------------------
						
						$vartoday = date("Y-m-d");
						$sql  = "INSERT INTO booktab01 (bookno, bookdte, booktyp, byrefno, create_by, create_on, ";
						$sql .= "                       modified_by, modified_on, buycd) values ";
						$sql .=	" ('$var_invno', '$vartoday', 'A', '$vmbkord','$var_loginid','$vartoday', "; 
						$sql .= "  '$var_loginid','$vartoday', '$vmbkbuy')";
						mysql_query($sql) or die("Query 1 Booking Table ;".mysql_error());
			
			if(!empty($_POST['bkitmcd']) && is_array($_POST['bkitmcd'])) 
			{	
				foreach($_POST['bkitmcd'] as $row=>$matcd){
					$bkitmcode = mysql_real_escape_string($matcd);
					$seqno     = $_POST['seqno'][$row];
					$bkitmdesc = mysql_real_escape_string($_POST['bkitmdesc'][$row]);
					$bkitmavai = $_POST['itmavai'][$row];
					$bkitmuom  = mysql_real_escape_string($_POST['itmuom'][$row]);
					$bkitmqty  = $_POST['bkqty'][$row];
					
					if ($bkitmcode <> "")
					{
						if ($bkitmavai == ""){ $bkitmavai = 0;}
						if ($bkitmqty == ""){ $bkitmqty = 0;}
						$sql = "INSERT INTO booktab02 values 
						       ('$var_invno', '$bkitmcode', '$bkitmdesc', '$bkitmavai','$bkitmuom',
						        '$bkitmqty', 'N', '0', '0')";
						mysql_query($sql) or die("Query 2 :".mysql_error());	
					}
				}
		    }
		    echo "<script>";   
      		echo "alert('Booking No :'".$var_invno.");"; 
      		echo "</script>";

				
				
				echo "}";
				echo "</script>";
			  }		
			/*-------------------------- Continus With Auto Book Item ------------------------------*/
		
			
			$backloc = "../bom_tran/m_mat_plan.php?menucd=".$var_menucode;
           	echo "<script>";
           	echo 'location.replace("'.$backloc.'")';
            echo "</script>"; 	
    }    
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">


<style media="all" type="text/css">
@import "../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css";
@import "../css/styles.css";

.style2 {
	margin-right: 0px;
}
</style>

<!-- jQuery libs -->
<script type="text/javascript" src="../js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>

<!-- Our jQuery Script to make everything work -->
<script  type="text/javascript" src="plan_itmdet.js"></script>


<script type="text/javascript"> 

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}


function getXMLHTTP() { //fuction to return the xml http object
		var xmlhttp=false;	
		try{
			xmlhttp=new XMLHttpRequest();
		}
		catch(e)	{		
			try{			
				xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e){
				try{
				xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
				}
				catch(e1){
					xmlhttp=false;
				}
			}
		}		 	
		return xmlhttp;
}

function validateForm()
{

    //Check the list of mat item no got duplicate item no------------------------------------------------
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
	var mylist = new Array();
	var mylist2 = new Array();	    

	for (var j = 1; j < rowCount; j++){

	    var idrowItem = "planmat"+j;
	    var idrowItem2 = "plandes"+j;
        var rowItem = document.getElementById(idrowItem).value;
        var rowItem2 = document.getElementById(idrowItem2).value;	 	 
        if (rowItem != ""){ 
        	mylist.push(rowItem);   
	    }
	    if (rowItem2 != ""){ 
        	mylist2.push(rowItem2);   
	    }		
    }		
	
	mylist.sort();
	mylist2.sort();
	var last = mylist[0];
	var last2 = mylist2[0];
	
	for (var i=1; i < mylist.length; i++) {
		if (mylist[i] == last){
		  	if(mylist2[i] == last2){ 
				alert ("Duplicate Item Found; " + last);
				return false;
		 	}			 
		}	
		last = mylist[i];
		last2 = mylist2[i];
	}
	//--------------------------------------------------------------------------------------------------- 
	
	//Check input purchased quantity is Valid-------------------------------------------------------
	  var table = document.getElementById('itemsTable');
	  var rowCount = table.rows.length;  
	
	  for (var j = 1; j < rowCount; j++){

	    var idrowbook = "planpur"+j;
        var rowItemc = document.getElementById(idrowbook).value;	 
        
        if (rowItemc != ""){ 
        	if(isNaN(rowItemc)) {
    	   		alert('Please Enter a valid number for Purchased Quantity :' + rowItemc + " line No :"+j);
    	   		document.InpMDETMas.idrowbook.focus();
    	        return false;
    	    }    
    	}
       }		
    //---------------------------------------------------------------------------------------------------
  
	
	//Check input booking quantity is Valid-------------------------------------------------------
	  var table = document.getElementById('itemsTable');
	  var rowCount = table.rows.length;  
	
	  for (var j = 1; j < rowCount; j++){

	    var idrowbook = "planbok"+j;
        var rowItemc = document.getElementById(idrowbook).value;	 
        
        if (rowItemc != ""){ 
        	if(isNaN(rowItemc)) {
    	   		alert('Please Enter a valid number for Booking Quantity :' + rowItemc + " line No :"+j);
    	        return false;
    	    }    
    	}
       }		
    //---------------------------------------------------------------------------------------------------
    
    //Checking Booking Quantity Got Larger Then Availabel Quantity/////////////////////
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;     

	for (var j = 1; j < rowCount; j++){

	    var idbookqty  = "planbok"+j;
	    var idavailqty = "plitmava"+j;
	    
        var vbkq = document.getElementById(idbookqty).value;	
        var vavq = document.getElementById(idavailqty).value; 
        
        if (parseFloat(vbkq) > parseFloat(vavq)){ 
        	alert ("Booking Quantity Must Not Exceed Available Quantity; Row "+ j);
        	document.getElementById(idbookqty).focus();
			return false;
   
	    }		
    }			
	///////////////////////////////////////////////////////////////////////////////////
}

function chkdecvalb(valdecbk, valid)
{
	var bkqtyid = "planbok"+valid;
	var avqtyid = "plitmava"+valid;
	
	var qtyav = document.getElementById(avqtyid).value;

	if (valdecbk != ""){
		if(isNaN(valdecbk)) {
    	   alert('Please Enter a valid number for Booking Quantity:' + valdecbk);
    	   document.getElementById(bkqtyid).focus();
    	   valdecbk = 0;
    	}
    	document.getElementById(bkqtyid).value = parseFloat(valdecbk).toFixed(2);
    }else{
    	valdecbk = 0;
    	document.getElementById(bkqtyid).value = parseFloat(valdecbk).toFixed(2);
    }
    if (parseFloat(valdecbk) < 0){	
    	alert('Booking Qunatity Cannot Negative Value:' + valdecbk);
		document.getElementById(bkqtyid).focus();
    }
    if (parseFloat(valdecbk) > parseFloat(qtyav)){
    	alert('Booking Qunatity Cannot More Than Available Quantity');
		document.getElementById(bkqtyid).focus();
	}
}

function chkdecvalp(valdecbk, valid)
{
	var purqtyid = "planpur"+valid;
	var itemid   = "planmat"+valid;
	
	var qtyav = document.getElementById(purqtyid).value;

	if (valdecbk != ""){
		if(isNaN(valdecbk)) {
    	   alert('Please Enter a valid number for Purchase Quantity:' + valdecbk);
    	   document.getElementById(purqtyid).focus();
    	   valdecbk = 0;
    	}
    	document.getElementById(purqtyid).value = parseFloat(valdecbk).toFixed(2);
    }else{
    	valdecbk = 0;
    	document.getElementById(purqtyid).value = parseFloat(valdecbk).toFixed(2);
    }
    if (parseFloat(valdecbk) < 0){	
    	alert('Purchase Qunatity Cannot Negative Value:' + valdecbk);
		document.getElementById(purqtyid).focus();
    }
}

</script>
</head>
<?php

   if($var_planopt == "P"){
   		$poptdesc = "By Product Code";
   }else{
   		$poptdesc = "By Color";
   }
   
   $orddesc = "";
   $sql  = "select distinct sordno ";
   $sql .= " from tmpplanpro01";
   $sql .= " Where usernm = '$var_loginid'";
   $sql_result = mysql_query($sql) or die("Can't query Sales Table : ".mysql_error());             	
   while ($row = mysql_fetch_assoc($sql_result)){
		$ordno  = mysql_real_escape_string($row['sordno']);  					
		$pordno = $ordno;
		if ($pordno != ""){
			if ($orddesc == ""){
				$orddesc = $pordno;
			}else{	
				$orddesc = $orddesc.", ".$pordno;	
   			}
   		}
   }
?>
 
<body onload="document.InpMDETMas.planpur1.focus();">
   <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>-->

  <div class ="contentc">
 
	<fieldset name="Group1" style=" width: 912px;" class="style2">
	 <legend class="title">PLANNING DETAIL - BY PRODUCT CODE (4)<?php echo  $var_costinno; ?></legend>
	  <br>	 
	 
	  <form name="InpMDETMas" method="POST" action="<?php echo htmlentities($_SERVER['PHP_SELF'].'?menucd='.$var_menucode); ?>" onsubmit="return validateForm()" style="width: 917px">
		
		<input name="plandte" type="hidden" value="<?php echo $var_plandte;?>">
		<input name="planrmk" type="hidden" value="<?php echo $var_planrmk;?>">
		<input name="planopt" type="hidden" value="<?php echo $var_planopt;?>">
		
		<table>
			<tr>
				<td></td>
				<td>Planning Option</td>
				<td>:</td>
				<td><?php echo $poptdesc; ?></td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td></td>
				<td>Sales Order No </td>
				<td>:</td>
				<td><?php echo $orddesc; ?></td>
			</tr>
			<tr>
				<td></td>
				<td>Remark</td>
				<td>:</td>
				<td><?php echo $var_planrmk;?></td>
			</tr>
	    </table>
	    <br>
		
		  <table id="itemsTable" class="general-table" style="width: 100%">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">Raw Material</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Consumption</th>
              <th class="tabheader">Available</th> 
              <th class="tabheader">Plan <br>Purchase Qty</th>
              <th class="tabheader">Plan<br>Booking Qty</th> 
             </tr>
            </thead>
            <tbody>
            
            <?php
          		$var_sql = " SELECT count(*) as cnt from costing_matdet";
	      		$var_sql .= " Where costingno = '$var_costinno'";
	      		$query_id = mysql_query($var_sql) or die ("Cant Check Product Job Code");
	      		$res_id = mysql_fetch_object($query_id);
             
	      		if ($res_id->cnt > 0 ){
	      			
	      			$sql = "SELECT * FROM costing_matdet";
             		$sql .= " Where costingno='".$var_costinno."'"; 
	    			$sql .= " ORDER BY seqno";  
					$rs_result = mysql_query($sql); 
			   
			        $i = 1;
					while ($rowq = mysql_fetch_assoc($rs_result)){
	
	      			   echo '<tr class="item-row">';
             		   echo '<td><input name="seqno[]" id="seqno" value="'.$rowq['seqno'].'" readonly="readonly" style="width: 15px; border:0;""></td>';
             		   echo '<td><input name="planmat[]" value="'.$rowq['rm_code'].'" id="planmat'.$i.'" class="autosearch" style="width: 100px" onchange ="upperCase(this.id)" onblur="get_desc(this.value, '.$i.')"></td>';
             		   echo '<td><input name="plandes[]" value="'.htmlentities($rowq['rm_desc']).'" id="plandes'.$i.'" readonly="readonly" style="border: 0; width: 230px;"></td>';
             		   echo '<td><input name="planuom[]" value="'.$rowq['rm_uom'].'" id="planuom'.$i.'" readonly="readonly" style="border: 0; width: 40px;"></td>';
             		   echo '<td><input name="plantco[]"  value="'.$rowq['sum_comp'].'" id="plantco'.$i.'" align="right" style="width: 90px;"></td>';
             	       echo '<td><input name="plitmava[]"  value="'.$avail.'" readonly="readonly" id="plitmava'.$i.'" align="right" style="width: 90px;border:0;"></td>';
             	       echo '<td align="center"><input name="planpur[]" type="checkbox" id="planpur'.$i.'" value="'.$rowq['rm_code'].'" style="width: 90px; border:0;"></td>';
             	       echo '<td><input name="planbok[]" id="planbok'.$i.'" style="width: 90px; text-align:right;" onblur="chkdecvalb(this.value, '.$i.')"></td>';
             	       echo '</tr>';
             		   $i = $i + 1;
					}

                }else{
                
                	$sql1  = "SELECT rm_code, rm_cddesc, rm_uom, sum(sumcomps) ";
                	$sql1 .= " FROM tmpplancol";
                	$sql1 .= " where usernm = '$var_loginid' and planflg = 'Y'";
                	$sql1 .= " group by 1, 2, 3 ";
                	$sql1 .= " order by rmseqno";
              		$rs_result1 = mysql_query($sql1) or die("Mysql Error =".mysql_error()); 
			  
			  		$i = 1;
			  		$pcontot = 0;    	
			  		while ($row1 = mysql_fetch_assoc($rs_result1)){
						$sumconp = number_format($row1['sum(sumcomps)'], 4, '.', ''); 
						$rmcode  = htmlentities($row1['rm_code']);
						$rmdesc  = htmlentities($row1['rm_cddesc']);
						$rmuom   = $row1['rm_uom'];
				
						#----------------------Available Qty For RM CODE-------------------
						$sqlonh  = "select sum(totalqty) from rawmat_tran ";
        				$sqlonh .= " where item_code ='$rmcode'";
        				$sqlonh .= " and tran_type in ('REC', 'ADJ', 'RTN', 'ISS', 'OPB', 'REJ')";
        				$sql_resultonh = mysql_query($sqlonh);
        				$rowonh = mysql_fetch_array($sql_resultonh);        
        				if ($rowonh[0] == "" or $rowonh[0] == null){ $rowonh[0]  = 0.00;}
        				$onhnd = $rowonh[0];
        
        				$sqlbk  = "select sum(bookqty-sumrelqty) from booktab02 ";
        				$sqlbk .= " where bookitm ='$rmcode'";
        				$sqlbk .= " and compflg = 'N'";
        				$sql_resultbk = mysql_query($sqlbk);
        				$rowbk = mysql_fetch_array($sql_resultbk);        
        				if ($rowbk[0] == "" or $rowbk[0] == null){ $rowbk[0]  = 0.00;}
        				$currbk = $rowbk[0];
        				$avail = number_format(($onhnd - $currbk),"2",".","");
        				#----------------------Available Qty For RM CODE-------------------

						echo '<tr class="item-row">';
             			echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 15px; border:0;"></td>';
             			echo '<td><input name="planmat[]"  value="'.$rmcode.'" readonly="readonly" id="planmat'.$i.'" style="width: 100px"></td>';
             			echo '<td><input name="plandes[]"  value="'.$rmdesc.'" id="plandes'.$i.'" readonly="readonly" style="border: 0; width: 230px;"></td>';
             			echo '<td><input name="planuom[]"  value="'.$rmuom.'" id="planuom'.$i.'" readonly="readonly" style="border: 0; width: 40px;"></td>';
             			echo '<td><input name="plantco[]"  value="'.$sumconp.'" readonly="readonly" id="plantco'.$i.'" style="width: 90px; text-align:right;" onBlur="calccomps('.$i.');"></td>';
             			echo '<td><input name="plitmava[]" value="'.$avail.'" readonly="readonly" id="plitmava'.$i.'" style="width: 90px;border:0;text-align:right;"></td>';
             			echo '<td><input name="planpur[]"  value="'.$puritm.'" id="planpur'.$i.'" style="width: 90px; text-align:right;" onblur="chkdecvalp(this.value, '.$i.')"></td>';
             			echo '<td><input name="planbok[]"  value="'.$bukitm.'" id="planbok'.$i.'" style="width: 90px; text-align:right;" onblur="chkdecvalb(this.value, '.$i.')"></td>';
             			echo '</tr>';
             			$pcontot = $pcontot + $sumconp;
             			$i = $i + 1;						
	 		 		}				
       		  	}
         ?>
         </tbody>
         
        </table>
         <table style="width: 921px">
         	<tr>
         		<td align="left" style="text-align:right; width: 459px;">Total</td>
         		<td>
				<input readonly="readonly" class="textnoentry1" type="text" id="totcompid" value="<?php echo number_format($pcontot, 4, ".",",");?>" style="text-align:right; width: 90px;"></td>
         	</tr>
         </table>
         <br><br>
	   <table>
	  	<tr>
			<td style="width: 1165px; height: 22px;" align="center">
			<?php
				 $locatr = "m_mat_plan.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
					include("../Setting/btnsave.php");
				?>
				</td>
			</tr>
	  </table>
   </form>	
  </fieldset>
  </div>
  <div class="spacer"></div>
</body>
</html>
