<?php
	
    include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	
	$var_loginid = $_SESSION['sid'];
	set_time_limit(1800);
    
    if($var_loginid == "") { 
      echo "<script>";   
      echo "alert('Not Log In to the system');"; 
      echo "</script>"; 

      echo "<script>";
      echo 'top.location.href = "../index.php"';
      echo "</script>";
    } else {
      $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
     
     	$frcat  = $_POST['selfcat'];
     	$tocat  = $_POST['seltcat'];
     	$catall = $_POST['chkallcat'];
     	$frdte  = date("Y-m-d", strtotime($_POST['rptofdte']));
     	$todte  = date("Y-m-d", strtotime($_POST['rptotdte']));
     	$rpttyp = $_POST['rptype'];
     	
     	if ($frdte <> "" and $todte <> ""){
     	
     		#----------------Prepare Temp Table For Printing -----------------------------------
     		$sql  = " Delete From tmpitmmove_rpt where usernm = '$var_loginid'";
        	mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
        	
        	if ($catall == '1'){
        		$sql = "SELECT rm_code, main_code, description, active_flag ";
				$sql .= " FROM rawmat_subcode";
    			$sql .= " where active_flag = 'ACTIVE'";
    			$sql .= " Order BY main_code, rm_code";
    		}else{
    			$sql = "SELECT y.rm_code, y.main_code, y.description, y.active_flag ";
				$sql .= " FROM rawmat_master x, rawmat_subcode y";
    			$sql .= " where y.active_flag = 'ACTIVE'";
    			$sql .= " and   x.rm_code = y.main_code";
    			$sql .= " and   x.category between '$frcat' and '$tocat'";
    			$sql .= " Order BY y.main_code, y.rm_code";
    		}	  
			$rs_result = mysql_query($sql);

			while ($row = mysql_fetch_assoc($rs_result)) { 
		    	$itmcd  = mysql_real_escape_string($row['rm_code']);
		    	$mitmcd = mysql_real_escape_string($row['main_code']);
		    	$itmdes = mysql_real_escape_string($row['description']);

				$sqlm  = "select category, uom, itm_grp_cd";
		    	$sqlm .= " from rawmat_master";
        		$sqlm .= " where rm_code ='$mitmcd'";
        		$sql_resultm = mysql_query($sqlm);
        		$rowm = mysql_fetch_array($sql_resultm);
        		$catcd  = $rowm['category'];
        		$itmuom = $rowm['uom'];
        		$itmgrd = $rowm['itm_grp_cd'];
        	
        		$sqlc  = "select cat_desc";
		    	$sqlc .= " from cat_master";
        		$sqlc .= " where cat_code ='$catcd'";
        		$sql_resultc = mysql_query($sqlc);
        		$rowc = mysql_fetch_array($sql_resultc);
        		$catdes = $rowc['cat_desc'];
        		
        		$sqlc  = "select itm_grp_desc";
		    	$sqlc .= " from item_group_master";
        		$sqlc .= " where itm_grp_cd ='$itmgrd'";
        		$sql_resultc = mysql_query($sqlc);
        		$rowc = mysql_fetch_array($sql_resultc);
        		$itmgrddesc = $rowc['itm_grp_desc'];
        		
        		#------------OnHand QTy From Date-----------------------------			
				$sqlo = "select sum(totalqty) from rawmat_tran ";
        		$sqlo .= " where item_code ='$itmcd' ";
        		$sqlo .= " and tran_type in ('REC', 'ADJ', 'RTN', 'ISS', 'OPB', 'REJ')";
        		$sqlo .= " and grndate < '$frdte'";
        		$sql_resulto = mysql_query($sqlo);
        		$rowo = mysql_fetch_array($sql_resulto);        
        		if ($rowo[0] == "" or $rowo[0] == null){ 
        		  $rowo[0]  = 0.00;
        		}
        		$onhandbal = $rowo[0];
        		#-------------------------------------------------------------
        		
        		#------------GRN QTy From Date To Date-----------------------------			
				$sqlg = "select sum(totalqty) from rawmat_tran ";
        		$sqlg .= " where item_code ='$itmcd' ";
        		$sqlg .= " and tran_type in ('REC')";
        		$sqlg .= " and grndate between '$frdte' and '$todte'";
        		$sql_resultg = mysql_query($sqlg);
        		$rowg = mysql_fetch_array($sql_resultg);        
        		if ($rowg[0] == "" or $rowg[0] == null){ 
        		  $rowg[0]  = 0.00;
        		}
        		$recbal = $rowg[0];
        		#-------------------------------------------------------------

				#------------Issue QTy From Date To Date-----------------------------			
				$sqli = "select sum(totalqty) from rawmat_tran ";
        		$sqli .= " where item_code ='$itmcd' ";
        		$sqli .= " and tran_type in ('ISS')";
        		$sqli .= " and grndate between '$frdte' and '$todte'";
        		$sql_resulti = mysql_query($sqli);
        		$rowi = mysql_fetch_array($sql_resulti);        
        		if ($rowi[0] == "" or $rowi[0] == null){ 
        		  $rowi[0]  = 0.00;
        		}
        		$issbal = $rowi[0];
        		#-------------------------------------------------------------

				#------------Adj QTy From Date To Date-----------------------------
				$sqla = " select sum(y.adjqty)";
      			$sqla .= " from rawmat_adj x, rawmat_adj_tran y";
      			$sqla .= " where x.adjdate between '$frdte' and '$todte'";
      			$sqla .= " and  x.rm_adj_id = y.rm_adj_id";
      			$sqla .= " and  y.item_code = '$itmcd'";
			
				//$sqla = "select sum(totalqty) from rawmat_tran ";
        		//$sqla .= " where item_code ='$itmcd' ";
        		//$sqla .= " and tran_type in ('ADJ')";
        		//$sqla .= " and grndate between '$frdte' and '$todte'";
        		$sql_resulta = mysql_query($sqla) or die(mysql_error());
        		$rowa = mysql_fetch_array($sql_resulta);        
        		if ($rowa[0] == "" or $rowa[0] == null){ 
        		  $rowa[0]  = 0.00;
        		}
        		$adjbal = $rowa[0];
        		#-------------------------------------------------------------

				#------------Return QTy From Date To Date-----------------------------			
				$sqlr = "select sum(totalqty) from rawmat_tran ";
        		$sqlr .= " where item_code ='$itmcd' ";
        		$sqlr .= " and tran_type in ('RTN')";
        		$sqlr .= " and grndate between '$frdte' and '$todte'";
        		$sql_resultr = mysql_query($sqlr);
        		$rowr = mysql_fetch_array($sql_resultr);        
        		if ($rowr[0] == "" or $rowr[0] == null){ 
        		  $rowr[0]  = 0.00;
        		}
        		$rtnbal = $rowr[0];
        		#-------------------------------------------------------------
        		
        		#------------Reject QTy From Date To Date-----------------------------			
				$sqlj = "select sum(totalqty) from rawmat_tran ";
        		$sqlj .= " where item_code ='$itmcd' ";
        		$sqlj .= " and tran_type in ('REJ')";
        		$sqlj .= " and grndate between '$frdte' and '$todte'";
        		$sql_resultj = mysql_query($sqlj);
        		$rowj = mysql_fetch_array($sql_resultj);        
        		if ($rowj[0] == "" or $rowj[0] == null){ 
        		  $rowj[0]  = 0.00;
        		}
        		$rejbal = $rowj[0];
        		#-------------------------------------------------------------
				
				
				#------------Reject QTy From Date To Date-----------------------------			
				$sqlop = "select sum(totalqty) from rawmat_tran ";
        		$sqlop .= " where item_code ='$itmcd' ";
        		$sqlop .= " and tran_type in ('OPB')";
        		$sqlop .= " and grndate between '$frdte' and '$todte'";
        		$sql_resultop = mysql_query($sqlop);
        		$rowop = mysql_fetch_array($sql_resultop);        
        		if ($rowop[0] == "" or $rowop[0] == null){ 
        		  $rowop[0]  = 0.00;
        		}
        		$opbbal = $rowop[0];
        		#-------------------------------------------------------------
        		
        		$cloqty = $onhandbal + $opbbal + $recbal + $issbal + $rtnbal + $adjbal + $rejbal;

				if ($onhandbal <> 0 or $recbal <> 0 or $issbal <> 0  or
				    $adjbal <> 0 or $rtnbal <> 0 or $rejbal <> 0 or $opbbal <> 0){
				
					///opening average cost-------------------------------------------------	
					$sqlv  = "select sum(y.totalqty * y.myr_unit_cost) / sum(y.totalqty)";
        			$sqlv .= " from rawmat_receive x, rawmat_receive_tran y";
        			$sqlv .= " where y.item_code ='$itmcd'";
					$sqlv .= " and   x.grndate < '$frdte'";
					$sqlv .= " and   x.rm_receive_id = y.rm_receive_id";
					$sql_resultv = mysql_query($sqlv);
        			$rowv = mysql_fetch_array($sql_resultv);
        			if ($rowv[0] == "" or $rowv[0] == null){ 
        			  $rowv[0]  = 0.00;
        			}
        			$oavgcst = $rowv[0];
					
					if ($oavgcst == 0){
						$sqlv1  = "select cost_price";
						$sqlv1 .= " from rawmat_subcode";
						$sqlv1 .= " where rm_code ='$itmcd'";
						$sql_resultv1 = mysql_query($sqlv1);
						$rowv1 = mysql_fetch_array($sql_resultv1);
						if ($rowv1[0] == "" or $rowv1[0] == null){ 
							$rowv1[0]  = 0.00;
						}
						$oavgcst = $rowv1[0];
					}
					//-------------------------------------------------------------------------

		            ///closing average cost-------------------------------------------------	
					$sqlv  = "select sum(y.totalqty * y.myr_unit_cost) / sum(y.totalqty)";
        			$sqlv .= " from rawmat_receive x, rawmat_receive_tran y";
        			$sqlv .= " where y.item_code ='$itmcd'";
					$sqlv .= " and   x.grndate <= '$todte'";
					$sqlv .= " and   x.rm_receive_id = y.rm_receive_id";
					$sql_resultv = mysql_query($sqlv);
        			$rowv = mysql_fetch_array($sql_resultv);
        			if ($rowv[0] == "" or $rowv[0] == null){ 
        			  $rowv[0]  = 0.00;
        			}
        			$avgcst = $rowv[0];
					
					if ($avgcst == 0){
						$sqlv1  = "select cost_price";
						$sqlv1 .= " from rawmat_subcode";
						$sqlv1 .= " where rm_code ='$itmcd'";
						$sql_resultv1 = mysql_query($sqlv1);
						$rowv1 = mysql_fetch_array($sql_resultv1);
						if ($rowv1[0] == "" or $rowv1[0] == null){ 
							$rowv1[0]  = 0.00;
						}
						$avgcst = $rowv1[0];
					}
					//-------------------------------------------------------------------------
        			
        			$obalamt = $oavgcst * $onhandbal;
        			$cbalamt = $avgcst * $cloqty;
					$sqliq  = " Insert Into tmpitmmove_rpt (subcode, description, cat, cat_desc, ";
        			$sqliq .= "  uom, openqty, openavgcst, openamt, recqty, issqty, adjqty, rtnqty, ";
        			$sqliq .= "  rejqty, cloqty, cloavgcst, cloamt, usernm, opncqty, itmgrp, itmgrpdesc)";
        			$sqliq .= " Values ('$itmcd', '$itmdes', '$catcd', '$catdes', '$itmuom',";
        			$sqliq .= "   '$onhandbal', '$oavgcst', '$obalamt', '$recbal', '$issbal', '$adjbal',";
        			$sqliq .= "   '$rtnbal', '$rejbal', '$cloqty', '$avgcst', '$cbalamt','$var_loginid', '$opbbal', ";
        			$sqliq .= "   '$itmgrd', '$itmgrddesc')";
        			mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
				}
     		}
     		#-----------------------------------------------------------------------------------
			// Redirect browser
			if ($rpttyp == "D"){
        		$fname = "stk_movement_rpt.rptdesign&__title=myReport"; 
        		$dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&fd=".$frdte."&td=".$todte."&usernm=".$var_loginid."&dbsel=".$varrpturldb;
        		$dest .= urlencode(realpath($fname));
        	}else{
        		$fname = "stk_movement_surpt.rptdesign&__title=myReport"; 
        		$dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&fd=".$frdte."&td=".$todte."&usernm=".$var_loginid."&dbsel=".$varrpturldb;
        		$dest .= urlencode(realpath($fname));
        	}	

        	//header("Location: $dest" );
        	echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        }
     }
    } 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

	
<style media="all" type="text/css">
@import "../css/styles.css";
@import "../css/demo_table.css";

.style2 {
	margin-right: 8px;
}
</style>
<script type="text/javascript" src="../js/datetimepicker_css.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.nightly.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>
<script type="text/javascript" src="../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../js/Parsers.js"></script>
<script type="text/javascript" src="../js/InputMask.js"></script>

<script type="text/javascript" charset="utf-8"> 

function setup() {

	document.InpRawOpen.selfcat.focus();
						
 	//Set up the date parsers
    var dateParser = new DateParser("dd-MM-yyyy");
      
	//Set up the DateMasks
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var dateMask1 = new DateMask("dd-MM-yyyy", "rptofdte");
	dateMask1.validationMessage = errorMessage;		
		
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var dateMask1 = new DateMask("dd-MM-yyyy", "rptotdte");
	dateMask1.validationMessage = errorMessage;	
}

function enabDis(idchk)
{
	var txtchk = document.InpRawOpen.chkallcat;
	var x      = document.getElementById("selfcat");
	var y      = document.getElementById("seltcat");

	if (txtchk.checked){
		document.getElementById("selfcat").disabled=true;
		document.getElementById("seltcat").disabled=true;
	}else{
		document.getElementById("selfcat").disabled=false;
		document.getElementById("seltcat").disabled=false;
	}
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

function chkSubmit()
{
	var txtchk = document.InpRawOpen.chkallcat;

	if (!txtchk.checked){
		var x=document.forms["InpRawOpen"]["selfcat"].value;
		if (x==null || x=="")
		{
			alert("From Category Code Cannot Be Blank");
			document.InpRawOpen.selfcat.focus();
			return false;
		}
		
		var x=document.forms["InpRawOpen"]["seltcat"].value;
		if (x==null || x=="")
		{
			alert("To Category Code Cannot Be Blank");
			document.InpRawOpen.seltcat.focus();
			return false;
		}
	}

	var x=document.forms["InpRawOpen"]["rptofdte"].value;
	if (x==null || x=="")
	{
		alert("Opening From Date Must Not Be Blank");
		document.InpRawOpen.rptofdte.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["rptotdte"].value;
	if (x==null || x=="")
	{
		alert("Opening To Date Must Not Be Blank");
		document.InpRawOpen.rptotdte.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["rptofdte"].value;
	var y=document.forms["InpRawOpen"]["rptotdte"].value;
	
    var fromdate = x.split('-');
        from_date = new Date();
        from_date.setFullYear(fromdate[2],fromdate[1]-1,fromdate[0]); 
    
    var todate = y.split('-');
        to_date = new Date();
        to_date.setFullYear(todate[2],todate[1]-1,todate[0]);
    if (from_date > to_date ) 
    {
        alert("Opening To Date Must Larger Then From Date");
		document.InpRawOpen.rptofdte.focus();
		return false;
    }
}	
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
	<div class="contentc">
	<fieldset name="Group1" style=" width: 627px; height: 260px;" class="style2">
	 <legend class="title">STOCK MOVEMENT REPORT</legend>
	  <br>
	  <form name="InpRawOpen" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 619px;">
		<table>
		  <tr>
		  	<td></td>
		  	<td style="width: 140px">From Stock Category</td>
		  	<td>:</td>
		  	<td colspan="5">
		  		<select name="selfcat" id ="selfcat" style="width: 278px">
			    <?php
                   $sql = "select cat_code, cat_desc from cat_master ORDER BY cat_code";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['cat_code'].'">'.$row['cat_code']." | ".$row['cat_desc'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>
		  	</td>
		  </tr>
		  <tr><td></td></tr>
		  <tr> 
		    <td></td>
		  	<td style="width: 140px">To Stock Category</td>
		  	<td>:</td>
		  	<td colspan="5">
		  		<select name="seltcat" id ="seltcat" style="width: 278px">
			    <?php
                   $sql = "select cat_code, cat_desc from cat_master ORDER BY cat_code";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['cat_code'].'">'.$row['cat_code']." | ".$row['cat_desc'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>
		  	</td>
		  </tr> 	
		  <tr><td></td></tr>
		  <tr>
		    <td style="width: 10px"></td>
		  	<td style="width: 132px">All Category Code</td>
		  	<td>:</td>
		  	<td colspan="5"><input type="checkbox" name="chkallcat" id="chkallcat" onclick="enabDis(this.id)" value="1"></td>
		  </tr>	
		  <tr><td></td></tr>	
	  	  <tr>
	  	    <td></td>
	  	    <td style="width: 140px" class="tdlabel">From Date</td>
	  	    <td>:</td> 
	  	    <td style="width: 134px">
				<input class="inputtxt" name="rptofdte" id ="rptofdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptofdte','ddMMyyyy')" style="cursor:pointer">
			</td>
			<td style="width: 27px"></td>
			<td style="width: 109px">To Date</td>
			<td>:</td>
			<td>
				<input class="inputtxt" name="rptotdte" id ="rptotdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptotdte','ddMMyyyy')" style="cursor:pointer">
			</td>
	  	  </tr>
	  	  <tr><td></td></tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td>Report Type</td>
	  	  	<td>:</td>
	  	  	<td colspan="5">
	  	  		<select name="rptype" id="rpttype">
	  	  			<option value="D">Detail</option>
	  	  			<option value="S">Summary</option>
	  	  		</select>
	  	  	</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td style="width: 140px" class="tdlabel">&nbsp;</td>
	  	    <td></td> 
            <td style="width: 134px"></td> 
	   	  </tr> 
	   	
	  	  <tr>
	  	   <td style="width: 181px" colspan="8" align="center">
	  	   
	  	   <?php
	  	   		include("../Setting/btnprint.php");
	  	   ?>
	  	   </td>
	  	  </tr>
	  	   <tr><td></td></tr>

	  	</table>
	   </form>	
	</fieldset>
	 </div>
    <div class="spacer"></div>
</body>

</html>
