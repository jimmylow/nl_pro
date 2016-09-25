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
     	$sql  = " DELETE FROM tmpcutitm where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table tmpcutitm To Insert Item ".mysql_error());
        
        $shardSize = 5000;
	 	$sqlin = "";		   			
	 	$k = 0;
      
   		$sql  = " SELECT buyno, ordno, prodcat,prodcnum, colno, sizeno ";
		$sql .= " FROM prodcutmas";
    	$sql .= " where buyno between '$fbuyno' and '$tbuyno'";
    	$sql .= " Order BY buyno, ordno ";
		$rs_result = mysql_query($sql);
		

		while ($row = mysql_fetch_assoc($rs_result)) { 
			$buyno    = $row['buyno'];
		   	$ordno    = $row['ordno'];
		   	$prodcat  = $row['prodcat'];
		   	$prodcnum = $row['prodcnum'];
		   	$colno    = $row['colno'];
		   	$sizeno   = $row['sizeno'];
		   	
		   	$itemcode = '';
		   	$itemcode = $prodcat. $prodcnum. '-'. $colno.'-'.$sizeno;
		   	
		    //$sqlin  = " Insert Into tmpcutitm (buyer, sordno, itemcode, usernm) ";
        	//$sqlin .= " Values ('$buyno', '$ordno','$itemcode','$var_loginid')";
        	//echo $sqlin;
        	//mysql_query($sqlin) or die("Unable Save In Temp Table 1 ".mysql_error());
        	
        	if ($k % $shardSize == 0) {
        		if ($k != 0) {

            		mysql_query($sqlin) or die("Unable Save In Temp Table ".mysql_error());
        		}
        		$sqlin = 'Insert Into tmpcutitm (buyer, sordno, itemcode, usernm) values ';
    		}
   			$sqlin .= (($k % $shardSize == 0) ? '' : ', ') . "('$buyno', '$ordno','$itemcode','$var_loginid')";
		 	$k = $k + 1;
        	
		}
		 if ($sqlin <> ""){
		 mysql_query($sqlin) or die("Unable Save In Temp Table 1".mysql_error());
		 }



     	#----------------Prepare Temp Table For Printing -----------------------------------
     	$sql  = " DELETE FROM tmpbuyervscut where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table tmpbuyervscut For Printing ".mysql_error());
		$shardSize = 5000;
	 	$sqliq = "";		   			
	 	$k = 0;

		$sql  = "SELECT x.sordno, x.sbuycd, x.sprocd, x.sproqty, x.sprounipri, y.sorddte, y.sexpddte, y.sordnobuyer ";
		$sql .= " FROM salesentrydet x, salesentry  y";
    	$sql .= " where x.sordno not in (select distinct sordno from tmpcutitm where usernm = '$var_loginid')";
    	$sql .= " AND x.sordno = y.sordno ";
    	$sql .= " AND x.sbuycd between '$fbuyno' and '$tbuyno' ";
    	//$sql .= "  and x.sordno = 'FNL 2059' ";
    	
    	$sql .= " AND y.sorddte BETWEEN '$fdte' AND '$tdte' ";
     	$sql .= " AND stat = 'ACTIVE' ";
    	
    	$sql .= " ORDER BY x.sbuycd, y.sorddte, x.sordno,  x.sproseq ";
    	//echo $sql; break;
		$rs_result = mysql_query($sql);
		

		while ($row = mysql_fetch_assoc($rs_result)) { 
		   	$sordno       = $row['sordno'];
		   	$sbuycd       = $row['sbuycd'];
		   	$sprocd       = $row['sprocd'];
		   	$sproqty      = $row['sproqty'];
		   	$sprounipri   = $row['sprounipri'];   	
       		//$sorddte     = $rowc['sorddte'];
       		//$sexpddte    = $rowc['sexpddte'];
       		$sordnobuyer = $rowc['sordnobuyer'];
       		$sorddte = date('Y-m-d', strtotime($row['sorddte']));
       		$sexpddte= date('Y-m-d', strtotime($row['sexpddte']));

       		
       		$amt = 0;
       		$amt = $sproqty * $sprounipri;
        	//$sqliq  = " Insert Into tmpbuyervscut ";
        	//$sqliq .= " Values ('$sbuycd', '$sordno', '$sordnobuyer', '$sexpddte', ";
        	
        	//$sqliq .= "   '$sorddte', '$sprocd', '$sproqty', '$sprounipri', '$amt','$var_loginid')";
        	//echo $sqliq; break;
        	//mysql_query($sqliq) or die("Unable Save In Temp Table tmpbuyervscut ".mysql_error());
        	
        	
        	if ($k % $shardSize == 0) {
        		if ($k != 0) {

            		mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
        		}
        		$sqliq = 'Insert Into tmpbuyervscut values ';
    		}
   			$sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$sbuycd', '$sordno', '$sordnobuyer', '$sexpddte', 
        														  '$sorddte', '$sprocd', '$sproqty', '$sprounipri', '$amt','$var_loginid')";
		 	$k = $k + 1;
		 	

		 }
		  if ($sqliq <> ""){
		 
		 mysql_query($sqliq) or die("Unable Save In Temp Table 1".mysql_error());
		 }


		 

     	 $fname = "sales_vs_cut.rptdesign&__title=myReport"; 
         $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&usernm=".$var_loginid."&dbsel=".$varrpturldb."&fbuyno=".$fbuyno."&tbuyno=".$tbuyno;
         echo $desc;
         $dest .= urlencode(realpath($fname));

         //header("Location: $dest" );
         echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
         $backloc = "../sales_tran/sales_vs_cut.php?menucd=".$var_menucode;
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
	 <legend class="title">BUYER ORDER VS CUTTING</legend>
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
