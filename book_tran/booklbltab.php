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
    }else{
 		$itmc   = htmlentities($_GET['itmc']);	
		$vref   = htmlentities($_GET['ref']);	
      	$itmqty = $_GET['itmqt'];
      	$secopy = $_GET['secp'];
    }
    
     if ($_POST['Submit'] == "Print") {
   
   			$sql  = " Delete from tmpbook_lbl Where usernm = '$var_loginid'";
   			mysql_query($sql) or die("Unable To Delete Temp Table".mysql_error());

			#--------------------------------------Insert into barcode table	
			$itmc  = htmlentities($_POST['itemno']);	
			$vref  = htmlentities($_POST['refno']);	
      		$scopy = $_POST['copys'];
      		$toqty = $_POST['tqty'];
      		
      		$sql  = "Select bookdte, byrefno, buycd from booktab01";
      		$sql .= " where bookno = '$vref'";
      		$sql_result = mysql_query($sql) or die(mysql_error());
        	$row = mysql_fetch_array($sql_result);
        	$bookdte = $row[0];
        	$sordno  = $row[1];
        	$sbuyno  = $row[2];
      		
      		if(!empty($_POST['lblser']) && is_array($_POST['lblser'])) 
			{	
				foreach($_POST['lblser'] as $row=>$lblser){
					
					$lblqty = $_POST['lblqty'][$row];
					
        			$sql  = " Insert Into tmpbook_lbl (sub_code, bookno, refcopy, lblser, ";
        			$sql .= "  lblserqty, totq, usernm, sordno, bookdte, sbuyer)";
        			$sql .= " Values ('$itmc', '$vref', '$scopy', '$lblser',";
        			$sql .= "   '$lblqty', '$toqty', '$var_loginid', '$sordno', '$bookdte', '$sbuyno')";
        			mysql_query($sql) or die("Unable To Save The Serial For Printing".mysql_error());
        		}	
        	}		

        	$fname = "book_lblslip.rptdesign&__title=myReport"; 
        	$dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&dbsel=".$varrpturldb."&usernm=".$var_loginid;
        	$dest .= urlencode(realpath($fname));

        	//header("Location: $dest" );
        	echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        	echo "<script>";
       		echo 'window.close();';
        	echo "</script>"; 
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<style media="all" type="text/css">
@import "../css/styles.css";
@import "../css/demo_table.css";

</style>

<script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.nightly.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>

<script type="text/javascript"> 

function calccompNmix(){
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length - 1; 

	var totmqty = 0;
	for(var i = 1; i < rowCount; i++) { 

		 var vprocostid = "lblqty"+i;
		 var colmatucost = document.getElementById(vprocostid).value;
				
		 if (!isNaN(colmatucost) && (colmatucost != "")){
				totmqty = parseFloat(totmqty) + parseFloat(colmatucost);		
		 }
	}
	document.getElementById("tqty").value = parseFloat(totmqty).toFixed(2);
}

function getqty(qtyid){

    var x = document.getElementById(qtyid).value;
	
	if(isNaN(x)){
    	alert('Please Enter a valid number for Total Qty:' + x);
    	document.getElementById(qtyid).focus();
    }
    if(x <= 0) {
    	alert('Not Accept Negative Value Or Zero:' + x);
    	document.getElementById(qtyid).focus();
    }
    document.getElementById(qtyid).value = parseFloat(x).toFixed(2); 
    calccompNmix();
}

function newWindow(file,window) {
    msgWindow=open(file,window,'resizable=yes,scrollbars=yes,width=950,height=700');
    if (msgWindow.opener == null) msgWindow.opener = self;
}			
</script>
</head>

<body onload="document.LstDetMas.lblqty1.focus()">
     <form name="LstDetMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
     	<input name="itemno" type="hidden" value="<?php echo $itmc;?>">
     	<input name="refno" type="hidden" value="<?php echo $vref;?>">
		<input name="refopt" type="hidden" value="<?php echo $vrefd;?>">
		<input name="copys" type="hidden" value="<?php echo $secopy;?>">

     	<fieldset>
		 <table cellpadding="0" cellspacing="0" id="itemsTable" width="100%">
         <thead>
         	<tr>
          		<th class="tabheader" style="width: 12px">#</th>
          		<th class="tabheader" style="width: 20px">Quantity</th>
          		<th class="tabheader" style="width: 30px">Serial</th>
         	</tr>
         </thead>
		 <tbody>
		 <?php 
		  	$varqty = $itmqty / $secopy;
			for ($i = 1; $i <= $secopy; $i++){
			?>	
				<tr>
            	<td style="text-align:center;"><?php echo $i; ?></td>
           		<td style="text-align:center;">
           		<input name="lblqty[]" value="<?php echo number_format($varqty,"2",".",""); ?>" id="lblqty<?php echo $i; ?>" onBlur="getqty(this.id)" style="text-align:right; width: 127px;">
           		</td>
            	<td style="text-align:center;">
            	<input name="lblser[]" value="<?php echo $i; ?>" id="lblser<?php echo $i; ?>" readonly="readonly" style="width: 93px; text-align:center">
            	</td>           		
           		</tr>
		<?php	
			}
		 ?>
		 	<tr>
		 		<td style="text-align:right">Total :</td>
		 		<td style="text-align:center">
		 		<input name="tqty" value="<?php echo number_format($itmqty, "2", ".",""); ?>" id="tqty" readonly="readonly" style="text-align:right; width: 127px;" class="textnoentry1">
		 		</td>
		 		<td></td>
		 	</tr>
		 </tbody>
		 </table>
		 <br>
		 <center>
		 		<input type="button" value="Close" style="width: 75px; height: 32px" class="butsub" onclick="window.close()">
		 		<input type="submit" name="Submit" id="btnprint" value="Print" style="width: 75px; height: 32px" class="butsub">
		 </center>
		 </fieldset>
		</form>
</body>
</html>
