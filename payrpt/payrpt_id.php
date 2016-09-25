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
     	$fjid = $_POST['selfjid'];
     	$tjid = $_POST['seltjid'];
 		$fwid = $_POST['selfwd'];
     	$twid = $_POST['seltwd'];
     	 
     	#----------------Prepare Temp Table For Printing -----------------------------------
     	$sql  = " Delete From tmprptbarcode where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());

		$sql  = "SELECT *";
		$sql .= " FROM sew_barcode";
    	$sql .= " where sewdate between '$fdte' and '$tdte'";
    	$sql .= " and prod_jobid between '$fjid' and '$tjid'";
    	$sql .= " and workid between '$fwid' and '$twid'";
    	$sql .= " Order BY workid, sewdate, prod_jobid, ticketno";
		$rs_result = mysql_query($sql);

		while ($row = mysql_fetch_assoc($rs_result)) { 
		   	$ticketno     = $row['ticketno'];
		   	$prod_code    = $row['prod_code'];
		   	$prod_jobsec  = $row['prod_jobsec'];
		   	$prod_jobid   = $row['prod_jobid'];
		   	$prod_jobrate = $row['prod_jobrate'];
		   	$barcodeno    = $row['barcodeno'];
		   	$workid       = $row['workid'];
		   	$sewdate      = $row['sewdate'];
		   	$qtydoz       = $row['qtydoz'];
		   	$qtypcs       = $row['qtypcs'];
		   	
		   	$sqlc  = "select workname";
	    	$sqlc .= " from wor_detmas";
       		$sqlc .= " where workid ='$workid'";
       		$sql_resultc = mysql_query($sqlc);
       		$rowc = mysql_fetch_array($sql_resultc);
       		$wrknm = $rowc['workname'];
			
			$totalqty = 0;
			$totalqty = ($qtydoz *12) + $qtypcs;
		    	
		    $sqliq  = " Insert Into tmprptbarcode (ticketno, prod_code, prod_jobsec, prod_jobid, ";
        	$sqliq .= "  prod_jobrate, barcodeno, workid, sewdate, qtydoz, qtypcs, usernm, widnm) ";
        	$sqliq .= " Values ('$ticketno', '$prod_code', '$prod_jobsec', '$prod_jobid', '$prod_jobrate', ";
        	$sqliq .= "   '$barcodeno', '$workid', '$sewdate', '$totalqty', '$qtypcs', '$var_loginid', '$wrknm')";
			//echo $sqliq;
        	mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
		 }

     	 $fname = "payrptid.rptdesign&__title=myReport"; 
         $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&usernm=".$var_loginid."&dbsel=".$varrpturldb."&fdte=".$fdte."&tdte=".$tdte."&fwid=".$fwid."&twid=".$twid;
         $dest .= urlencode(realpath($fname));

         //header("Location: $dest" );
         echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
         $backloc = "../payrpt/payrpt_id.php?menucd=".$var_menucode;
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

	document.InpRawOpen.rptofdte.focus();
	
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
	var x=document.forms["InpRawOpen"]["rptofdte"].value;
	if (x==null || x=="")
	{
		alert("Sewing From Date Cannot Be Blank");
		document.InpRawOpen.rptofdte.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["rptotdte"].value;
	if (x==null || x=="")
	{
		alert("Sewing To Date Cannot Be Blank");
		document.InpRawOpen.rptotdte.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["selfjid"].value;
	if (x==null || x=="")
	{
		alert("From Job ID Cannot Be Blank");
		document.InpRawOpen.selfjid.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["seltjid"].value;
	if (x==null || x=="")
	{
		alert("To Job ID Cannot Be Blank");
		document.InpRawOpen.seltjid.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["selfwd"].value;
	if (x==null || x=="")
	{
		alert("From Worker ID Cannot Be Blank");
		document.InpRawOpen.selfwd.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["seltwd"].value;
	if (x==null || x=="")
	{
		alert("To Worker ID Cannot Be Blank");
		document.InpRawOpen.seltwd.focus();
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
        alert("Sew To Date Must Larger Then Sew From Date");
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
	 <legend class="title">PRODUCTION PAYROLL REPORT BY ID</legend>
	  <br />
	  <form name="InpRawOpen" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 139px; width: 750px;" onSubmit= "return chkSubmit()">
		<table style="width: 807px; height: 102px;">
	  	    <tr>
	  	    	<td style="width: 6px"></td>
	  	    	<td style="width: 140px" class="tdlabel">From Sewing Date</td>
	  	    	<td>:</td> 
	  	    	<td style="width: 134px">
					<input class="inputtxt" name="rptofdte" id ="rptofdte" type="text" value="<?php  echo date("01-m-Y"); ?>" style="width: 100px" />
					<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptofdte','ddMMyyyy')" style="cursor:pointer" />
				</td>
				<td style="width: 27px"></td>
				<td style="width: 109px">To Sewing Date</td>
				<td>:</td>
				<td>
					<input class="inputtxt" name="rptotdte" id ="rptotdte" type="text" value="<?php  echo date("t-m-Y"); ?>" style="width: 100px" />
					<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptotdte','ddMMyyyy')" style="cursor:pointer" />
				</td>
		    </tr>
		    <tr><td></td></tr>
		    <tr>
		    	<td></td>
		    	<td>From Job ID</td>
		    	<td>:</td>
		    	<td>
		    		<select name="selfjid" id ="selfjid" style="width: 100px">
			   		<?php
                   		$sql = "select jobfile_id, jobfile_desc from jobfile_master ORDER BY jobfile_id";
                   		$sql_result = mysql_query($sql);
                   		echo "<option size =30 selected></option>";
                       
				   		if(mysql_num_rows($sql_result)) 
				   		{
				   			while($row = mysql_fetch_assoc($sql_result)) 
				   			{ 
					  			echo '<option value="'.$row['jobfile_id'].'">'.$row['jobfile_id']." | ".$row['jobfile_desc'].'</option>';
				 	 		} 
				   		}
	            	?>				   
			  		</select>
		    	</td>
		    	<td></td>
		    	<td>To Job ID</td>
		    	<td>:</td>
		    	<td>
		    		<select name="seltjid" id ="seltjid" style="width: 100px">
			   		<?php
                   		$sql = "select jobfile_id, jobfile_desc from jobfile_master ORDER BY jobfile_id";
                   		$sql_result = mysql_query($sql);
                   		echo "<option size =30 selected></option>";
                       
				   		if(mysql_num_rows($sql_result)) 
				   		{
				   			while($row = mysql_fetch_assoc($sql_result)) 
				   			{ 
					  			echo '<option value="'.$row['jobfile_id'].'">'.$row['jobfile_id']." | ".$row['jobfile_desc'].'</option>';
				 	 		} 
				   		}
	            	?>				   
			  		</select>
		    	</td>
		    </tr>
		    <tr><td></td></tr>
		    <tr>
		    	<td></td>
		    	<td>From Worker ID</td>
		    	<td>:</td>
		    	<td>
		    		<select name="selfwd" id ="selfwd" style="width: 150px">
			   		<?php
                   		$sql = "select workid, workname from wor_detmas ORDER BY workid";
                   		$sql_result = mysql_query($sql);
                   		echo "<option size =30 selected></option>";
                       
				   		if(mysql_num_rows($sql_result)) 
				   		{
				   			while($row = mysql_fetch_assoc($sql_result)) 
				   			{ 
					  			echo '<option value="'.$row['workid'].'">'.$row['workid']." | ".$row['workname'].'</option>';
				 	 		} 
				   		}
	            	?>				   
			  		</select>
		    	</td>
		    	<td></td>
		    	<td>To Worker ID</td>
		    	<td>:</td>
		    	<td>
		    		<select name="seltwd" id ="seltwd" style="width: 150px">
			   		<?php
                   		$sql = "select workid, workname from wor_detmas ORDER BY workid";
                   		$sql_result = mysql_query($sql);
                   		echo "<option size =30 selected></option>";
                       
				   		if(mysql_num_rows($sql_result)) 
				   		{
				   			while($row = mysql_fetch_assoc($sql_result)) 
				   			{ 
					  			echo '<option value="'.$row['workid'].'">'.$row['workid']." | ".$row['workname'].'</option>';
				 	 		} 
				   		}
	            	?>				   
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
	  	  <tr>
	  	    <td style="width: 6px"></td> 
	  	    <td colspan="3">&nbsp;</td>
	   	  </tr> 
	   	
	  	  <tr>
	  	   <td colspan="8" align="center">
	  	   <?php
	  	   		//include("../Setting/btnprint.php");
	  	   ?>
	  	   </td>
	  	  </tr>
	  	   <tr><td style="width: 6px"></td></tr>

	  	</table>
	   </form>	
	</fieldset>
	 </div>
    <div class="spacer"></div>
</body>

</html>
