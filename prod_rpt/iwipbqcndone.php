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
     	$fjid  = $_POST['selfjid'];
     	$tjid  = $_POST['seltjid'];
     	 
     	 /*
     	#----------------Prepare Temp Table For Printing -----------------------------------
     	$sql  = " Delete From tmpjidscr where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());

		$sql  = "SELECT prod_jobid, x.ticketno, x.prod_code, productqty";
		$sql .= " FROM sew_barcode x, sew_entry y";
    	$sql .= " where x.ticketno = y.ticketno";
    	$sql .= " and 	prod_jobid between '$fjid' and '$tjid'";
    	$sql .= " and   x.workid = '' and y.qcqty = 0";
    	$sql .= " order by 1";
		$rs_result = mysql_query($sql);

		while ($row = mysql_fetch_assoc($rs_result)) { 

		   	$prod_jobid = $row['prod_jobid'];
		   	$ticketno   = $row['ticketno'];
		   	$prod_code  = $row['prod_code'];
		   	$productqty = $row['productqty'];
		   	if (empty($productqty)){$productqty = 0;}
		   	
	   	 	$sqlc  = "select jobfile_desc";
	    	$sqlc .= " from jobfile_master";
       		$sqlc .= " where jobfile_id ='$prod_jobid'";
       		$sql_resultc = mysql_query($sqlc);
       		$rowc = mysql_fetch_array($sql_resultc);
       		$jobfile_desc = mysql_real_escape_string($rowc['jobfile_desc']);
		   	       			    	
		    $sqliq  = " Insert Into tmpjidscr";
        	$sqliq .= " Values ('$var_loginid', '$prod_jobid', '$jobfile_desc', '$ticketno', '$prod_code', '$productqty')";
        	mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
		 }
		*/	
     	 $fname = "iwipbqcndone.rptdesign&__title=myReport"; 
         $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&usernm=".$var_loginid."&dbsel=".$varrpturldb."&fd=".$fjid."&td=".$tjid;
         $dest .= urlencode(realpath($fname));

         //header("Location: $dest" );
         echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
         $backloc = "../prod_rpt/iwipbqcndone.php?menucd=".$var_menucode;
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

	document.InpRawOpen.selfjid.focus();
									
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
}
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
	<div class="contentc">
	<fieldset name="Group1" style=" width: 758px; height: 205px;" class="style2">
	 <legend class="title">INCOMPLETE WORK IN PROGRESS BEFORE QC AND WORK DONE</legend>
	  <br />
	  <form name="InpRawOpen" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 139px; width: 750px;" onsubmit= "return chkSubmit()">
		<table style="width: 807px; height: 102px;">
	  	    <tr>
	  	    	<td style="width: 6px"></td>
	  	    	<td style="width: 140px" class="tdlabel">From Job ID</td>
	  	    	<td>:</td> 
	  	    	<td style="width: 134px">
					<select name="selfjid" id ="selfjid" style="width: 100px">
			   		<?php
                   		$sql = "select jobfile_id, jobfile_desc from jobfile_master where actvty = 'A' ORDER BY jobfile_id";
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
				<td style="width: 27px"></td>
				<td style="width: 109px">To Job ID</td>
				<td>:</td>
				<td>
					<select name="seltjid" id ="seltjid" style="width: 100px">
			   		<?php
                   		$sql = "select jobfile_id, jobfile_desc from jobfile_master where actvty = 'A' ORDER BY jobfile_id";
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
