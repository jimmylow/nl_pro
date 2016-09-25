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
     	$call  = $_POST['chkall'];
     	$fpodte  = date("Y-m-d", strtotime($_POST['inqfdte']));
     	$tpodte  = date("Y-m-d", strtotime($_POST['inqtdte']));
     	$todte = date("Y-m-d");      	
     
     	if ($call == '1'){
     		$sqlm  = "select count(*)";
		    $sqlm .= " from po_master";
        	$sqlm .= " where po_date between '$fpodte' and '$tpodte'";
        	$sqlm .= " and active_flag = 'ACTIVE'";
     	}else{
     		$sqlm  = "select count(*)";
		    $sqlm .= " from po_master";
        	$sqlm .= " where po_date between '$fpodte' and '$tpodte'";
			$sqlm .= " and supplier between '$fsupp' and '$tsupp'";
			$sqlm .= " and active_flag = 'ACTIVE'";
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
     		$sql  = " Delete From tmppodet where usernm = '$var_loginid'";
        	mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
        	
        	if ($call == '1'){
     			$sqlm  = "select x.po_no, x.supplier, x.po_date, x.del_date, y.itemcode, y.qty, y.uprice, y.itmdesc, y.itmuom";
		    	$sqlm .= " from po_master x, po_trans y";
        		$sqlm .= " where x.po_date between '$fpodte' and '$tpodte'";
        		$sqlm .= " and 	 x.po_no = y.po_no";
        		$sqlm .= " and   active_flag = 'ACTIVE'";
     		}else{
     			$sqlm  = "select x.po_no, x.supplier, x.po_date, x.del_date, y.itemcode, y.qty, y.uprice, y.itmdesc, y.itmuom";
		    	$sqlm .= " from po_master x, po_trans y";
        		$sqlm .= " where po_date between '$fpodte' and '$tpodte'";
        		$sqlm .= " and 	 x.po_no = y.po_no";
				$sqlm .= " and   x.supplier between '$fsupp' and '$tsupp'";
				$sqlm .= " and   active_flag = 'ACTIVE'";
     		}
			$rs_result = mysql_query($sqlm);

			while ($row = mysql_fetch_assoc($rs_result)) { 
		    	$pono   = $row['po_no'];
		    	$supp   = $row['supplier'];
		    	$podte  = $row['po_date'];
		    	$deldte = $row['del_date'];
		    	$mitmcd = mysql_real_escape_string($row['itemcode']);
		    	$qtyord = $row['qty'];
		    	$poprice = $row['uprice'];
		    	$itmdesc = mysql_real_escape_string($row['itmdesc']);
		    	$uom    = $row['itmuom'];
		    	if ($qtyord == "" or $qtyord == null){ 
        		  $qtyord  = 0.00;
        		}
		    	
		    	#------------Supplier Name-----------------------------			
				$sqlop = "select supplier_desc, currency_code from supplier_master ";
        		$sqlop .= " where supplier_code ='$supp'";
        		$sql_resultop = mysql_query($sqlop);
        		$rowop = mysql_fetch_array($sql_resultop);        
        		$suppde = $rowop[0];
        		$curr = $rowop[1];
        		#------------------------------------------------------
        		
        		#------------GRN QTy From Date To Date-----------------------------			
				$sqlg = "select sum(totalqty) from rawmat_tran ";
        		$sqlg .= " where item_code ='$mitmcd' ";
        		$sqlg .= " and tran_type in ('REC')";
        		$sqlg .= " and po_number = '$pono'";
        		$sql_resultg = mysql_query($sqlg);
        		$rowg = mysql_fetch_array($sql_resultg);        
        		if ($rowg[0] == "" or $rowg[0] == null){ 
        		  $rowg[0]  = 0.00;
        		}
        		$recbal = $rowg[0];
        		#-------------------------------------------------------------
        		
        		#---------------------------Avg Cost------------------------
        		$sqlv  = "select sum(y.totalqty * y.myr_unit_cost) / sum(y.totalqty)";
        		$sqlv .= " from rawmat_receive x, rawmat_receive_tran y";
        		$sqlv .= " where y.item_code ='$mitmcd'";
				$sqlv .= " and   x.grndate  <= '$todte'";
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
					$sqlv1 .= " where rm_code ='$mitmcd'";
					$sql_resultv1 = mysql_query($sqlv1);
					$rowv1 = mysql_fetch_array($sql_resultv1);
					if ($rowv1[0] == "" or $rowv1[0] == null){ 
						$rowv1[0]  = 0.00;
					}
					$avgcst = $rowv1[0];
				}
				#------------------------------------------------------------------------------
				
				#------------last grn Date-----------------------------			
				$sqld = "select max(grndate) from rawmat_tran ";
        		$sqld .= " where item_code ='$mitmcd' ";
        		$sqld .= " and tran_type in ('REC')";
        		$sqld .= " and po_number = '$pono'";
        		$sql_resultd = mysql_query($sqld);
        		$rowd = mysql_fetch_array($sql_resultd);      
        		$lstgdte = $rowd[0];
        		if ($lstgdte == ""){
        			$lstgdte = $podte;
        		}	
        		#-------------------------------------------------------------
        		
        		#----------------Currency Rate---------------------------------------------
        		$exhmth = date("n",strtotime($podte)); 
				$exhyr  = date("Y",strtotime($podte));
				 	
				if ($curr == "MYR"){
					$brate = 1;
				}else{	
					$sql4 = "select buyrate from curr_xrate ";
       				$sql4 .= " where xmth ='$exhmth' and xyr ='$exhyr' ";
       				$sql4 .= " and curr_code = '$curr'";
       				$sql_result4 = mysql_query($sql4) or die("Cant Echange Rate Table ".mysql_error());;
       				$row4 = mysql_fetch_array($sql_result4);
       				$brate = $row4[0];
       			}	
				$basepri = $poprice * $brate;
        		#--------------------------------------------------------------------------
		    	
		    	$sqliq  = " Insert Into tmppodet (ponum, itemcode, suppcd, suppde, ";
        		$sqliq .= "  usernm, qtyord, qtyrec, itmuom, avgcst, poprice, rmpoprice, deldte, fulldte, ";
        		$sqliq .= "  podte, itmdesc)";
        		$sqliq .= " Values ('$pono', '$mitmcd', '$supp', '$suppde', '$var_loginid',";
        		$sqliq .= "   '$qtyord', '$recbal', '$uom', '$avgcst', '$poprice', '$basepri', '$deldte',";
        		$sqliq .= "   '$lstgdte', '$podte', '$itmdesc')";
        		mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
			}

			// Redirect browser
        	$fname = "pur_det.rptdesign&__title=myReport"; 
        	$dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&fd=".$fpodte."&td=".$tpodte."&usernm=".$var_loginid."&dbsel=".$varrpturldb;
        	$dest .= urlencode(realpath($fname));

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

	document.InpPurOpen.selfsupp.focus();
						
 	//Set up the date parsers
    var dateParser = new DateParser("dd-MM-yyyy");
      
	//Set up the DateMasks
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var dateMask1 = new DateMask("dd-MM-yyyy", "inqfdte");
	dateMask1.validationMessage = errorMessage;	
	
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var dateMask1 = new DateMask("dd-MM-yyyy", "inqtdte");
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
	var txtchk = document.InpPurOpen.chkall;
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
	var x=document.forms["InpPurOpen"]["chkall"];	
	if (!x.checked){
		var fs = document.forms["InpPurOpen"]["selfsupp"].value;
		if (fs==null || fs=="")
		{
			alert("From Supplier Cannot Be Blank");
			document.InpPurOpen.selfsupp.focus();
			return false;
		}
		
		var ts = document.forms["InpPurOpen"]["seltsupp"].value;
		if (ts==null || ts=="")
		{
			alert("To Supplier Cannot Be Blank");
			document.InpPurOpen.seltsupp.focus();
			return false;
		}
	}

	var x=document.forms["InpPurOpen"]["inqfdte"].value;
	if (x==null || x=="")
	{
		alert("P/O Order From Date Cannot Be Blank");
		document.InpPurOpen.inqfdte.focus();
		return false;
	}

	var x=document.forms["InpPurOpen"]["inqfdte"].value;
	if (x==null || x=="")
	{
		alert("P/O Order From Date Cannot Be Blank");
		document.InpPurOpen.inqfdte.focus();
		return false;
	}
	
	var x=document.forms["InpPurOpen"]["inqtdte"].value;
	if (x==null || x=="")
	{
		alert("P/O Order To Date Cannot Be Blank");
		document.InpPurOpen.inqtdte.focus();
		return false;
	}
	
	var x=document.forms["InpPurOpen"]["inqfdte"].value;
	var y=document.forms["InpPurOpen"]["inqtdte"].value;
	
    var fromdate = x.split('-');
        from_date = new Date();
        from_date.setFullYear(fromdate[2],fromdate[1]-1,fromdate[0]); 
    
    var todate = y.split('-');
        to_date = new Date();
        to_date.setFullYear(todate[2],todate[1]-1,todate[0]);
    if (from_date > to_date ) 
    {
        alert("P/O To Date Must Larger Then From Date");
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
	<fieldset name="Group1" style=" width: 598px; height: 270px;" class="style2">
	 <legend class="title">PURCHASE REPORT</legend>
	  <br>
	  <form name="InpPurOpen" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 583px;">
		<table style="width: 573px">
		  <tr>
		  	<td style="width: 15px"></td>
		  	<td style="width: 128px">From Supplier Code</td>
		  	<td style="width: 2px">:</td>
		  	<td style="width: 134px">
		  		<select name="selfsupp" id="selfsupp" style="width: 278px">
			    <?php
                   $sql = "select supplier_code, supplier_desc from supplier_master where active_flag = 'ACTIVE' ORDER BY supplier_code";
                   $sql_result = mysql_query($sql) or die("Enable To Query Supplier".mysql_error());
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
		  <tr><td style="width: 15px"></td></tr>
		  <tr>
		  	<td></td>
		  	<td>To Supplier Code</td>
		  	<td>:</td>
		  	<td>
		  		<select name="seltsupp" id="seltsupp" style="width: 278px">
			    <?php
                   $sql = "select supplier_code, supplier_desc from supplier_master where active_flag = 'ACTIVE' ORDER BY supplier_code";
                   $sql_result = mysql_query($sql) or die("Enable To Query Supplier".mysql_error());
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
		  <tr><td style="width: 15px"></td></tr>
		  <tr>
		  	  <td></td>
		  	  <td>All Supplier</td>
		  	  <td>:</td>
		  	  <td><input type="checkbox" name="chkall" id="chkall" onclick="enabDis(this.id)" value="1"></td>
		  </tr>
		  <tr><td style="width: 15px"></td></tr>	
	  	  <tr>
	  	    <td style="width: 15px"></td>
	  	    <td style="width: 128px" class="tdlabel">From P/O Date</td>
	  	    <td style="width: 2px">:</td> 
	  	    <td style="width: 134px">
				<input class="inputtxt" name="inqfdte" id ="inqfdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('inqfdte','ddMMyyyy')" style="cursor:pointer">
			</td>	
	  	  </tr>
	  	  <tr><td style="width: 15px"></td></tr>
	  	  <tr>
	  	    <td style="width: 15px"></td>
	  	    <td style="width: 128px" class="tdlabel">To P/O Date</td>
	  	    <td style="width: 2px">:</td> 
	  	    <td style="width: 134px">
				<input class="inputtxt" name="inqtdte" id ="inqtdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('inqtdte','ddMMyyyy')" style="cursor:pointer">
			</td>	
	  	  </tr>
	  	  <tr><td style="width: 15px"></td></tr>
	  	  <tr>
	  	    <td style="width: 15px"></td> 
	  	    <td style="width: 128px" class="tdlabel">&nbsp;</td>
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
	  	   <tr><td style="width: 15px">&nbsp;</td></tr>
		    <tr><td style="width: 15px">&nbsp;</td></tr>	
	  	</table>
	   </form>	
	</fieldset>
	 </div>
    <div class="spacer"></div>
</body>

</html>
