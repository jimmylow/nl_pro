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
     //echo 'kkk'; break;
 		$fbuyno = $_POST['selfwd'];
     	$tbuyno = $_POST['seltwd'];
     	
     	$fdte = date('Y-m-d', strtotime($_POST['rptofdte']));
     	$tdte = date('Y-m-d', strtotime($_POST['rptotdte']));

     	
     	#----------------Prepare Temp Table To Insert The Item Code-----------------------------------
     	$sql  = " DELETE FROM tmp_inv_monthly_rpt where username = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table tmp_inv_monthly_rpt To Insert Item ".mysql_error());
        
        $sqlin = "";		   			
	 	      
	 	$sql  = "select sysprocd, buyprocd, s.buycd, s.invno, qtys, unitpri, amts, invdte, descript, uoms";
	 	$sql .= " from ship_invdet si";
	 	$sql .= " inner join ship_invmas s ON s.invno = si.invno";
	 	$sql .= " where buycd between '$fbuyno' and '$tbuyno'";
	 	$sql .= " AND s.invdte BETWEEN '$fdte' AND '$tdte' ";
		$rs_result = mysql_query($sql);
		
		while ($row = mysql_fetch_assoc($rs_result)) {
            $sysprocd = $row['sysprocd'];
		    $buyprocd = $row['buyprocd'];
		    $buycd = $row['buycd'];
		    $invno = $row['invno'];
		    $qtys  = $row['qtys'];
		    $unitpri = $row['unitpri'];
		    $amts = $row['amts'];
		    $invdte = $row['invdte'];
		    $descript = $row['descript'];
		    $uoms = $row['uoms'];
		    
		    if (empty($sysprocd)) {
		        $itemcode = $buyprocd;
		    }
            else {
                $itemcode = $sysprocd;
            }
            $sqlin = "Insert Into tmp_inv_monthly_rpt (inv_no, item_code, description, ship_date, buyer, ship_qty, item_uom, unit_price, total_amt, username) values ";
            $sqlin .=  "('$invno', '$itemcode','$descript','$invdte','$buycd','$qtys','$uoms','$unitpri','$amts','$var_loginid')";        
            mysql_query($sqlin) or die("Unable Save In Temp Table ".mysql_error());
    	
		}
		
     	 $fname = "invoicing_monthly_rpt.rptdesign&__title=myReport"; 
     	 $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&usernm=".$var_loginid."&dbsel=".$varrpturldb."&dateFrom=".$fdte."&dateTo=".$tdte;
         echo $desc;
         $dest .= urlencode(realpath($fname));

         //header("Location: $dest" );
         echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
         /* $backloc = "../sales_tran/invoicing_monthly_report.php?menucd=".$var_menucode;
       	 echo "<script>";
       	 echo 'location.replace("'.$backloc.'")';
       	 echo "</script>";    */     	
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
	
	if ($vminvoicedte == "") { $vminvoicedte = date("t-m-Y"); } 
 	//Set up the date parsers
    var dateParser = new DateParser("dd-MM-yyyy");
    //alert('xxx');
  
	//Set up the DateMasks
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var dateMask1 = new DateMask("dd-MM-yyyy", "invoicedte");
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
	 <legend class="title">INVOICING MONTHLY REPORT</legend>
	  <br />
	  <form name="InpRawOpen" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 139px; width: 750px;" onSubmit= "return chkSubmit()">
		<table style="width: 807px; height: 102px;">
		    <tr>
		    	<td></td>
		    	<td>From Buyer</td>
		    	<td>:</td>
		    	<td>
		    		<select name="selfwd" id ="selfwd" style="width: 150px">
			 <?php
              $sql = "select customer_code, customer_desc from customer_master ORDER BY customer_code ASC";
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
		    		<select name="seltwd" id ="seltwd" style="width: 150px">
			 <?php
              $sql = "select customer_code, customer_desc from customer_master ORDER BY customer_code ASC";
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
			<tr><td></td></tr>
	  	  	<tr>
		    	<td></td>
	  	    <td style="width: 140px" class="tdlabel">From Date</td>
	  	    <td>:</td> 
	  	    <td style="width: 134px">
				<input class="inputtxt" name="rptofdte" id ="rptofdte" type="text" value="<?php  echo date("01-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptofdte','ddMMyyyy')" style="cursor:pointer">
			</td>
			<td style="width: 27px"></td>
			<td style="width: 109px">To Date</td>
			<td>:</td>
			<td>
				<input class="inputtxt" name="rptotdte" id ="rptotdte" type="text" value="<?php  echo date("t-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptotdte','ddMMyyyy')" style="cursor:pointer">
			</td>

		    </tr>
	  	  	<tr>
	  	  		<td style="width: 6px">&nbsp;</td>
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
