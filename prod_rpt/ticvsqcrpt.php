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
     
     	$frbuy  = $_POST['sfbuy'];
     	$tobuy  = $_POST['stbuy'];
     	$frdte  = date("Y-m-d", strtotime($_POST['rptofdte']));
     	$todte  = date("Y-m-d", strtotime($_POST['rptotdte']));
     	
     	if ($frdte <> "" and $todte <> ""){
     	
     		#----------------Prepare Temp Table For Printing -----------------------------------
     		$sql  = " Delete From tmpticvsqc where usernm = '$var_loginid'";
        	mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
        	
   			$sql = "SELECT x.ticketno, x.qcdate, x.okqty, x.defectqty, x.missingqty, y.productqty, y.buyer, y.productcode";
			$sql .= " FROM sew_qc x, sew_entry y";
    		$sql .= " where x.ticketno = y.ticketno";
    		$sql .= " and   x.qcdate between '$frdte' and '$todte'";
    		$sql .= " and   y.buyer between '$frbuy' and '$tobuy'";
    		$sql .= " Order BY x.ticketno";	  
			$rs_result = mysql_query($sql);

			while ($row = mysql_fetch_assoc($rs_result)) { 
		    	$ticketno    = $row['ticketno'];
		    	$qcdate      = $row['qcdate'];
		    	$okqty       = $row['okqty'];
		    	$defectqty   = $row['defectqty'];
		    	$missingqty  = $row['missingqty'];
		    	$productqty  = $row['productqty'];
		    	$buyer       = $row['buyer'];
		    	$productcode = $row['productcode'];
		    	if(empty($okqty)){$okqty = 0;}
		    	if(empty($defectqty)){$defectqty = 0;}
		    	if(empty($missingqty)){$missingqty = 0;}
		    	if(empty($productqty)){$productqty = 0;}
				$difqty = $productqty - $okqty;
			       			
  				$sqliq  = " Insert Into tmpticvsqc ";
        		$sqliq .= " Values ('$var_loginid', '$qcdate', '$buyer', '$ticketno', '$productqty',";
        		$sqliq .= "   '$defectqty', '$okqty', '$missingqty', '$difqty', '$productcode')";
        		mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
     		}
     		#-----------------------------------------------------------------------------------
			// Redirect browser
        	$fname = "ticvsqcrpt.rptdesign&__title=myReport"; 
        	$dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&fd=".$frdte."&td=".$todte."&usernm=".$var_loginid."&dbsel=".$varrpturldb."&fb=".$frbuy."&tb=".$tobuy;
        	$dest .= urlencode(realpath($fname));

        	//header("Location: $dest" );
         	echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
         	$backloc = "../prod_rpt/ticvsqcrpt.php?menucd=".$var_menucode;
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

	document.InpRawOpen.rptofdte.focus();
						
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
	var x=document.forms["InpRawOpen"]["rptofdte"].value;
	if (x==null || x=="")
	{
		alert("From DO Date Must Not Be Blank");
		document.InpRawOpen.rptofdte.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["rptotdte"].value;
	if (x==null || x=="")
	{
		alert("To DO Date Must Not Be Blank");
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
        alert("To DO Date Must Larger Then From Date");
		document.InpRawOpen.rptofdte.focus();
		return false;
    }
    
    var x=document.forms["InpRawOpen"]["sfbuy"].value;
	if (x==null || x=="")
	{
		alert("From Buyer Cannot Be Blank");
		document.InpRawOpen.sfbuy.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["stbuy"].value;
	if (x==null || x=="")
	{
		alert("To Buyer Cannot Be Blank");
		document.InpRawOpen.stbuy.focus();
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
	 <legend class="title">TICKET VS QC CHECKING REPORT</legend>
	  <br />
	  <form name="InpRawOpen" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 619px;">
		<table>
	  	  <tr>
	  	    <td></td>
	  	    <td style="width: 140px" class="tdlabel">From QC Date</td>
	  	    <td>:</td> 
	  	    <td style="width: 134px">
				<input class="inputtxt" name="rptofdte" id ="rptofdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px" />
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptofdte','ddMMyyyy')" style="cursor:pointer" />
			</td>
			<td style="width: 27px"></td>
			<td style="width: 109px">To QC Date</td>
			<td>:</td>
			<td>
				<input class="inputtxt" name="rptotdte" id ="rptotdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px" />
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptotdte','ddMMyyyy')" style="cursor:pointer" />
			</td>
	  	  </tr>
	  	  <tr><td></td></tr>
	  	  <tr>
		    	<td></td>
		    	<td>From Buyer</td>
		    	<td>:</td>
		    	<td>
		    		<select name="sfbuy" id ="sfbuy" style="width: 150px">
			   		<?php
                   		$sql = "select customer_code, customer_desc from customer_master where active_flag = 'ACTIVE' ORDER BY customer_code";
                   		$sql_result = mysql_query($sql);
                   		echo "<option size =30 selected></option>";
                       
				   		if(mysql_num_rows($sql_result)) 
				   		{
				   			while($row = mysql_fetch_assoc($sql_result)) 
				   			{ 
					  			echo '<option value="'.$row['customer_code'].'">'.$row['customer_code']." | ".$row['customer_desc'].'</option>';
				 	 		} 
				   		}
	            	?>				   
			  		</select>
		    	</td>
		    	<td></td>
		    	<td>To Buyer</td>
		    	<td>:</td>
		    	<td>
		    		<select name="stbuy" id ="stbuy" style="width: 150px">
			   		<?php
                   		$sql = "select customer_code, customer_desc from customer_master where active_flag = 'ACTIVE' ORDER BY customer_code";
                   		$sql_result = mysql_query($sql);
                   		echo "<option size =30 selected></option>";
                       
				   		if(mysql_num_rows($sql_result)) 
				   		{
				   			while($row = mysql_fetch_assoc($sql_result)) 
				   			{ 
					  			echo '<option value="'.$row['customer_code'].'">'.$row['customer_code']." | ".$row['customer_desc'].'</option>';


				 	 		} 
				   		}
	            	?>				   
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
