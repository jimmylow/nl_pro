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
    
		$fdte = date("Y-m-d", strtotime($_POST['rptofdte']));
     	$tdte = date("Y-m-d", strtotime($_POST['rptotdte']));
     	$fpr  = $_POST['selfpr'];
     	$tpr  = $_POST['seltpr'];
     	
     	#----------------Prepare Temp Table For Printing -----------------------------------
     	$sql  = " Delete From tmpiwipqc where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());

		$sql  = "SELECT productcode, productiondate, ticketno, productqty";
		$sql .= " FROM sew_entry";
    	$sql .= " where productiondate between '$fdte' and '$tdte'";
    	$sql .= " and 	productcode between '$fpr' and '$tpr'";
    	$sql .= " and   (qcqty = 0 or qcqty is null) and stat = 'ACTIVE'";
		$rs_result = mysql_query($sql);

		while ($row = mysql_fetch_assoc($rs_result)) { 

		   	$productcode    = $row['productcode'];
		   	$productiondate = $row['productiondate'];
		   	$ticketno       = $row['ticketno'];
		   	$productqty     = $row['productqty'];
		   	if (empty($productqty)){$productqty = 0;}
       		
		   	$sqlc  = "select cost_dozprice";
	    	$sqlc .= " from pro_cd_master";
       		$sqlc .= " where prod_code ='$productcode'";
       		$sql_resultc = mysql_query($sqlc);
       		$rowc = mysql_fetch_array($sql_resultc);
	   		$cost_dozprice = $rowc['cost_dozprice'];
		   	if (empty($cost_dozprice)){$cost_dozprice = 0;}

		   	if (($cost_dozprice == NULL) or ($cost_dozprice == 0)){
		   		$sqlc  = "select totcost";
	    		$sqlc .= " from prod_matmain";
       			$sqlc .= " where prod_code ='$productcode'";
       			$sql_resultc = mysql_query($sqlc);
       			$rowc = mysql_fetch_array($sql_resultc);
		   		$cost_dozprice = $rowc['totcost'];
		   		if (empty($cost_dozprice)){$cost_dozprice = 0;}
		   	}
			$bal = $productqty;
			if (empty($bal)){$bal = 0;}
			$wage = $bal * $cost_dozprice / 12;
		   	       			    	
		    $sqliq  = " Insert Into tmpiwipqc";
        	$sqliq .= " Values ('$var_loginid', '$productcode', '$productiondate', '$ticketno', '$productqty', '',";
        	$sqliq .= "         '', '$cost_dozprice', '$bal', '$wage')";
        	mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
		 }

     	 $fname = "iwipbfqc.rptdesign&__title=myReport"; 
         $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&usernm=".$var_loginid."&dbsel=".$varrpturldb."&fdte=".$fdte."&tdte=".$tdte."&fp=".$fpr."&tp=".$tpr;
         $dest .= urlencode(realpath($fname));

         //header("Location: $dest" );
         echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
         $backloc = "../prod_rpt/iwipbfqc.php?menucd=".$var_menucode;
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

	document.InpRawOpen.selfpr.focus();
	
	//Set up the date parsers
    var dateParser = new DateParser("dd-MM-yyyy");
      
	//Set up the DateMasks
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var dateMask1 = new DateMask("dd-MM-yyyy", "rptofdte");
	dateMask1.validationMessage = errorMessage;
	
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
	var x=document.forms["InpRawOpen"]["selfpr"].value;
	if (x==null || x=="")
	{
		alert("From Product Cannot Be Blank");
		document.InpRawOpen.selfpr.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["seltpr"].value;
	if (x==null || x=="")
	{
		alert("To Product Cannot Be Blank");
		document.InpRawOpen.seltpr.focus();
		return false;
	}
		
	var x=document.forms["InpRawOpen"]["rptofdte"].value;
	if (x==null || x=="")
	{
		alert("From QC Date Cannot Be Blank");
		document.InpRawOpen.rptofdte.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["rptotdte"].value;
	if (x==null || x=="")
	{
		alert("To QC Date Cannot Be Blank");
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
        alert("QC To Date Must Larger Then QC From Date");
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
	<fieldset name="Group1" style=" width: 758px; height: 205px;" class="style2">
	 <legend class="title">INCOMPLETE WORK IN PROGRESS BEFORE QC</legend>
	  <br />
	  <form name="InpRawOpen" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 139px; width: 750px;" onSubmit= "return chkSubmit()">
		<table style="width: 807px; height: 102px;">
	  	    <tr>
	  	    	<td style="width: 6px"></td>
	  	    	<td style="width: 140px" class="tdlabel">From Product</td>
	  	    	<td>:</td> 
	  	    	<td style="width: 134px">
					<select name="selfpr" id ="selfpr" style="width: 100px">
			   		<?php
                   		$sql = "select prod_code, prod_desc from pro_cd_master ORDER BY prod_code";
                   		$sql_result = mysql_query($sql);
                   		echo "<option size =30 selected></option>";
                       
				   		if(mysql_num_rows($sql_result)) 
				   		{
				   			while($row = mysql_fetch_assoc($sql_result)) 
				   			{ 
					  			echo '<option value="'.$row['prod_code'].'">'.$row['prod_code']." | ".$row['prod_desc'].'</option>';
				 	 		} 
				   		}
	            	?>				   
			  		</select>
				</td>
				<td style="width: 27px"></td>
				<td style="width: 109px">To Product</td>
				<td>:</td>
				<td>
					<select name="seltpr" id ="seltpr" style="width: 100px">
			   		<?php
                   		$sql = "select prod_code, prod_desc from pro_cd_master ORDER BY prod_code";
                   		$sql_result = mysql_query($sql);
                   		echo "<option size =30 selected></option>";
                       
				   		if(mysql_num_rows($sql_result)) 
				   		{
				   			while($row = mysql_fetch_assoc($sql_result)) 
				   			{ 
					  			echo '<option value="'.$row['prod_code'].'">'.$row['prod_code']." | ".$row['prod_desc'].'</option>';
				 	 		} 
				   		}
	            	?>				   
			  		</select>
				</td>
		    </tr>
		    <tr><td></td></tr>
			 <tr>
		    	<td></td>
		    	<td>From Production Date</td>
		    	<td>:</td>
		    	<td>
		    		<input class="inputtxt" name="rptofdte" id ="rptofdte" type="text" value="<?php  echo date("01-m-Y"); ?>" style="width: 100px" />
					<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptofdte','ddMMyyyy')" style="cursor:pointer" />
		    	</td>
		    	<td></td>
		    	<td>To Production Date</td>
		    	<td>:</td>
		    	<td>		
					<input class="inputtxt" name="rptotdte" id ="rptotdte" type="text" value="<?php  echo date("t-m-Y"); ?>" style="width: 100px" />
					<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptotdte','ddMMyyyy')" style="cursor:pointer" />		    	
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
	  	  <tr>
	  	   <td colspan="8" align="center">
	  	   <?php
	  	   		//include("../Setting/btnprint.php");
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
