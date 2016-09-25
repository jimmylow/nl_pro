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
      $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
     
     	$fsupp = $_POST['selfsupp'];
     	$tsupp = $_POST['seltsupp'];
     	$call  = $_POST['chkallsupp'];
     	$frdte  = date("Y-m-d", strtotime($_POST['rptfdte']));
     	$trdte  = date("Y-m-d", strtotime($_POST['rpttdte']));
     	
     	if ($call == '1'){
     		$sqlm  = "select count(*)";
		    $sqlm .= " from rawmat_receive";
        	$sqlm .= " where grndate between '$frdte' and '$trdte'";
     	}else{
     		$sqlm  = "select count(*)";
		    $sqlm .= " from rawmat_receive x, po_master y";
        	$sqlm .= " where grndate between '$frdte' and '$trdte'";
			$sqlm .= " and y.supplier between '$fsupp' and '$tsupp'";
			$sqlm .= " and x.po_number = y.po_no";
			$sqlm .= " and y.active_flag = 'ACTIVE'";
     	}
     	$sql_resultm = mysql_query($sqlm);
        $rowm = mysql_fetch_array($sql_resultm);
        $cnt  = $rowm[0];

		if($cnt == 0){
			echo "<script>";   
      		echo "alert('No Data Found On Selected Query');"; 
      		echo "</script>";
		}else{
     	
     		#----------------Prepare Temp Table For Printing -----------------------------------
     		$sql  = " Delete From tmpstkinvtab where usernm = '$var_loginid'";
        	mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
        	        	
        	if ($call == '1'){
     			$sqlm  = "select rm_receive_id, refno, po_number, invno, grndate";
		    	$sqlm .= " from rawmat_receive";
        		$sqlm .= " where grndate between '$frdte' and '$trdte'";
     		}else{
     			$sqlm  = "select x.rm_receive_id, x.refno, x.po_number, x.invno, x.grndate";
		    	$sqlm .= " from rawmat_receive x, po_master y";
        		$sqlm .= " where grndate between '$frdte' and '$trdte'";
				$sqlm .= " and y.supplier between '$fsupp' and '$tsupp'";
				$sqlm .= " and x.po_number = y.po_no";
				$sqlm .= " and y.active_flag = 'ACTIVE'";
     		}
     		$rs_result = mysql_query($sqlm);

			while ($row = mysql_fetch_assoc($rs_result)) {
				$recid = $row['rm_receive_id'];
		    	$refno = mysql_real_escape_string($row['refno']);
		    	$ponum = $row['po_number']; 
		    	$invno = mysql_real_escape_string($row['invno']);
		       	$redte = $row['grndate'];
		    	
		    	$sqlc  = "select supplier";
		    	$sqlc .= " from po_master";
        		$sqlc .= " where po_no ='$ponum'";
        		$sql_resultc = mysql_query($sqlc);
        		$rowc = mysql_fetch_array($sql_resultc);
        		$suppn = $rowc['supplier'];
        		
        		$sqln  = "select supplier_desc";
		    	$sqln .= " from supplier_master";
        		$sqln .= " where supplier_code ='$suppn'";
        		$sql_resultn = mysql_query($sqln);
        		$rown = mysql_fetch_array($sql_resultn);
        		$suppd = $rown['supplier_desc'];

				$sqlm  = "select item_code, unit_cost, description, uom, totalqty, myr_unit_cost";
		    	$sqlm .= " from rawmat_receive_tran";
        		$sqlm .= " where rm_receive_id ='$recid'";
        		$sql_resultm = mysql_query($sqlm);
        		
        		while ($rowm = mysql_fetch_assoc($sql_resultm)) { 
        			$itmcode = mysql_real_escape_string($rowm['item_code']);
        			$reccst  = $rowm['unit_cost'];
        			$itmdesc = mysql_real_escape_string($rowm['description']);
        			$itmuom  = $rowm['uom'];
        			$recqty  = $rowm['totalqty'];
        			$myrcst  = $rowm['myr_unit_cost'];  
        			if ($reccst == "" or $reccst == null){$reccst  = 0.00;}
        			if ($recqty == "" or $reccst == null){$recqty  = 0.00;}
        			if ($myrcst == "" or $reccst == null){$myrcst  = 0.00;}
        	
					$sqliq  = " Insert Into tmpstkinvtab (supp_code, supp_desc, dte_rec, recno, ";
        			$sqliq .= "  pono, subcode, subcduom, subdesc, invno, refno, usernm, recunitcst, recqty, rmcost) ";
        			$sqliq .= " Values ('$suppn', '$suppd', '$redte', '$recid', '$ponum',";
        			$sqliq .= "   '$itmcode', '$itmuom', '$itmdesc', '$invno', '$refno', '$var_loginid', '$reccst', '$recqty', '$myrcst')";
        			
        			mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());	
				}	
     		}
     		#-----------------------------------------------------------------------------------
     		
     		// Redirect browser
			$fname = "stk_inv_rpt.rptdesign&__title=myReport"; 
        	$dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&fs=".$fsupp."&ts=".$tsupp."&fd=".$frdte."&td=".$trdte."&usernm=".$var_loginid."&dbsel=".$varrpturldb;
        	$dest .= urlencode(realpath($fname));

        	//header("Location: $dest" );
        	echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        	$backloc = "../stck_report/stck_inv_rpt.php?menucd=".$var_menucode;
           	echo "<script>";
           	echo 'location.replace("'.$backloc.'")';
            echo "</script>";
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

	document.InpRawOpen.selfsupp.focus();
						
 	//Set up the date parsers
    var dateParser = new DateParser("dd-MM-yyyy");
      
	//Set up the DateMasks
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var dateMask1 = new DateMask("dd-MM-yyyy", "rptfdte");
	dateMask1.validationMessage = errorMessage;
	
	var dateMask1 = new DateMask("dd-MM-yyyy", "rpttdte");
	dateMask1.validationMessage = errorMessage;		
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

function enabDis(idchk)
{
	var txtchk = document.InpRawOpen.chkallsupp;
	var x      = document.getElementById("selfsupp");
	var y      = document.getElementById("seltsupp");

	if (txtchk.checked){
		document.getElementById("selfsupp").disabled=true;
		document.getElementById("seltsupp").disabled=true;
	}else{
		document.getElementById("selfsupp").disabled=false;
		document.getElementById("seltsupp").disabled=false;
	}
}

function chkSubmit()
{
	var txtchk = document.InpRawOpen.chkallsupp;

	if (!txtchk.checked){
		var x=document.forms["InpRawOpen"]["selfsupp"].value;
		if (x==null || x=="")
		{
			alert("From Supplier Code Cannot Be Blank");
			document.InpRawOpen.selfsupp.focus();
			return false;
		}
		
		var x=document.forms["InpRawOpen"]["seltsupp"].value;
		if (x==null || x=="")
		{
			alert("To Supplier Code Cannot Be Blank");
			document.InpRawOpen.seltsupp.focus();
			return false;
		}
	}
	
	var x=document.forms["InpRawOpen"]["rptfdte"].value;
	if (x==null || x=="")
	{
		alert("From Received Date Cannot Be Blank");
		document.InpRawOpen.rptfdte.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["rpttdte"].value;
	if (x==null || x=="")
	{
		alert("To Received Date Cannot Be Blank");
		document.InpRawOpen.rpttdte.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["rptfdte"].value;
	var y=document.forms["InpRawOpen"]["rpttdte"].value;
	
    var fromdate = x.split('-');
        from_date = new Date();
        from_date.setFullYear(fromdate[2],fromdate[1]-1,fromdate[0]); 
    
    var todate = y.split('-');
        to_date = new Date();
        to_date.setFullYear(todate[2],todate[1]-1,todate[0]);
        
    if (from_date > to_date ) 
    {
        alert("Received To Date Must Larger Then Received From Date");
		document.InpRawOpen.rptfdte.focus();
		return false;
    }
}	
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
	<div class="contentc">
	<fieldset name="Group1" style=" width: 620px; height: 200px;" class="style2">
	 <legend class="title">STOCK INVOICE REPORT</legend>
	  <form name="InpRawOpen" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 583px;">
		<table style="width: 620px">
		  <tr>
		  	<td style="width: 10px"></td>
		  	<td style="width: 132px">From Supplier Code</td>
		  	<td style="width: 2px">:</td>
		  	<td style="width: 134px">
		  		<select name="selfsupp" id ="selfsupp" style="width: 278px">
			    <?php
                   $sql = "select supplier_code, supplier_desc from supplier_master ORDER BY supplier_code";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['supplier_code'].'">'.$row['supplier_code']." | ".$row['supplier_desc'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>
		  	</td>
		  </tr>
		  <tr><td style="width: 10px"></td></tr>
		  <tr>
		  	<td style="width: 10px"></td>
		  	<td style="width: 132px">To Supplier Code</td>
		  	<td style="width: 2px">:</td>
		  	<td style="width: 134px">
		  		<select name="seltsupp" id ="seltsupp" style="width: 278px">
			    <?php
                   $sql = "select supplier_code, supplier_desc from supplier_master ORDER BY supplier_code";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['supplier_code'].'">'.$row['supplier_code']." | ".$row['supplier_desc'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>
		  	</td>
		  </tr>
		  <tr><td style="width: 10px"></td></tr>
		  <tr>
		  	<td style="width: 10px"></td>
		  	<td style="width: 132px">All Supplier Code</td>
		  	<td>:</td>
		  	<td><input type="checkbox" name="chkallsupp" id="chkallsupp" onclick="enabDis(this.id)" value="1"></td>
		  </tr>
		  <tr><td style="width: 10px"></td></tr>	
	  	  <tr>
	  	    <td style="width: 10px"></td>
	  	    <td style="width: 132px" class="tdlabel">From Received Date</td>
	  	    <td style="width: 2px">:</td> 
	  	    <td style="width: 400px">
				<input class="inputtxt" name="rptfdte" id ="rptfdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptfdte','ddMMyyyy')" style="cursor:pointer">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				To Received Date :
				<input class="inputtxt" name="rpttdte" id ="rpttdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rpttdte','ddMMyyyy')" style="cursor:pointer">
			</td>	
	  	  </tr>
	  	  <tr>
	  	    <td style="width: 10px"></td> 
	  	    <td style="width: 132px" class="tdlabel">&nbsp;</td>
	  	    <td style="width: 2px"></td> 
            <td style="width: 134px"></td> 
	   	  </tr> 
	   	
	  	  <tr>
	  	   <td colspan="8" align="center">
	  	   
	  	   <?php
	  	   		include("../Setting/btnprint.php");
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
