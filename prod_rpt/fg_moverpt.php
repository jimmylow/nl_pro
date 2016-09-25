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
     	
     	#----------------Prepare Temp Table For Printing -----------------------------------
     	$sql  = " Delete From tmpfgmove where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
	
		$shardSize = 8000;
	 	$sqliq = "";		   			
	 	$k = 0;	
		$sql  = "SELECT sum(totalqty), item_code";
		$sql .= " FROM fg_tran";
    	$sql .= " where trxdate < '$fdte'";
    	$sql .= " and item_code between '$fjid' and '$tjid'";		
    	$sql .= " group by item_code";
		$rs_result = mysql_query($sql);

		while ($row = mysql_fetch_assoc($rs_result)) { 
		   	$bfqty     = $row['sum(totalqty)'];
		   	$prod_code = $row['item_code'];       	
       		if (empty($bfqty)){$bfqty = 0;}        	
       		
       		if ($bfqty <> 0){
        		if ($k % $shardSize == 0) {
        			if ($k != 0) {	  
        	    		mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
        			}
        			$sqliq = 'Insert Into tmpfgmove (usernm, prodcd, bfqty) Values ';
    			}
   				$sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$var_loginid', '$prod_code', '$bfqty')";
			 	$k = $k + 1;	
			}		
		 }
		 if (!empty($sqliq)){
		 	mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
		 }
	
		 $shardSize = 8000;
	  	 $sqliq = "";		   			
	 	 $k = 0;	
		 $sql  = "SELECT sum(totalqty), tran_type, item_code";
		 $sql .= " FROM fg_tran";
    	 $sql .= " where trxdate between '$fdte' and '$tdte'";
    	 $sql .= " and item_code between '$fjid' and '$tjid'";	
    	 $sql .= " group by tran_type, item_code";
		 $rs_result = mysql_query($sql);

		while ($row = mysql_fetch_assoc($rs_result)) { 
		   	$trqty     = $row['sum(totalqty)'];
		   	$trty      = $row['tran_type'];
		   	$prod_code = $row['item_code'];       	
       		if (empty($trqty)){$trqty = 0;}
       		
			$oqty = 0;   $iqty = 0;  $rqty = 0;  $aqty = 0;
			switch($trty){
			case "ISS":
				$oqty = 0;   $iqty = $trqty;  $rqty = 0;  $aqty = 0;
				break;
			case "OPB":
				$oqty = $trqty;   $iqty = 0;  $rqty = 0;  $aqty = 0;
				break;
			case "REC":
				$oqty = 0;   $iqty = 0;  $rqty = $trqty;  $aqty = 0;
				break;
			case "ADJ":
				$oqty = 0;   $iqty = 0;  $rqty = 0;  $aqty = $trqty;
				break;
			}        	
       		
        	if ($k % $shardSize == 0) {
        		if ($k != 0) {	  
        	   		mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
        		}
        		$sqliq = 'Insert Into tmpfgmove (usernm, prodcd, openqty, issqty, recqty, adjqty) Values ';
    		}
   			$sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$var_loginid', '$prod_code', '$oqty', '$iqty',
   			                                                   '$rqty', '$aqty')";
			$k = $k + 1;	
					
		 }
		 if (!empty($sqliq)){
		 	mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
		 }
	
     	 $fname = "fg_moverpt.rptdesign&__title=myReport"; 
     	 $fname .= "&fd=".$fdte."&td=".$tdte;
     	 $fname .= "&fpr=".$fjid."&tpr=".$tjid;
         $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&usernm=".$var_loginid."&dbsel=".$varrpturldb;
         $dest .= urlencode(realpath($fname));

         //header("Location: $dest" );
         echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
         $backloc = "../prod_rpt/fg_moverpt.php?menucd=".$var_menucode;
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
        alert("To Date Must Larger Then From Date");
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
	<fieldset name="Group1" style=" width: 808px;" class="style2">
	 <legend class="title">FG MOVEMENT REPORT</legend>
	  <br />
	  <form name="InpRawOpen" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 139px; width: 750px;" onSubmit= "return chkSubmit()">
		<table style="width: 807px;">
	  	    <tr>
	  	    	<td style="width: 6px"></td>
	  	    	<td style="width: 180px" class="tdlabel">From Transaction Date</td>
	  	    	<td>:</td> 
	  	    	<td style="width: 134px">
					<input class="inputtxt" name="rptofdte" id ="rptofdte" type="text" value="<?php  echo date("01-m-Y"); ?>" style="width: 100px" />
					<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptofdte','ddMMyyyy')" style="cursor:pointer" />
				</td>
				<td style="width: 27px"></td>
				<td style="width: 180px">To Transaction Date</td>
				<td>:</td>
				<td>
					<input class="inputtxt" name="rptotdte" id ="rptotdte" type="text" value="<?php  echo date("t-m-Y"); ?>" style="width: 100px" />
					<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptotdte','ddMMyyyy')" style="cursor:pointer" />
				</td>
		    </tr>
		    <tr><td></td></tr>
		    <tr>
		    	<td></td>
		    	<td>From Product code</td>
		    	<td>:</td>
		    	<td>
		    		<select name="selfjid" id ="selfjid" style="width: 100px">
			   		<?php
                   		$sql = "select prod_code from pro_cd_master where actvty != 'D' ORDER BY prod_code";
                   		$sql_result = mysql_query($sql);                       
				   		if(mysql_num_rows($sql_result)) 
				   		{
				   			while($row = mysql_fetch_assoc($sql_result)) 
				   			{ 
					  			echo '<option value="'.$row['prod_code'].'">'.$row['prod_code'].'</option>';
				 	 		} 
				   		}
	            	?>				   
			  		</select>
		    	</td>
		    	<td></td>
		    	<td>To Product code</td>
		    	<td>:</td>
		    	<td>
		    		<select name="seltjid" id ="seltjid" style="width: 100px">
			   		<?php
                   		$sql = "select prod_code from pro_cd_master where actvty != 'D' ORDER BY prod_code";
                   		$sql_result = mysql_query($sql);
                       
				   		if(mysql_num_rows($sql_result)) 
				   		{
				   			while($row = mysql_fetch_assoc($sql_result)) 
				   			{ 
					  			echo '<option value="'.$row['prod_code'].'">'.$row['prod_code'].'</option>';
				 	 		} 
				   		}
	            	?>				   
			  		</select>
		    	</td>
		    </tr>
			<tr><td></td></tr>
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
