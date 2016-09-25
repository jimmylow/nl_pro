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
      $var_prodcd = $_GET['prod']; 
      $var_opt   = $_GET['opt'];
    }
    
    if ($_POST['Submit'] == "Update") {
    	$var_planpr = $_POST['planpr'];
   	  	
    	if ($var_planpr != ""){
    	
      		if(!empty($_POST['planmat']) && is_array($_POST['planmat'])) 
			{	
				foreach($_POST['planmat'] as $row=>$matcd ) {
					$macode    = $matcd;
					$maseqno   = $_POST['seqno'][$row];
					$mabuy     = $_POST['planbuy'][$row];
					$maord     = $_POST['planord'][$row];
					$madesc    = $_POST['plandes'][$row];
					$mauom     = $_POST['planuom'][$row];
					$maucomps  = $_POST['planuco'][$row];
					$maordqty  = $_POST['planqty'][$row];
					$masumcomp = $_POST['plantco'][$row];
					$bkitmavai = $_POST['plitmava'][$row];
						
					if ($macode <> ""){
						if ($maucomps == ""){ $maucomps = 0;}
						if ($maordqty == ""){ $maordqty = 0;}
						if ($masumcomp == ""){ $masumcomp = 0;}
													
						$sql  = "update tmpplancol set sumcomps= '$masumcomp' "; 
						$sql .=	" where usernm = '$var_loginid' and orderno ='$maord'";
						$sql .= " and   buycd  = '$mabuy'       and rm_code = '$macode'";
						$sql .= " and   procd  = '$var_planpr'";
						mysql_query($sql) or die("Can't Insert To Temp Table Color Plan :".mysql_error());	           					
					}
				}
			}	
		}
		echo "<script>";   
   		echo "window.close();"; 
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
    var vmpscons = "plantco"+vid;
    
	var col1 = document.getElementById(vmpscons).value;		
	
	if (col1 != ""){
		if(isNaN(col1)) {
    	   alert('Please Enter a valid number for Unit Consumption :' + col1);
    	   col1 = 0;
    	}
    	document.getElementById(vmpscons).value = parseFloat(col1).toFixed(4);
    }
}

function deleteRow(tableID) {
	try {
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
         
        if (rowCount > 2){
             table.deleteRow(rowCount - 1);
        }else{
             alert ("No More Row To Remove");
        }
	}catch(e) {
		alert(e);
	}
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

	//Check input booking quantity is Valid-------------------------------------------------------
	//  var table = document.getElementById('itemsTable');
	//  var rowCount = table.rows.length;  
	
	//  for (var j = 1; j < rowCount; j++){

	 //   var idrowbook = "planbok"+j;
     //   var rowItemc = document.getElementById(idrowbook).value;	 
        
     //   if (rowItemc != ""){ 
       // 	if(isNaN(rowItemc)) {
    //	   		alert('Please Enter a valid number for Booking Quantity :' + rowItemc + " line No :"+j);
    	//        return false;
    	//    }    
    //	}
    //   }		
    ////---------------------------------------------------------------------------------------------------
     
}

function get_desc(itemcode, vid)
{
    var iditmdesc = "plandes"+vid;
    var iditmuom  = "planuom"+vid;
    var iditmavil = "plitmava"+vid;
	var strURL="aja_get_itmdesc.php?itmcod="+itemcode;
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
					var obj = jQuery.parseJSON(req.responseText);
					if (obj != null){
						document.getElementById(iditmdesc).value = obj.desc;
						document.getElementById(iditmuom).value = obj.uom;
						document.getElementById(iditmavil).value = obj.avail;
					}else{
						document.getElementById(iditmdesc).value = "";
						document.getElementById(iditmuom).value = "";
						document.getElementById(iditmavil).value = "";
					}	
				} 
			}
		}	 
	}
	req.open("GET", strURL, true);
	req.send(null);
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
 
<body onload="document.InpMDETMas.plantco1.focus();">
 
	<fieldset name="Group1" style=" width: 927px;" class="style2">
	 <legend class="title">PLANNING DETAIL - BY PRODUCT CODE (<?php echo $var_prodcd; ?> - <?php echo $prdesc; ?>)</legend>
	  <br>	 
	 
	  <form name="InpMDETMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()" style="width: 910px">		
		  <input name="planpr" type="hidden" value="<?php echo $var_prodcd;?>">
		  
		  <table id="itemsTable" class="general-table" style="width: 100%">
          	<thead>
          	 <tr>
              <th class="tabheader">#</th>
              <th class="tabheader" style="width: 50px">Buyer</th>
              <th class="tabheader">Order No</th>
              <th class="tabheader">Raw Material</th>
              <th class="tabheader">Description</th>
              <th class="tabheader">UOM</th>
              <th class="tabheader">Consumption</th>
              <th class="tabheader">Available</th> 
             </tr>
            </thead>
            <tbody>
            
            <?php
				$sql1  = "SELECT buycd, orderno, rm_code, rm_cddesc, rm_uom, sum(sumcomps)";
				$sql1 .= " FROM tmpplancol ";
             	$sql1 .= " Where usernm ='".$var_loginid."'"; 
             	$sql1 .= " And   procd ='$var_prodcd'";
             	$sql1 .= " group by 1, 2, 3, 4, 5";
	    		$sql1 .= " ORDER BY buycd, orderno, rmseqno, rm_code";
				$rs_result1 = mysql_query($sql1); 
			 
			 	$i = 1;
			  	while ($row1 = mysql_fetch_assoc($rs_result1)){
			  		$buycd   = $row1['buycd'];
			  		$ordno   = $row1['orderno'];
			  		$itmcd   = htmlentities($row1['rm_code']);
			  		$rmdesc  = htmlentities($row1['rm_cddesc']);
			  		$rmuom   = $row1['rm_uom'];
					$sumconp = number_format($row1['sum(sumcomps)'], 4, '.', '');
					
					#----------------------Available Qty For RM CODE-------------------
					$sql2 = "select sum(totalqty) from rawmat_tran ";
        			$sql2 .= " where item_code ='$itmcd'";
        			$sql2 .= " and tran_type in ('REC', 'ADJ', 'RTN', 'ISS', 'OPB', 'REJ')";
        			$sql_result2 = mysql_query($sql2);
        			$row2 = mysql_fetch_array($sql_result2);        
        			if ($row2[0] == "" or $row2[0] == null){ 
        				$row2[0]  = 0.00;
        			}
        			$onhnd = $row2[0];
        
        			$sql3 = "select sum(bookqty-sumrelqty) from booktab02 ";
        			$sql3 .= " where bookitm ='$itmcd'";
        			$sql3 .= " and compflg = 'N'";
        			$sql_result3 = mysql_query($sql3);
        			$row3 = mysql_fetch_array($sql_result3);        
        			if ($row3[0] == "" or $row3[0] == null){ 
          				$row3[0]  = 0.00;
        			}
        			$currbk = $row3[0];
        			$avail = number_format(($onhnd - $currbk),"2",".","");
        			#----------------------Available Qty For RM CODE-------------------
			
					echo '<tr class="item-row">';
             		echo '<td><input name="seqno[]" id="seqno" value="'.$i.'" readonly="readonly" style="width: 15px; border:0;"></td>';
             		echo '<td><input name="planbuy[]"  value="'.$buycd.'"  id="planbuy'.$i.'" readonly="readonly" style="width: 50px; border:0;"></td>';
					echo '<td><input name="planord[]"  value="'.$ordno.'"  id="planord'.$i.'" readonly="readonly" style="width: 70px; border:0;" ></td>';
             		echo '<td><input name="planmat[]"  value="'.$itmcd.'"  id="planmat'.$i.'" readonly="readonly" style="width: 100px; border:0;"></td>';
             		echo '<td><input name="plandes[]"  value="'.$rmdesc.'" id="plandes'.$i.'" readonly="readonly" style="border: 0; width: 230px;"></td>';
             		echo '<td><input name="planuom[]"  value="'.$rmuom.'"  id="planuom'.$i.'" readonly="readonly" style="border: 0; width: 40px;"></td>';
             		echo '<td><input name="plantco[]"  value="'.$sumconp.'" id="plantco'.$i.'" style="width: 90px; text-align:right;" onBlur="calccomps('.$i.');"></td>';
             		echo '<td><input name="plitmava[]" value="'.$avail.'"  readonly="readonly" id="plitmava'.$i.'" style="width: 90px;border:0; text-align:right;"></td>';
             		echo '</tr>';	
             		$i = $i + 1;							
	 		 	}
         ?>
         </tbody>
         
        </table>   
	   <table>
	  	<tr>
			<td style="width: 1150px; height: 22px;" align="center">
			<input type="button" value="Close" style="width: 60px; height: 32px" class="butsub" onclick="window.close()">
			<input type=submit name = "Submit" value="Update" class="butsub" style="width: 60px; height: 32px" >
			</td>
			</tr>
	  </table>
   </form>	
  </fieldset>

  <div class="spacer"></div>
</body>
</html>
