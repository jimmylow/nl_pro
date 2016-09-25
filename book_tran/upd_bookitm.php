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
      $varbkno = $_GET['bno'];
      include("../Setting/ChqAuth.php");
    }
    
    if ($_POST['Submit'] == "Update") {
		$vmbkno = $_POST['bookno'];
		
		if ($vmbkno != ""){
			if(!empty($_POST['bkitmcd']) && is_array($_POST['bkitmcd'])) 
			{	
				foreach($_POST['bkitmcd'] as $row=>$matcd){
					$bkitmcode = mysql_real_escape_string($matcd);
					$bkrelqty  = $_POST['relqty'][$row];
					
					if ($bkrelqty > 0)
					{
						$sql  = "Update booktab02 set sumrelqty = sumrelqty + $bkrelqty"; 
						$sql .= " Where bookno = '$vmbkno' and bookitm = '$bkitmcode'";
						mysql_query($sql) or die("Query 2 :".mysql_error());;	
					}
				}
			}
		}	
  
  		$backloc = "../book_tran/m_booksys.php?menucd=".$var_menucode;
       	echo "<script>";
        echo 'location.replace("'.$backloc.'")';
        echo "</script>"; 

    }    
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">


<style media="all" type="text/css">
@import "../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css";
@import "../css/styles.css";

.style2 {
	margin-right: 0px;
}
</style>

<!-- jQuery libs -->
<script type="text/javascript" src="../js/datetimepicker_css.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>

<script type="text/javascript">

function validateForm()
{
	//Checking got Booking Quantity Got Negative Or Zero Value/////////////////////////
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;     

	for (var j = 1; j < rowCount; j++){

	    var idrowrel = j;
        var rowItem = document.getElementById(idrowrel).value;	 
        if (rowItem < 0){ 
        	alert ("Release Quantity Must Larger Then Zero; " + rowItem);
        	document.getElementById(idrowrel).focus();
			return false;
	    }		
    }			
	///////////////////////////////////////////////////////////////////////////////////

	//Checking Booking Quantity Got Larger Then Availabel Quantity/////////////////////
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;     

	for (var j = 1; j < rowCount; j++){

	    var idrelqty = j;
	    var bkbalid  = "bkbalqty"+valid;
	    
        var vbkq = document.getElementById(idrelqty).value;	
        var vavq = document.getElementById(bkbalid).value; 
        
        if (parseFloat(vbkq) > parseFloat(vavq)){ 
        	alert ("Release Quantity Must Not Exceed Booking Balance Quantity; Row "+ j);
        	document.getElementById(idrelqty).focus();
			return false;
	    }		
    }			
	///////////////////////////////////////////////////////////////////////////////////
   
}

function chkdecval(valid, valdecrel)
{
	var bkbalid  = "bkbalqty"+valid;
	
	var qtybkbal = document.getElementById(bkbalid).value;
	
	if (valdecrel != ""){
		
		if(isNaN(valdecrel)) {
    	   alert('Please Enter a valid number for Release Quantity:' + valdecrel);
    	   document.getElementById(relqtyid).focus();
    	   valdecrel = 0;
    	}
    	document.getElementById(valid).value = parseFloat(valdecrel).toFixed(2);
    }else{    
    	valdecrel = 0;
   		document.getElementById(valid).value = parseFloat(valdecrel).toFixed(2);
    }
    
    if (parseFloat(valdecrel) < 0){	
    	alert('Release Qunatity Cannot Negative Value:' + valdecrel);
		document.getElementById(valid).focus();
    }
    if (parseFloat(valdecrel) > parseFloat(qtybkbal)){
    	alert('Release Qunatity Cannot More Than Booking Balance Quantity');
		document.getElementById(valid).focus();
	}
	
}
</script>
</head>

<?php
		$sql = "select * from booktab01";
        $sql .= " where bookno ='".$varbkno."'";        
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);

        $sordno = $row['byrefno'];
        $sbuycd = $row['buycd'];
        $sbkdte = date('d-m-Y', strtotime($row['bookdte']));
        
        $sql = "select pro_buy_desc from pro_buy_master ";
        $sql .= " where pro_buy_code ='".$sbuycd."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $sbuyde = $row[0];
?>

<body onload="document.getElementById('1').focus();">
  <?php include("../topbarm.php"); ?> 
 <!-- <?php include("../sidebarm.php"); ?> -->

  <div class="contentc">

	<fieldset name="Group1" style=" width: 934px;" class="style2">
	 <legend class="title">UPDATE BOOKING NO <?php echo $varbkno ;?></legend>
	  <br>	 
	  
	  <form name="InpBook" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	    <input type="hidden" name="bookno" value="<?php echo $varbkno ;?>">
		<table style="width: 926px" border="0">
		   <tr>
		   		<td></td>
		   		<td style="width: 31px">Sales Order No</td>
		   		<td>:</td>
		   		<td>
		   			<input name="bkordno" id="bkordno" readonly="readonly" type="text" style="width: 150px;" value="<?php echo $sordno; ?>">
		   		</td>
		   </tr>
		   <tr>
		   		<td></td>
		   		<td style="width: 31px"></td>
		   		<td></td>
		   		<td><div id="msgcd"></div></td>
		   </tr>	
		   <tr>
		   		<td></td>
		   		<td style="width: 31px">Buyer Name</td>
		   		<td>:</td>
		   		<td>
		   			<input class="inputtxt" name="bkbuycd" readonly="readonly" id ="bkbuycd" type="text" style="width: 60px;" value="<?php echo $sbuycd; ?>">	
		   			<label><?php echo $sbuyde; ?></label>
	
		   		</td>
		   </tr>
		   <tr><td></td></tr>
	   	   <tr>
	   	    <td style="width: 10px;"></td>
	  	    <td style="width: 31px;">Booking Date</td>
	  	    <td style="width: 5px;">:</td>
	  	    <td style="width: 300px;">
		   		<input class="inputtxt" name="bookdte" readonly="readonly" id ="bookdte" type="text" style="width: 80px;" value="<?php  echo $sbkdte; ?>">	
		   	</td>     
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 31px"></td>
	  	   <td></td>
	  	   <td></td>
	  	  </tr>
	  	</table>
	  	
		  <table id="itemsTable" class="general-table">
          	<thead>
          	 <tr>
               <th class="tabheader">#</th>
               <th class="tabheader">Item Code</th>
               <th class="tabheader">Description</th>
               <th class="tabheader">UOM</th>
               <th class="tabheader">Book Quantity</th>
               <th class="tabheader">Completed</th>
               <th class="tabheader">Send Qty</th>
               <th class="tabheader">Sum Release Qty</th>
               <th class="tabheader">Bal Book Qty</th>
               <th class="tabheader">Release Qty</th>
             </tr>
            </thead>

            <tbody>
              <?php
            	$sql = "SELECT * FROM booktab02";
             	$sql .= " Where bookno='".$varbkno."'";  
				$rs_result = mysql_query($sql); 
			   
			    $i = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)){ 
					if ($rowq['bookqty'] == ""){ $rowq['bookqty'] = 0;}
					if ($rowq['sendqty'] == ""){ $rowq['sendqty'] = 0;}
					if ($rowq['sumrelqty'] == ""){ $rowq['sumrelqty'] = 0;}
					
					$balbook = $rowq['bookqty'] - $rowq['sendqty'] - $rowq['sumrelqty'];
            ?>
             <tr class="item-row">
                <td style="width: 30px">
				<input name="seqno[]" id="seqno" value="<?php echo $i; ?>" readonly="readonly" style="width: 20px; border:0;"></td>
                <td>
				<input name="bkitmcd[]" id="bkitmcd<?php echo $i; ?>" readonly="readonly" style="width: 120px; border:0;" value="<?php echo $rowq['bookitm'];?>"></td>
                <td>
				<input name="bkitmdesc[]" id="bkitmdesc<?php echo $i; ?>" readonly="readonly" style="width: 200px; border:0;" value="<?php echo $rowq['itmdesc'];?>"></td>
                <td>
				<input name="itmuom[]" id="itmuom<?php echo $i; ?>" style="width: 50px; border:0;" readonly="readonly" value="<?php echo $rowq['itmuom'];?>"></td>
                <td>
				<input name="bkqty[]" id="itmbook<?php echo $i; ?>" readonly="readonly" style="width: 80px; border:0; text-align:right;" value="<?php echo $rowq['bookqty'];?>"></td>
                <td>
				<input name="compflg[]" id="bkcomp<?php echo $i; ?>" readonly="readonly" style="width: 75px; border:0; text-align:center;" value="<?php echo $rowq['compflg'];?>"></td>
				<td>
				<input name="sendqty[]" id="sendqty<?php echo $i; ?>" readonly="readonly" style="width: 75px; border:0; text-align:right;" value="<?php echo $rowq['sendqty'];?>"></td>
				<td>
				<input name="srelqty[]" id="srelqty<?php echo $i; ?>" readonly="readonly" style="width: 75px; border:0; text-align:right;" value="<?php echo $rowq['sumrelqty'];?>"></td>
				<td>
				<input name="bkbalqty[]" id="bkbalqty<?php echo $i; ?>" readonly="readonly" style="width: 75px; border:0; text-align:right" value="<?php echo number_format($balbook,"2",".",",");?>"></td>
             	<td>
				<input name="relqty[]" id="<?php echo $i; ?>" style="width: 100px; text-align:right" onblur="chkdecval(this.id, this.value)"></td>

             </tr>
              <?php
             	$i = $i + 1;
             	}
             ?>	
            </tbody>
           </table>

         <br><br>
     
		 <table style="width: 927px">
		  	<tr>
				<td style="width: 1059px; height: 22px;" align="center">
				<?php
				 $locatr = "m_booksys.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
					include("../Setting/btnupdate.php");
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
