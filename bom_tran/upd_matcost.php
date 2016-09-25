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
    
      $var_stat = $_GET['stat'];
      $var_menucode = $_GET['menucd'];
      $var_costinno = $_GET['costn'];
      include("../Setting/ChqAuth.php");
    }
    
      if ($_POST['Submit'] == "Update") {
    
			$rmk = $_POST['mplanrmk'];
			$cst = $_POST['costn'];
        	$var_menucode  = $_POST['menudcode'];

         	if ($cst <> "") {
         	    #------------------------Update Master Costing -------------------------------
         		$vartoday = date("Y-m-d");
         		$sql = "Update costing_mat set remark ='$rmk',";
         		$sql .= " 					   modified_by='$var_loginid',";
         		$sql .= " 					   modified_on='$vartoday'";
         		$sql .= " WHERE costingno = '$cst'";
       	 		mysql_query($sql); 
       	 		#-----------------------------------------------------------------------------
       	 		
       	 		/*--------------------------- Insert Into costing_purbook ----------------------------- */
				$sql  = " Delete From costing_purbook Where costing_no = '$cst'";
       	 		mysql_query($sql); 

  				if(!empty($_POST['planmat']) && is_array($_POST['planmat'])) 
				{	
					foreach($_POST['planmat'] as $row=>$matcd ) {
						$matcode    = $matcd;
						$matseqno   = $_POST['seqno'][$row];
						$matdesc    = htmlentities($_POST['plandes'][$row]);
						$matuom     = $_POST['planuom'][$row];
						$matcoucost = $_POST['plantco'][$row];
						$matpur     = $_POST['planpur'][$row];
						$matbok     = $_POST['planbok'][$row];
					
						if ($matcode <> "")
						{
							if ($matcoucost == ""){ $matcoucost = 0;}
							if ($matpur  == ""){ $matpur  = 0;}
							if ($matbok == ""){ $matbok = 0;}
							$sql = "INSERT INTO costing_purbook (costing_no, itmcode, consump, itmdesc, itmuom, plpurqty, plbkqty) values 
						    		('$cst', '$matcode', '$matcoucost', '$matdesc','$matuom','$matpur','$matbok')";
							mysql_query($sql) or die("Query 2 :".mysql_error());
           				}	
					}
				}

			}
			/*--------------------------- Insert Into costing_matdet ----------------------------- */

        
         		$backloc = "../bom_tran/m_mat_plan.php?menucd=".$var_menucode;
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
<script type="text/javascript" src="../js/multitable/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../js/multitable/jquery-ui-1.8.14.custom.min.js"></script>
<!-- Our jQuery Script to make everything work -->
<script  type="text/javascript" src="plan_itmdet.js"></script>

<script type="text/javascript">
function poptastic(url)
{
	var newwindow;
	newwindow=window.open(url,'name','height=800,width=800,left=30,top=200, scrollbars=yes');
	if (window.focus) {newwindow.focus()}
}
 

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
    var vmpscons = "plantco"+vid;
	var col1 = document.getElementById(vmpscons).value;		
	
	if (col1 != ""){
		if(isNaN(col1)) {
    	   alert('Please Enter a valid number for Unit Consumption :' + col1);
    	   col1 = 0;
    	}
    	document.getElementById(vmpscons).value = parseFloat(col1).toFixed(4);
    }
    
    if (col1 < 0){
    	alert('Negative Value Is Not Accespt :' + col1);
    	col1 = 0;
    	document.getElementById(vmpscons).value = parseFloat(col1).toFixed(4);
    }
    
	caltotcomps();   
}

function caltotcomps(){
    var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length; 
		
	var totcops = 0;
		
	for(var i = 1; i < rowCount; i++) { 
	  var vplantco = "plantco"+i;
	  var colcops = document.getElementById(vplantco).value;					
		
	  if (!isNaN(colcops) && (colcops != "")){
				totcops = parseFloat(totcops) + parseFloat(colcops);		
	  }
	}
	document.InpMDETMas.totcompid.value = parseFloat(totcops).toFixed(4);	     
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

	//Check the list of mat item no is Valid-------------------------------------------------------------
	var flgchk = 1;	
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
         
    for (var j = 1; j < rowCount; j++){
       var idrowItem = "planmat"+j;
       var rowItem = document.getElementById(idrowItem).value;	 
              
       if (rowItem != ""){
       	var strURL="aja_chk_subCodeCount.php?rawmatcdg="+rowItem;
	   	var req = getXMLHTTP();
        if (req)
	  	{
			req.onreadystatechange = function()
			{
				if (req.readyState == 4)
				{
					// only if "OK"
					if (req.status == 200)
					{
						if (req.responseText == 0)
						{
						   flgchk = 0;
						   alert ('Invalid Raw Mat Item Sub Code : '+ rowItem + ' At Row '+j);
						   return false;
						}
					} else {
						//alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
						return false;
					}
				}
			}	 
		  }
		
		  req.open("GET", strURL, false);
		  req.send(null);
	    }	  
    }
     if (flgchk == 0){
	   return false;
	}
    //---------------------------------------------------------------------------------------------------

    //Check the list of mat item no got duplicate item no------------------------------------------------
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;  
	var mylist = new Array();
	var mylist2 = new Array();	    

	for (var j = 1; j < rowCount; j++){

	    var idrowItem = "planmat"+j;
	    var idrowItem2 = "plandes"+j;
        var rowItem = document.getElementById(idrowItem).value;
        var rowItem2 = document.getElementById(idrowItem2).value;	 	 
        if (rowItem != ""){ 
        	mylist.push(rowItem);   
	    }
	    if (rowItem2 != ""){ 
        	mylist2.push(rowItem2);   
	    }			
    }		
	
	mylist.sort();
	mylist2.sort();
	var last = mylist[0];
	var last2 = mylist2[0];
	
	for (var i=1; i < mylist.length; i++) {
		if (mylist[i] == last){
			if (mylist2[i] == last2){ 
				alert ("Duplicate Item Found; " + last + " " + last2);
				return false;
			}	
		}	
		last = mylist[i];
		last2 = mylist2[i];
	}
	//---------------------------------------------------------------------------------------------------   
	
	//Check input booking quantity is Valid-------------------------------------------------------
	  var table = document.getElementById('itemsTable');
	  var rowCount = table.rows.length;  
	
	  for (var j = 1; j < rowCount; j++){

	    var idrowbook = "planbok"+j;
        var rowItemc = document.getElementById(idrowbook).value;	 
        
        if (rowItemc != ""){ 
        	if(isNaN(rowItemc)) {
    	   		alert('Please Enter a valid number for Booking Quantity :' + rowItemc + " line No :"+j);
    	        return false;
    	    }    
    	}
       }		
    //---------------------------------------------------------------------------------------------------
    
    //Checking Booking Quantity Got Larger Then Availabel Quantity/////////////////////
	var table = document.getElementById('itemsTable');
	var rowCount = table.rows.length;     

	for (var j = 1; j < rowCount; j++){

	    var idbookqty  = "planbok"+j;
	    var idavailqty = "plitmava"+j;
	    
        var vbkq = document.getElementById(idbookqty).value;	
        var vavq = document.getElementById(idavailqty).value; 
        
        if (parseFloat(vbkq) > parseFloat(vavq)){ 
        	alert ("Booking Quantity Must Not Exceed Available Quantity; Row "+ j);
        	document.getElementById(idbookqty).focus();
			return false;
   
	    }		
    }			
	///////////////////////////////////////////////////////////////////////////////////
}

function chkdecvalb(valdecbk, valid)
{
	var bkqtyid = "planbok"+valid;
	var avqtyid = "plitmava"+valid;
	
	var qtyav = document.getElementById(avqtyid).value;

	if (valdecbk != ""){
		if(isNaN(valdecbk)) {
    	   alert('Please Enter a valid number for Booking Quantity:' + valdecbk);
    	   document.getElementById(bkqtyid).focus();
    	   valdecbk = 0;
    	}
    	document.getElementById(bkqtyid).value = parseFloat(valdecbk).toFixed(2);
    }else{
    	valdecbk = 0;
    	document.getElementById(bkqtyid).value = parseFloat(valdecbk).toFixed(2);
    }
    if (parseFloat(valdecbk) < 0){	
    	alert('Booking Qunatity Cannot Negative Value:' + valdecbk);
		document.getElementById(bkqtyid).focus();
    }
    if (parseFloat(valdecbk) > parseFloat(qtyav)){
    	alert('Booking Qunatity Cannot More Than Available Quantity');
		document.getElementById(bkqtyid).focus();
	}
}

function chkdecvalp(valdecbk, valid)
{
	var purqtyid = "planpur"+valid;
	
	var qtyav = document.getElementById(purqtyid).value;

	if (valdecbk != ""){
		if(isNaN(valdecbk)) {
    	   alert('Please Enter a valid number for Purchased Quantity:' + valdecbk);
    	   document.getElementById(purqtyid).focus();
    	   valdecbk = 0;
    	}
    	document.getElementById(purqtyid).value = parseFloat(valdecbk).toFixed(2);
    }else{
    	valdecbk = 0;
    	document.getElementById(purqtyid).value = parseFloat(valdecbk).toFixed(2);
    }
    if (parseFloat(valdecbk) < 0){	
    	alert('Purchased Qunatity Cannot Negative Value:' + valdecbk);
		document.getElementById(purqtyid).focus();
    }
}
</script>
</head>

 
  <?php
  	 $sql = "select remark, docdate, planopt from costing_mat ";
     $sql .= " where costingno ='".$var_costinno."'";
     $sql_result = mysql_query($sql);
     $row = mysql_fetch_array($sql_result);
     $planrmk = $row['remark'];
     $plandte = $row['docdate'];
     $plandte = date('d-m-Y', strtotime($plandte));
     if($row['planopt'] == "C"){
		$ploptdesc = "Color";
	 }else{
		$ploptdesc = "Product Code";
	 }	
	
     $sql = "select distinct sordno from costing_matord ";
     $sql .= " where costingno ='".$var_costinno."'";   
     $sql_result = mysql_query($sql) or die(mysql_error);
     
     $lblorder = "";
     while($row = mysql_fetch_assoc($sql_result)){
       if ($row['sordno'] != ""){ 
        if ($lblorder == ""){
    	    $lblorder = $row['sordno'];
        }else{  
			$lblorder = $lblorder.", ".$row['sordno'];		
		}
	   }			 
	 }
	 
 
  ?>
<body onload="document.InpMDETMas.mplanrmk.focus()">
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>--> 
  <div class ="contentc">
    <form name="InpMDETMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()" style="width: 870px">
	<fieldset name="Group1" style=" width: 900px;" class="style2">
	 <legend class="title">PLANNING DETAIL MATERIAL UPDATE <?php echo  $var_costinno; ?></legend>
	   <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
       <input name="costn" type="hidden" value="<?php echo $var_costinno;?>">
	  <br>	 
	  	 <table>
  	 		<tr>
	  	  		<td></td>
	  	  		<td>Costing No</td>
	  	  		<td>:</td>
	  	  		<td><input type="text" name="costinno" id="costinno" value="<?php echo $var_costinno; ?>" readonly="readonly"></td>
	  	  	</tr>
	  	  	<tr>
	  	  		<td></td>
	  	  	</tr>
	  	    <tr>
	  	    	<td></td>
	  	    	<td style="width: 154px">Remark</td>
	  	    	<td>:</td>
	  	    	<td style="width: 311px">
				<input class="inputtxt" name="mplanrmk" id ="mplanrmkid" type="text" style="width: 400px;" value="<?php  echo $planrmk; ?>"></td>
	  	    	<td style="width: 9px"></td>
	  	    	<td style="width: 84px">Doc. Date</td>
	  	    	<td style="width: 12px">:</td>
	  	    	<td style="width: 183px">
				<input class="inputtxt" name="mplandte" id ="mplandte" type="text" readonly="readonly" value="<?php  echo $plandte; ?>">
				</td>
	  	    </tr>
	  	    <tr>
	  	    	<td></td>
	  	    	<td style="width: 154px">Order No Include</td>
	  	    	<td>:</td>
	  	    	<td colspan="5">
	  	    	<label><?php echo $lblorder;?></label>
	  	    	</td>
	  	    </tr>
	  	    <tr>
	  	    	<td></td>
	  	    	<td>Planning By</td>
	  	    	<td>:</td>
	  	    	<td><?php echo $ploptdesc; ?></td>
	  	    </tr>
	  	  </table>	
	  	  <br>
		  <table id="itemsTable" class="general-table" style="width: 884px; height: 46px;">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader">Raw Material</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Consumption</th>
              <th class="tabheader">Available</th>
              <th class="tabheader">Plan<br>Purchased</th>
              <th class="tabheader">Plan<br>Booking Qty</th>
              <th class="tabheader">Edit</th>  
             </tr>
            </thead>
            <tbody>
            
             <?php
          		$var_sql  = " SELECT rm_code, rm_desc, rm_uom, sum(sum_comp)";
          		$var_sql .= " from costing_matdet";
	      		$var_sql .= " Where costingno = '$var_costinno'";
	      		$var_sql .= " group by 1, 2, 3 ";
	      		$var_sql .= " order by seqno";
	      		$rs_result = mysql_query($var_sql); 
	      		
	      	    $totcomps = 0;
	      	    $i = 1;
	      		while($row = mysql_fetch_assoc($rs_result)){
	      		    $itmcode  = htmlentities($row['rm_code']);
	      		    $itmdesc  = htmlentities($row['rm_desc']);
	      		    $itmuom   = $row['rm_uom'];
	      		    $itmscomp = $row['sum(sum_comp)'];
	      		    
      		        $sql = "select sum(plpurqty), sum(plbkqty) from costing_purbook ";
     				$sql .= " where costing_no ='$var_costinno' And itmcode = '$itmcode'";
     				$sql_result = mysql_query($sql);
     				$row = mysql_fetch_array($sql_result);
     			    $itmspur = number_format($row[0], 2, ".",",");
     				$itmsbok = number_format($row[1], 2, ".",",");
     				
     				#----------------------Available Qty For RM CODE-------------------
						$sqlonh  = "select sum(totalqty) from rawmat_tran ";
        				$sqlonh .= " where item_code ='$itmcode'";
        				$sqlonh .= " and tran_type in ('REC', 'ADJ', 'RTN', 'ISS', 'OPB', 'REJ')";
        				$sql_resultonh = mysql_query($sqlonh);
        				$rowonh = mysql_fetch_array($sql_resultonh);        
        				if ($rowonh[0] == "" or $rowonh[0] == null){ $rowonh[0]  = 0.00;}
        				$onhnd = $rowonh[0];
        
        				$sqlbk  = "select sum(bookqty-sumrelqty) from booktab02 ";
        				$sqlbk .= " where bookitm ='$itmcode'";
        				$sqlbk .= " and compflg = 'N'";
        				$sql_resultbk = mysql_query($sqlbk);
        				$rowbk = mysql_fetch_array($sql_resultbk);        
        				if ($rowbk[0] == "" or $rowbk[0] == null){ $rowbk[0]  = 0.00;}
        				$currbk = $rowbk[0];
        				$avail = number_format(($onhnd - $currbk),"2",".","");
        				#----------------------Available Qty For RM CODE-------------------


	      		    
	      		    $urlviwcol = "editplanpro.php?pr=".$itmcode."&cs=".$var_costinno."&desc=".trim($itmdesc);
	      		    	      		    		      		    
	      			echo '<tr class="item-row">';
             		echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 15px; border:0;"></td>';
             		echo '<td><input name="planmat[]" value="'.$itmcode.'"  id="planmat'.$i.'" readonly="readonly" style="width: 100px; border:0;"></td>';
             		echo '<td><input name="plandes[]" value="'.$itmdesc.'"  id="plandes'.$i.'" readonly="readonly" style="width: 200px; border:0;"></td>';
             		echo '<td><input name="planuom[]" value="'.$itmuom.'"   id="planuom'.$i.'" readonly="readonly" style="border: 0; width: 30px;"></td>';
             		echo '<td><input name="plantco[]" value="'.$itmscomp.'" id="plantco'.$i.'" readonly="readonly" style="width: 80px; border:0; text-align:right;"></td>';
             		echo '<td><input name="plitmava[]" value="'.$avail.'" readonly="readonly" id="plitmava'.$i.'" style="width: 90px;border:0;text-align:right;"></td>';
             		echo '<td><input name="planpur[]" value="'.$itmspur.'"  id="planpur'.$i.'" style="width: 80px; text-align:right;" onblur="chkdecvalp(this.value, '.$i.')"></td>';
             	    echo '<td><input name="planbok[]" value="'.$itmsbok.'"  id="planbok'.$i.'" style="width: 80px; text-align:right;" onblur="chkdecvalb(this.value, '.$i.')"></td>';
             	    echo '<td style="text-align:center;"><a href="javascript:poptastic(\''.$urlviwcol.'\')" title="RM Planning Detail">[EDIT]</a></td>';
             		echo '</tr>';
             		$totcomps = $totcomps + $itmscomp;
             		$i = $i + 1;	
				}      
				$totcomps = number_format($totcomps, 4, '.', '');      
         	 ?>
         </tbody>
        </table>
	    
         <table style="width: 884px">
         	<tr>
         		<td style="width: 24px"></td>
         		<td style="width: 227px"></td>
         		<td style="width: 126px"></td>
         		<td align="left" style="width: 71px">Total</td>
         		<td>
				<input readonly="readonly" class="textnoentry1" type="text" id="totcompid" value="<?php echo $totcomps;?>" style="width: 90px; text-align:right">
				</td>
         	</tr>
		   </table>	
	    <br>
	 
	   <table>
	  	<tr>
			<td style="width: 900px; height: 22px;" align="center">
			<?php
				 $locatr = "m_mat_plan.php?menucd=".$var_menucode;
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				  include("../Setting/btnupdate.php");
				?>
				</td>
			</tr>
	  </table>
	  
  </fieldset>
  </form>
  </div>
  <div class="spacer"></div>
</body>
</html>
