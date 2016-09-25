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
 		$fwid = $_POST['selfwd'];
     	$twid = $_POST['seltwd'];
     	 
     	#----------------Prepare Temp Table For Printing -----------------------------------
     	$sql  = " Delete From tmpwrkrpt01 where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
		
		$shardSize = 2000;
	 	$sqliq = "";		   			
	 	$k = 0;
		$sql  = "SELECT distinct workid, prod_jobid, prod_jobrate";
		$sql .= " FROM sew_barcode";
    	$sql .= " where workid between '$fwid' and '$twid'";
    	$sql .= " Order BY workid, prod_jobid";
		$rs_result = mysql_query($sql);

		while ($row = mysql_fetch_assoc($rs_result)) { 
		   	$workid       = $row['workid'];
		   	$prod_jobid   = $row['prod_jobid'];
		   	$prod_jobrate = $row['prod_jobrate'];
		   	
		   	$sqlc  = "select jobfile_desc";
	    	$sqlc .= " from jobfile_master";
       		$sqlc .= " where jobfile_id ='$prod_jobid'";
       		$sql_resultc = mysql_query($sqlc);
       		$rowc = mysql_fetch_array($sql_resultc);
       		$jobfile_desc  = $rowc['jobfile_desc'];
		   	
		   	$sqlc  = "select workname, deptcd, deptde, payratecd";
	    	$sqlc .= " from wor_detmas";
       		$sqlc .= " where workid ='$workid'";
       		$sql_resultc = mysql_query($sqlc);
       		$rowc = mysql_fetch_array($sql_resultc);
       		$wrknm  = $rowc['workname'];
       		$deptcd = $rowc['deptcd'];
       		$deptde = $rowc['deptde'];
       		$payratecd = $rowc['payratecd'];
       		
       		$sqlc  = "select paydesc";
	    	$sqlc .= " from wor_payrate";
       		$sqlc .= " where paycode ='$payratecd'";
       		$sql_resultc = mysql_query($sqlc);
       		$rowc = mysql_fetch_array($sql_resultc);
       		$paydesc  = $rowc['paydesc'];

		    	
		    //$sqliq  = " Insert Into tmpwrkrpt01 (usernm, wrkid, wrknm, deptid, ";
        	//$sqliq .= "  deptnm, payrid, payrdesc, jobid, jobdesc, jobrate) ";
        	//$sqliq .= " Values ('$var_loginid', '$workid', '$wrknm', '$deptcd', '$deptde', ";
        	//$sqliq .= "   '$payratecd', '$paydesc', '$prod_jobid ', '$jobfile_desc', '$prod_jobrate')";
        	if ($k % $shardSize == 0) {
        		if ($k != 0) {	  
            		mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
        		}
        		$sqliq = 'Insert Into tmpwrkrpt01 Values ';
    		}
   			$sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$var_loginid', '$workid', '$wrknm', '$deptcd', '$deptde', '$payratecd', '$paydesc', '$prod_jobid ', '$jobfile_desc', '$prod_jobrate')";
		 	$k = $k + 1;	
		 }
		 mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error()); 
		 mysql_close($db_link); 	
     	 $fname = "wrkrpt01.rptdesign&__title=myReport"; 
         $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&usernm=".$var_loginid."&dbsel=".$varrpturldb."&fwid=".$fwid."&twid=".$twid;
         $dest .= urlencode(realpath($fname));

         //header("Location: $dest" );
         echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
         $backloc = "../wrkrpt/wrk_rpt01.php?menucd=".$var_menucode;
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

	document.InpRawOpen.selfwd.focus();
									
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

}
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
	<div class="contentc">
	<fieldset name="Group1" style=" width: 758px; height: 205px;" class="style2">
	 <legend class="title">WORKER REPORT BY ID</legend>
	  <br />
	  <form name="InpRawOpen" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 139px; width: 750px;" onSubmit= "return chkSubmit()">
		<table style="width: 807px; height: 102px;">
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
