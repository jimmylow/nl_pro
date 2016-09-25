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
      	$vrefd  = $_GET['refd'];
      	$itmqty = $_GET['itmqt'];
      	$secopy = $_GET['secp'];
    }
    
     if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Save") {
		
		$itmc  = htmlentities($_POST['itemno']);	
		$vref  = htmlentities($_POST['refno']);	
      	$vrefd = $_POST['refopt'];
      	$scopy = $_POST['copys'];
      	$toqty = $_POST['tqty'];
      			
        if ($vref != ""){
        
        	switch ($vrefd){ 
     		case "1":
     			$sql = "select po_number, refno from rawmat_receive ";
	        	$sql .= " where rm_receive_id ='".$vref."'";
             	break;
            case "2":
     			$sql = "select po_number, refno from rawmat_receive ";
	        	$sql .= " where invno ='".$vref."'";
             	break;
			case "3":
     			$sql = "select po_number, refno from rawmat_receive ";
	        	$sql .= " where refno ='".$vref."'";
             	break; 	
            default: 
            	$sql = "";	
     		}
     		if ($sql != ""){
     			$sql_result = mysql_query($sql) or die(mysql_error());
        		$row = mysql_fetch_array($sql_result);
        		$ponum = $row[0];
        		$doref = $row[1];
        	}	

        	if(!empty($_POST['lblser']) && is_array($_POST['lblser'])) 
			{	
				$vardte = $vartoday = date("Y-m-d");
				foreach($_POST['lblser'] as $row=>$lblser){
					
					$lblqty = $_POST['lblqty'][$row];
					
        			$sql  = " Insert Into stck_lbl (sub_code, refno, refopt, refcopy, ";
        			$sql .= "  lblser, lblserqty, datepri, totq, sponum, sdonum)";
        			$sql .= " Values ('$itmc', '$vref', '$vrefd', '$scopy', '$lblser',";
        			$sql .= "   '$lblqty', '$vardte', '$toqty', '$ponum', '$doref')";
        			mysql_query($sql) or die("Unable To Save The Serial For Printing".mysql_error());
        		}	
        	}
        }			

       	echo "<script>";
       	echo 'window.close();';
        echo "</script>"; 
     }
    } 

    
    /*
    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
		
		$itmc   = htmlentities($_POST['itemno']);	
		$vref   = htmlentities($_POST['refno']);	
      	$vrefd  = $_POST['refopt'];
      	$scopy  = $_POST['copys'];
      	
      	$arrqty = $_POST['lblqty'];
		$arrser = $_POST['lblser'];		
		$strqty = urlencode(serialize($arrqty ));
        $strser = urlencode(serialize($arrser));

        //header("Location: $dest" );
        $dest = '../stck_mas/stk_lblprt.php?i='.$itmc."&r=".$vref."&d=".$vrefd."&cp=".$scopy."&lq=".$strqty."&lr=".$strser;
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=800,width=100%,left=100,top=100');</script>";	
		//echo "<script language=\"javascript\">window.open('".$dest."','STOCK LABEL','resizable=yes,scrollbars=yes,width=950,height=700');</script>";
        
       	echo "<script>";
       	echo 'window.close();';
        echo "</script>"; 

     }
    } 
	*/
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

<body onload="document.LstDetMas.btnprint.focus()">
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
		 		<input type="submit" name="Submit" id="btnsave" value="Save" style="width: 75px; height: 32px" class="butsub">
		 </center>
		 </fieldset>
		</form>
</body>
</html>
