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
     
     	$sql = "Delete From tmpcutdetrpt where usernm = '$var_loginid'";
     	mysql_query($sql) or die("Unable Prepare Temp Table ".mysql_error());
     
     	$sbuyno = $_POST['selobuy'];
     	$sgrpno = $_POST['selcgrp'];
     	$frdte  = date("Y-m-d", strtotime($_POST['cfdte']));
     	$trdte  = date("Y-m-d", strtotime($_POST['ctdte']));
     	
     	if ($sbuyno == 'A'){$sbuyno = '%';}
     	if ($sgrpno == 'A'){$sgrpno = '%';}
     	
    	$sqlm  = "select count(*)";
	    $sqlm .= " from prodcutmas";
       	$sqlm .= " where cutdte between '$frdte' and '$trdte'";
		$sqlm .= " and buyno like '$sbuyno'";
		$sqlm .= " and grpno like '$sgrpno'";
		$sqlm .= " and status != 'D'";
     	$sql_resultm = mysql_query($sqlm);
        $rowm = mysql_fetch_array($sql_resultm);
        $cnt  = $rowm[0];

		if($cnt == 0){
			echo "<script>";   
      		echo "alert('No Data Found On Selected Query');"; 
      		echo "</script>";
		}else{
		
			$sqlm  = "select *";
	    	$sqlm .= " from prodcutmas";
       		$sqlm .= " where cutdte between '$frdte' and '$trdte'";
			$sqlm .= " and buyno like '$sbuyno'";
			$sqlm .= " and grpno like '$sgrpno'";
			$sqlm .= " and status != 'D'";
			$rs_result = mysql_query($sqlm);

			while ($row = mysql_fetch_assoc($rs_result)) {
				$cutno = $row['cutno'];
		    	$cutdt = $row['cutdte'];
		    	$ordno = $row['ordno']; 
		    	$buyno = $row['buyno'];
		       	$orddt = $row['orddte'];
		       	$grpno = $row['grpno'];
		       	$cuqty = $row['ordqty'];
		       	$qtuom = $row['prodnouom'];
		       	$custa = $row['status'];
		       	$pcdca = $row['prodcat'];
		       	$pcdnu = $row['prodcnum'];
		       	$pcdcl = $row['colno'];
		        $pcdsz = $row['sizeno'];
		        $prdno = $pcdca.$pcdnu."-".$pcdcl."-".$pcdsz;
		       	
		       	if (empty($cuqty)){$cuqty = 0;}
		       	
		    	$sqliq  = " Insert Into tmpcutdetrpt (cutno, cutdte, ordno, buyno, ";
        		$sqliq .= "  orddte, grpno, ordqty, prodnouom, stat, usernm, prodcode) ";
        		$sqliq .= " Values ('$cutno', '$cutdt', '$ordno', '$buyno', '$orddt',";
        		$sqliq .= "   '$grpno', '$cuqty', '$qtuom', '$custa', '$var_loginid', '$prdno')";	
        		mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());	
			}
			
		
     		// Redirect browser
			$fname = "cut_det_rpt.rptdesign&__title=myReport"; 
        	$dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&by=".$sbuyno."&gr=".$sgrpno."&fd=".$frdte."&td=".$trdte."&usernm=".$var_loginid."&dbsel=".$varrpturldb;
        	echo $desc;
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

	document.InpCutRpt.selobuy.focus();
						
 	//Set up the date parsers
    var dateParser = new DateParser("dd-MM-yyyy");
      
	//Set up the DateMasks
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var dateMask1 = new DateMask("dd-MM-yyyy", "cfdte");
	dateMask1.validationMessage = errorMessage;
	
	var dateMask1 = new DateMask("dd-MM-yyyy", "ctdte");
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
	var x=document.forms["InpCutRpt"]["cfdte"].value;
	if (x==null || x=="")
	{
		alert("From Cutting Date Cannot Be Blank");
		document.InpCutRpt.cfdte.focus();
		return false;
	}
	
	var x=document.forms["InpCutRpt"]["ctdte"].value;
	if (x==null || x=="")
	{
		alert("To Cutting Date Cannot Be Blank");
		document.InpCutRpt.ctdte.focus();
		return false;
	}
	
	var x=document.forms["InpCutRpt"]["cfdte"].value;
	var y=document.forms["InpCutRpt"]["ctdte"].value;
	
    var fromdate = x.split('-');
        from_date = new Date();
        from_date.setFullYear(fromdate[2],fromdate[1]-1,fromdate[0]); 
    
    var todate = y.split('-');
        to_date = new Date();
        to_date.setFullYear(todate[2],todate[1]-1,todate[0]);
        
    if (from_date > to_date ) 
    {
        alert("Cutting To Date Must Larger Then Cutting From Date");
		document.InpCutRpt.cfdte.focus();
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
	 <legend class="title">CUTTING JOB DETAIL REPORT</legend>
	  <form name="InpCutRpt" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 583px;">
		<table style="width: 620px">
		  <tr>
		  	<td style="width: 10px"></td>
		  	<td style="width: 132px">Buyer Code</td>
		  	<td style="width: 2px">:</td>
		  	<td style="width: 134px">
		  		<select name="selobuy" id ="selobuy" style="width: 108px">
			    <?php
                   $sql = "select distinct pro_buy_code, pro_buy_desc from pro_buy_master ORDER BY pro_buy_code";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected value='A'>A | All Buyer Code</option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['pro_buy_code'].'">'.$row['pro_buy_code']." | ".$row['pro_buy_desc'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>
		  	</td>
		  </tr>
		  <tr><td style="width: 10px"></td></tr>
		  <tr>
		  	<td style="width: 10px"></td>
		  	<td style="width: 132px">Group Code</td>
		  	<td style="width: 2px">:</td>
		  	<td style="width: 134px">
		  		<select name="selcgrp" id ="selcgrp" style="width: 139px">
			    <?php
                   $sql = "select grpcd, grpde from wor_grpmas ORDER BY grpcd";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected value='A'>A | All Group Code</option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['grpcd'].'">'.$row['grpcd']." | ".$row['grpde'].'</option>';
				 	 } 
				   }
	            ?>				   
			  </select>
		  	</td>
		  </tr>
		  <tr><td style="width: 10px"></td></tr>	
	  	  <tr>
	  	    <td style="width: 10px"></td>
	  	    <td style="width: 132px" class="tdlabel">From Cut Date</td>
	  	    <td style="width: 2px">:</td> 
	  	    <td style="width: 400px">
				<input class="inputtxt" name="cfdte" id ="cfdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('cfdte','ddMMyyyy')" style="cursor:pointer">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
				To Cut Date:
				<input class="inputtxt" name="ctdte" id ="ctdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('ctdte','ddMMyyyy')" style="cursor:pointer">
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
