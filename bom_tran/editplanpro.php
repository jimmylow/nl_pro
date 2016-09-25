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
      $itmcd = $_GET['pr'];
      $cstcd = $_GET['cs'];
      $desccd = $_GET['desc'];   
    }
   
    if ($_POST['Submit'] == "Update") {
		$cno = $_POST['cstno'];
		$itno = $_POST['itmcode'];
   	  	
   	  	#-----------------------Update Detail -------------------------------------
    	if(!empty($_POST['prno']) && is_array($_POST['prno'])){ 
    		foreach($_POST['prno'] as $row=>$prno){
   
				$seqno  = $_POST['seqno'][$row];
				$probuy = $_POST['probuy'][$row];
				$proord = $_POST['proord'][$row];
				$ordqty = $_POST['ordqty'][$row];
				$unicom = $_POST['unicom'][$row];
				$scomp  = $_POST['scomp'][$row];
				
				if ($prno <> ""){
					if ($scomp == ""){ $scomp = 0;}

					$sql  = "update costing_matdet set sum_comp = '$scomp ' "; 
					$sql .=	" where costingno = '$cno'  and rm_code ='$itno'";
					$sql .= " and   prodcd    = '$prno' and buycd   = '$probuy'";
					$sql .= " and   ordno     = '$proord'";

					mysql_query($sql) or die("Cant Update Detail Table :".mysql_error());
				}
			}
		}
		#------------------------------------------------------------------------------
    	
		echo "<script>"; 
   		echo "window.close();";
   		echo 'window.opener.location.reload(true);';
   		echo "</script>"; 
    }    
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>PLAN BY PRODUCT</title>

<style media="all" type="text/css">


@import "../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css";
@import "../css/styles.css";

body{
	 overflow:scroll;
}

.style2 {
	margin-right: 0px;
}
</style>

<!-- jQuery libs -->
<script type="text/javascript" src="../js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>

<!-- Our jQuery Script to make everything work -->
<script  type="text/javascript" src="plan_itmdet.js"></script>


<script type="text/javascript"> 

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
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

function calccomps(vid)
{
	var col1 = document.getElementById(vid).value;		
	
	if (col1 != ""){
		if(isNaN(col1)) {
    	   alert('Please Enter a valid number :' + col1);
    	   col1 = 0;
    	}
    	document.getElementById(vid).value = parseFloat(col1).toFixed(4);
    }
    
     if (parseFloat(col1) < 0){	
    	alert('Cannot Negative Value:' + col1);
		document.getElementById(vid).focus();
    }
    
    var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length - 2;  
	
	var tot = 0;
	for (var j = 1; j < rowCount; j++){
	  var idrowconsump = "scomp"+j;
      var rowItemc = document.getElementById(idrowconsump).value;
      tot = tot + parseFloat(rowItemc);
    }
   
	document.getElementById('totc').innerHTML = parseFloat(tot).toFixed(4);		
}

function validateForm()
{
    //Check input compusmtion and qunaity is Valid-------------------------------------------------------
	  var table = document.getElementById('itemsTable');
	  var rowCount = table.rows.length;  
	
	  for (var j = 1; j < rowCount; j++){

	    var idrowconsump = "plantco"+j;
        var rowItemc = document.getElementById(idrowconsump).value;	 
        
        if (rowItemc != ""){ 
        	if(isNaN(rowItemc)) {
    	   		alert('Please Enter a valid number for Consumption :' + rowItemc + " line No :"+j);
    	        return false;
    	    }    
    	}
       }		
    //--------------------------------------------------------------------------------------------------- 
}

</script>
</head>
<?php
 	$sqlcd = "select distinct procdde from tmpplanpro01 ";
 	$sqlcd .= " where procd ='".$var_prodcd."'";
    $sql_resultcd = mysql_query($sqlcd) or die("Can't query Product Table :".mysql_error());
    $rowcd = mysql_fetch_array($sql_resultcd);
    $prdesc = $rowcd[0];
?>
 
<body onload="document.InpMDETMas.scomp1.focus();">
	<fieldset>
	<form name="InpMDETMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return validateForm()">
	<input type="hidden" name="cstno" value="<?php echo $cstcd; ?>">
	<table>
		<tr>
			<td></td>
			<td style="font-weight:bold">Item Code</td>
			<td>:</td>
			<td><input name="itmcode" type="text" readonly="readonly" value="<?php echo htmlentities($itmcd); ?>"></td>
		</tr>
	</table>
	<br>
    <table width="100%" id="itemsTable">
		<tr>
			<th class="tabheader">No</th>
			<th class="tabheader">Buyer</th>
			<th class="tabheader">Sales Order</th>
			<th class="tabheader">Product Code</th>
			<th class="tabheader">Order Qty</th>
			<th class="tabheader">Unit Comps.</th>
			<th class="tabheader">Compsumtion</th>
		</tr>
		<?php
			$sql  = "select buycd, ordno, prodcd, sordqty, rm_ucoms, sum(sum_comp), sum(puritm), sum(bokitm)";
            $sql .= " from costing_matdet";
			$sql .= " Where rm_code = '$itmcd'";
			$sql .= " and costingno = '$cstcd'";
			$sql .= " and rm_desc = '$desccd'"; 
			$sql .= " group by 1, 2, 3, 4, 5";
			$sql .= " order by 1, 2, 3";
            $sql_result = mysql_query($sql) or die("Can't query Temp Table : ".mysql_error());
                
			$i = 1;
			$tqty = 0;
			$pqty = 0;
			$bqty = 0;	
            while ($row = mysql_fetch_assoc($sql_result)){

            	$buycd  = $row['buycd'];
                $ordno  = $row['ordno'];
                $prno   = $row['prodcd'];
                $ordqty = $row['sordqty'];
                $unicom = $row['rm_ucoms'];
                $scomp  = $row['sum(sum_comp)'];
            ?>
            <tr>
				<td><input name="seqno[]"  id="seqno<?php echo $i;?>"  value="<?php echo $i; ?>" readonly="readonly" style="width: 7px; border:0;"></td>
				<td><input name="probuy[]" id="probuy<?php echo $i;?>" value="<?php echo $buycd; ?>" readonly="readonly" style="width: 30px; border:0;"></td>
				<td><input name="proord[]" id="proord<?php echo $i;?>" value="<?php echo $ordno; ?>" readonly="readonly" style="width: 80px; border:0;"></td>
				<td><input name="prno[]"   id="prno<?php echo $i;?>"   value="<?php echo $prno; ?>" readonly="readonly" style="width: 100px; border:0;"></td>
				<td><input name="ordqty[]" id="ordqty<?php echo $i;?>" value="<?php echo $ordqty; ?>" readonly="readonly" style="width: 80px; border:0; text-align:right"></td>
				<td><input name="unicom[]" id="unicom<?php echo $i;?>" value="<?php echo $unicom; ?>" readonly="readonly" style="width: 80px; border:0; text-align:right"></td>
				<td><input name="scomp[]"  id="scomp<?php echo $i;?>" value="<?php echo $scomp; ?>" style="width: 80px; text-align:right" onblur="calccomps(this.id)"></td>
			</tr>    
			<?php
				$i = $i + 1;
				$tqty = $tqty + $scomp;
 
 			}
		?>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td style="text-align:right;">Total :</td>
			<td class="textnoentry1" style="text-align:right;" id="totc"><?php echo number_format($tqty, "4",".",""); ?></td>
		</tr>
		<tr>
			<td colspan="9" align="center">
				<input type="button" value="Close" style="width: 60px; height: 32px" class="butsub" onclick="window.close()">
				<input type="submit" name = "Submit" value="Update" style="width: 60px; height:32px" class="butsub">
			</td>
		</tr>
	</table>
	</form>
	</fieldset>
</body>

</html>
