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
     	$fbuy = $_POST['selfbuy'];
     	$tbuy = $_POST['seltbuy'];

     	 
     	#----------------Prepare Temp Table For Printing -----------------------------------
     	$sql  = " Delete From tmpticrptyp where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
		
		$shardSize = 7000;
	 	$sqliq = "";		   			
	 	$k = 0;
		$sql  = "SELECT x.ticketno, x.sewtype, x.productcode, x.productqty, x.areacd, y.pro_cat, y.prod_desc, y.cost_pcsprice, y.prod_type, x.buyer";
		$sql .= " FROM sew_entry x, pro_cd_master y";
    	$sql .= " where x.productiondate between '$fdte' and '$tdte'";
    	$sql .= " and   x.productcode = y.prod_code";
    	$sql .= " and   x.buyer between '$fbuy' and '$tbuy'";
    	$sql .= " Order BY x.ticketno";
		$rs_result = mysql_query($sql);

		while ($row = mysql_fetch_assoc($rs_result)) { 

		   	$ticketno      = $row['ticketno'];
		    $sewtype       = $row['sewtype'];
		   	$productcode   = $row['productcode'];
		   	$productqty    = $row['productqty'];
		   	$area          = $row['area'];
		   	$pro_cat       = $row['pro_cat'];
		   	$prod_desc     = mysql_real_escape_string($row['prod_desc']);
		   	$cost_pcsprice = $row['cost_pcsprice'];
		   	$prdtyp        = $row['prod_type'];
			$buyer         = $row['buyer'];
		   	if (empty($productqty)){$productqty = 0;}
		   	if (empty($cost_pcsprice)){$cost_pcsprice = 0;}
		   	       			    	
		    if ($k % $shardSize == 0) {
       			if ($k != 0) {	
				
        	   		mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
       			}
       			$sqliq = 'Insert Into tmpticrptyp values';
    		}
   			$sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$pro_cat', '$ticketno', '$area', '$productcode', '$productqty', '$cost_pcsprice',
															   '$var_loginid', '$sewtype', '$buyer')";
			$k = $k + 1;
		 }
		 if (!empty($sqliq)){
			mysql_query($sqliq) or die ("Cant insert 2 : ".mysql_error());
		 }

     	 $fname = "ticrptsum.rptdesign&__title=myReport"; 
         $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&usernm=".$var_loginid."&dbsel=".$varrpturldb."&fdte=".$fdte."&tdte=".$tdte;
         $dest .= urlencode(realpath($fname));

         //header("Location: $dest" );
         echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
         $backloc = "../prod_rpt/ticfile_sum.php?menucd=".$var_menucode;
       	 echo "<script>";
       	 //echo 'location.replace("'.$backloc.'")';
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
		alert("From Production Date Cannot Be Blank");
		document.InpRawOpen.rptofdte.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["rptotdte"].value;
	if (x==null || x=="")
	{
		alert("To Production Date Cannot Be Blank");
		document.InpRawOpen.rptotdte.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["selfbuy"].value;
	if (x==null || x=="")
	{
		alert("From Buyer Cannot Be Blank");
		document.InpRawOpen.selfbuy.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["seltbuy"].value;
	if (x==null || x=="")
	{
		alert("To Buyer Cannot Be Blank");
		document.InpRawOpen.selfbuy.focus();
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
	<fieldset name="Group1" style=" width: 758px; height: 130px;" class="style2">
	 <legend class="title">TICKET FILE REPORT SUMMARY</legend>
	  <br />
	  <form name="InpRawOpen" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onSubmit= "return chkSubmit()">
		<table style="width: 807px; height: 102px;">	
			 <tr>
		    	<td></td>
		    	<td>From Prod Date</td>
		    	<td>:</td>
		    	<td>
		    		<input class="inputtxt" name="rptofdte" id ="rptofdte" type="text" value="<?php  echo date("01-m-Y"); ?>" style="width: 100px" />
					<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptofdte','ddMMyyyy')" style="cursor:pointer" />
		    	</td>
		    	<td></td>
		    	<td>To Prod Date</td>
		    	<td>:</td>
		    	<td>		
					<input class="inputtxt" name="rptotdte" id ="rptotdte" type="text" value="<?php  echo date("t-m-Y"); ?>" style="width: 100px" />
					<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptotdte','ddMMyyyy')" style="cursor:pointer" />		    	
				</td>
		    </tr>
			 <tr>
		    	<td></td>
		    	<td>From Buyer</td>
		    	<td>:</td>
		    	<td style="width: 242px">
		    		<select name="selfbuy" id ="selfbuy" style="width: 240px">
			   		<?php
                   		$sql = "select customer_code, customer_desc from customer_master ORDER BY customer_code";
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
		    		<select name="seltbuy" id ="seltbuy" style="width: 240px">
			   		<?php
                   		$sql = "select customer_code, customer_desc from customer_master ORDER BY customer_code";
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
