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
     	$fstyp = $_POST['selfstyp'];
     	$tstyp = $_POST['seltstyp'];
     	$fbuy = $_POST['selfbuy'];
     	$tbuy = $_POST['seltbuy'];

     	 
     	#----------------Prepare Temp Table For Printing -----------------------------------
     	$sql  = " Delete From tmpsewaging where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());

		$shardSize = 3000;
	 	$sqliq = "";		   			
	 	$k = 0;
		$sql  = "SELECT areacd, sewtype, ticketno, creation_time, productcode, productqty";
		$sql .= " FROM sew_entry";
    	$sql .= " where creation_time between '$fdte' and '$tdte'";
    	$sql .= " and 	sewtype between '$fstyp' and '$tstyp'";
    	$sql .= " and   buyer between '$fbuy' and '$tbuy'";
    	$sql .= " and   qcqty = 0 and stat = 'ACTIVE'";
		$rs_result = mysql_query($sql);

		while ($row = mysql_fetch_assoc($rs_result)) { 

		   	$area          = $row['area'];
		   	$sewtype       = $row['sewtype'];
		   	$ticketno      = $row['ticketno'];
		   	$creation_time = $row['creation_time'];
		   	$productcode   = $row['productcode'];
		   	$productqty    = $row['productqty'];
		   	if (empty($productqty)){$productqty = 0;}
		   	
	   	 	$sqlc  = "select area_desc";
	    	$sqlc .= " from area_master";
       		$sqlc .= " where area_code ='$area'";
       		$sql_resultc = mysql_query($sqlc);
       		$rowc = mysql_fetch_array($sql_resultc);
       		$area_desc = mysql_real_escape_string($rowc['area_desc']);
       		
       		$sqlc  = "select sewtype_desc";
	    	$sqlc .= " from prosewtype_master";
       		$sqlc .= " where sewtype_code ='$sewtype'";
       		$sql_resultc = mysql_query($sqlc);
       		$rowc = mysql_fetch_array($sql_resultc);
       		$sewtype_desc = mysql_real_escape_string($rowc['sewtype_desc']);
		   	
		   	$sqlc  = "select pro_cat, cost_pcsprice, cost_dozprice";
	    	$sqlc .= " from pro_cd_master";
       		$sqlc .= " where prod_code ='$productcode'";
       		$sql_resultc = mysql_query($sqlc);
       		$rowc = mysql_fetch_array($sql_resultc);
       		$pro_cat= $rowc['pro_cat'];
		   	$cost_pcsprice = $rowc['cost_pcsprice'];
	   		$cost_dozprice = $rowc['cost_dozprice'];
		   	if (empty($cost_pcsprice)){$cost_pcsprice = 0;}
		   	if (empty($cost_dozprice)){$cost_dozprice = 0;}
		   	
		   	if ($cost_pcsprice == 0){
		   		$sqlc  = "select totcost";
	    		$sqlc .= " from prod_matmain";
       			$sqlc .= " where prod_code ='$productcode'";
       			$sql_resultc = mysql_query($sqlc);
       			$rowc = mysql_fetch_array($sql_resultc);
		   		$cost_pcsprice = $rowc['totcost'] / 12;
		   	}
		   	if ($cost_dozprice == 0){
		   		$sqlc  = "select totcost";
	    		$sqlc .= " from prod_matmain";
       			$sqlc .= " where prod_code ='$productcode'";
       			$sql_resultc = mysql_query($sqlc);
       			$rowc = mysql_fetch_array($sql_resultc);
		   		$cost_dozprice = $rowc['totcost'];
		   		if (empty($cost_dozprice)){$cost_dozprice = 0;}
		   	}
			$balamt = $productqty * $cost_pcsprice;
			if (empty($balamt)){$balamt = 0;}
		   	
			if ($k % $shardSize == 0) {
				if ($k != 0) {	  
					mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
				}
				$sqliq = 'Insert Into tmpsewaging values';
			}
			$sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$area', '$area_desc', '$sewtype', '$sewtype_desc', '$pro_cat', '$productcode',
															   '$ticketno', '$creation_time', '$cost_pcsprice', '$cost_dozprice','$productqty','$balamt', '$var_loginid', '')";
			$k = $k + 1;       			    	
		    //$sqliq  = " Insert Into tmpsewaging";
        	//$sqliq .= " Values ('$area', '$area_desc', '$sewtype', '$sewtype_desc', '$pro_cat', '$productcode',";
        	//$sqliq .= "         '$ticketno', '$creation_time', '$cost_pcsprice', '$cost_dozprice','$productqty', ";
        	//$sqliq .= "         '$balamt', '$var_loginid', '')";
        	//mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
		 }
		  if ($sqliq <> ""){
			mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
		 }


     	 $fname = "sewaging.rptdesign&__title=myReport"; 
         $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&usernm=".$var_loginid."&dbsel=".$varrpturldb."&fdte=".$fdte."&tdte=".$tdte."&ftyp=".$fstyp."&ttyp=".$tstyp;
         $dest .= urlencode(realpath($fname));

         //header("Location: $dest" );
         echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
         $backloc = "../prod_rpt/sewwip.php?menucd=".$var_menucode;
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
        alert("Ticket To Date Must Larger Then Ticket From Date");
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
	 <legend class="title">Work In Progress Aging Report</legend>
	  <br />
	  <form name="InpRawOpen" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 139px; width: 750px;" onSubmit= "return chkSubmit()">
		<table style="width: 807px; height: 102px;">
	  	    <tr>
	  	    	<td style="width: 6px"></td>
	  	    	<td style="width: 117px" class="tdlabel">From Sew Type</td>
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
		    <tr><td></td></tr>
			 <tr>
		    	<td style="height: 32px"></td>
		    	<td style="height: 32px; width: 117px">From Ticket Date</td>
		    	<td style="height: 32px">:</td>
		    	<td style="height: 32px">
		    		<input class="inputtxt" name="rptofdte" id ="rptofdte" type="text" value="<?php  echo date("01-m-Y"); ?>" style="width: 100px" />
					<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptofdte','ddMMyyyy')" style="cursor:pointer" />
		    	</td>
		    	<td style="height: 32px"></td>
		    	<td style="height: 32px">To Ticket Date</td>
		    	<td style="height: 32px">:</td>
		    	<td style="height: 32px">		
					<input class="inputtxt" name="rptotdte" id ="rptotdte" type="text" value="<?php  echo date("t-m-Y"); ?>" style="width: 100px" />
					<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptotdte','ddMMyyyy')" style="cursor:pointer" />		    	
				</td>
		    </tr>
			<tr><td></td></tr>
	  	  	<tr>
		    	<td></td>
		    	<td style="width: 117px">From Buyer</td>
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
