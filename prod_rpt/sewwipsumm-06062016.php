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
	  set_time_limit(3600);
      include("../Setting/ChqAuth.php");
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
     	$adte = date("Y-m-d", strtotime($_POST['rptasat']));
     	$fstyp = $_POST['selfstyp'];
     	$tstyp = $_POST['seltstyp'];
		$rptopt  = $_POST['rptopt'];
     	
     	unset($arrstyp);
     	$arrstyp = array();
     	$i = 0;
     	$sqlc  = "select sewtype_code from prosewtype_master";
     	$sqlc .= " where sewtype_code between '$fstyp' and '$tstyp'";
     	$rs_rt = mysql_query($sqlc) or die(mysql_error());
     	while ($rw = mysql_fetch_assoc($rs_rt)) { 
			$arrstyp[$i] = $rw['sewtype_code'];
			$i = $i + 1;
		}

		$cntstyp = 0;			
		$sqlo = "select count(*) from prosewtype_master";
   	 	$sql_resulto = mysql_query($sqlo);
	    $row_bal = mysql_fetch_array($sql_resulto);      
	    if ($row_bal[0] == "" or $row_bal[0] == null){ 
	    	 $row_bal[0]  = 0.00;
	    }
	    $cntstyp = $row_bal[0];
		$cntarrstyp = count($arrstyp);
     	 
     	#----------------Prepare Temp Table For Printing -----------------------------------
     	$sql  = " Delete From tmpsewaging where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());

		$shardSize = 3000;
	 	$sqliq = "";		   			
	 	$k = 0;
		$sql  = "SELECT DISTINCT prod_code, prod_desc, modified_on";
		$sql .= " from pro_jobmodel";
		$sql .= " order by prod_code";
		$rs_result = mysql_query($sql) or die(mysql_error());

		while ($row = mysql_fetch_assoc($rs_result)) { 
			$productcode = htmlentities($row['prod_code']);
			
			$sproductqty = 0;			
			$sqlo = "select sum(totalqty) from wip_tran ";
	    	$sqlo .= " where item_code ='$productcode'";
	    	$sqlo .= " and tran_type in ('REC', 'ADJ', 'RTN', 'ISS', 'OPB', 'REJ')";
	    	$sqlo .= " and trxdate <= '$adte'"; 
	   	 	$sql_resulto = mysql_query($sqlo);
	    	$row_bal = mysql_fetch_array($sql_resulto);      
	    	if ($row_bal[0] == "" or $row_bal[0] == null){ 
	    	   $row_bal[0]  = 0.00;
	    	}
	    	$sproductqty = $row_bal[0];
		   	
			if ($sproductqty <> 0){
				$sewtype = '';
				$sqlc  = "select distinct sewtype, buyer from sew_entry";
				$sqlc .= " where productcode = '$productcode'";
				$sqlc .= " and sewtype <> ''";
				$sql_resultc = mysql_query($sqlc);
       			$rowc = mysql_fetch_array($sql_resultc);
       			$sewtype = $rowc['sewtype'];
				$buyer = $rowc['buyer'];
				
   				$sewtype_desc = '';
		   		$sqlc  = "select sewtype_desc";
	    		$sqlc .= " from prosewtype_master";
       			$sqlc .= " where sewtype_code ='$sewtype'";
       			$sql_resultc = mysql_query($sqlc);
       			$rowc = mysql_fetch_array($sql_resultc);
       			$sewtype_desc = mysql_real_escape_string($rowc['sewtype_desc']);
		   	
		   		$cost_pcsprice = 0;
		   		$sql = "select sum(totcost)/12 from prod_matmain ";
	    		$sql .= " where prod_code = '$productcode'";
	    		$sql_result = mysql_query($sql);
	    		$row_price  = mysql_fetch_array($sql_result);   
	    		if ($row_price[0] == "" or $row_price[0] == null){ 
	       			$row_price[0]  = 0.00;
	    		}
	    		$cost_pcsprice = $row_price[0];
	   			$cost_pcsprice = number_format((float)$cost_pcsprice , 2, '.', '');
	    		$balamt = 0;
	    		$balamt = $sproductqty * $cost_pcsprice;
				if (empty($balamt)){$balamt = 0;}
				
				//$sqliq  = " Insert Into tmpsewaging (sewtype, sewtyde, categcd, prodcd, cstpri, balsewqty, balamt, usernm, buyer)";
        		//$sqliq .= " Values ('$sewtype', '$sewtype_desc', '$prod_type', '$productcode',";
        		//$sqliq .= "         '$cost_pcsprice', '$sproductqty', '$balamt', '$var_loginid', '$buyer')";
		   	    
		   	    if ($cntstyp == $cntarrstyp){
					if ($k % $shardSize == 0) {
						if ($k != 0) {	  
							mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
						}
						$sqliq = 'Insert Into tmpsewaging (sewtype, sewtyde, categcd, prodcd, cstpri, balsewqty, balamt, usernm, buyer) values';
					}
					$sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$sewtype', '$sewtype_desc', '$prod_type', '$productcode',
																	   '$cost_pcsprice', '$sproductqty', '$balamt', '$var_loginid', '$buyer')";
					$k = $k + 1;	
        			//mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
		 		}else{
		 			if (in_array($sewtype, $arrstyp)){
						if ($k % $shardSize == 0) {
							if ($k != 0) {	  
								mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
							}
							$sqliq = 'Insert Into tmpsewaging (sewtype, sewtyde, categcd, prodcd, cstpri, balsewqty, balamt, usernm, buyer) values';
						}
						$sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$sewtype', '$sewtype_desc', '$prod_type', '$productcode',
																		    '$cost_pcsprice', '$sproductqty', '$balamt', '$var_loginid', '$buyer')";
						$k = $k + 1;
		 				//mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
		 			}
		 		}
		 	}
		 }
		 if ($sqliq <> ""){
			mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
		 }

		 switch($rptopt){
		 case 'A':
			$fname = "sewagingsumm.rptdesign&__title=myReport"; 
			break;
		 case 'B':
			$fname = "sewagingsumbuy.rptdesign&__title=myReport";
			break;
		 }
		 $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&usernm=".$var_loginid."&dbsel=".$varrpturldb."&adte=".$adte."&ftyp=".$fstyp."&ttyp=".$tstyp;
         $dest .= urlencode(realpath($fname));

         //header("Location: $dest" );
         echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
         $backloc = "../prod_rpt/sewwipsumm.php?menucd=".$var_menucode;
       	 echo "<script>";
       	 echo 'location.replace("'.$backloc.'")';
       	 echo "</script>";        	
     }
    } 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />

	
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

	document.InpRawOpen.selfstyp.focus();
	
	//Set up the date parsers
    var dateParser = new DateParser("dd-MM-yyyy");
      
	//Set up the DateMasks
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var dateMask1 = new DateMask("dd-MM-yyyy", "rptasat");
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

function chkSubmit()
{
	var x=document.forms["InpRawOpen"]["selfstyp"].value;
	if (x==null || x=="")
	{
		alert("From Sewing Type Cannot Be Blank");
		document.InpRawOpen.selfstyp.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["seltstyp"].value;
	if (x==null || x=="")
	{
		alert("To Sewing Type Cannot Be Blank");
		document.InpRawOpen.seltstyp.focus();
		return false;
	}
		
	var x=document.forms["InpRawOpen"]["rptasat"].value;
	if (x==null || x=="")
	{
		alert("As At Production Date Cannot Be Blank");
		document.InpRawOpen.rptasat.focus();
		return false;
	}
}
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
	<div class="contentc">
	<fieldset name="Group1" style=" width: 758px; height: 205px;" class="style2">
	 <legend class="title">WIP AGEING SUMMARY - COST</legend>
	  <br />
	  <form name="InpRawOpen" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 139px; width: 750px;" onSubmit= "return chkSubmit()">
		<table style="width: 807px; height: 102px;">
	  	    <tr>
	  	    	<td style="width: 6px"></td>
	  	    	<td style="width: 140px" class="tdlabel">From Sew Type</td>
	  	    	<td>:</td> 
	  	    	<td style="width: 134px">
					<select name="selfstyp" id ="selfstyp" style="width: 100px">
			   		<?php
                   		$sql = "select sewtype_code, sewtype_desc from prosewtype_master ORDER BY sewtype_code";
                   		$sql_result = mysql_query($sql);
                   		echo "<option size =30 selected></option>";
                       
				   		if(mysql_num_rows($sql_result)) 
				   		{
				   			while($row = mysql_fetch_assoc($sql_result)) 
				   			{ 
					  			echo '<option value="'.$row['sewtype_code'].'">'.$row['sewtype_code']." | ".$row['sewtype_desc'].'</option>';
				 	 		} 
				   		}
	            	?>				   
			  		</select>
				</td>
				<td></td>
				<td style="width: 109px">To Sew Type</td>
				<td>:</td>
				<td>
					<select name="seltstyp" id ="seltstyp" style="width: 100px">
			   		<?php
                   		$sql = "select sewtype_code, sewtype_desc from prosewtype_master ORDER BY sewtype_code";
                   		$sql_result = mysql_query($sql);
                   		echo "<option size =30 selected></option>";
                       
				   		if(mysql_num_rows($sql_result)) 
				   		{
				   			while($row = mysql_fetch_assoc($sql_result)) 
				   			{ 
					  			echo '<option value="'.$row['sewtype_code'].'">'.$row['sewtype_code']." | ".$row['sewtype_desc'].'</option>';
				 	 		} 
				   		}
	            	?>				   
			  		</select>
				</td>
		    </tr>
		    <tr>
				<td></td>
			</tr>
			 <tr>
		    	<td></td>
		    	<td>As At Production Date</td>
		    	<td>:</td>
		    	<td>
		    		<input class="inputtxt" name="rptasat" id ="rptasat" type="text" value="<?php  echo date("01-m-Y"); ?>" style="width: 100px" />
					<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptasat','ddMMyyyy')" style="cursor:pointer" />
		    	</td>
		    </tr>
			<tr><td></td></tr>
			<tr>
				<td></td>
		    	<td>Report Group Option</td>
		    	<td>:</td>
		    	<td>
		    		<select id="rptopt" name="rptopt">
						<option value='A'>Sew Type</option>
						<option value='B'>Buyer</option>
					</select>
		    	</td>
			</tr>
			<tr><td></td></tr>
	  	  	<tr>
	  	  		<td style="width: 6px"></td>
	  	  	  	<td colspan="7" align="center">	  	   
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
