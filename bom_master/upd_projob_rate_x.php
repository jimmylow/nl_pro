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
      $var_prodcode = $_GET['procd'];
      $var_menucode = $_GET['menucd']; 
    }
   
    if ($_POST['Submit'] == "Update") {
		$vprocode = $_POST['prodcode'];
		$vprodesc = $_POST['procddesc'];
		$var_menucode  = $_POST['menudcode'];
            
		if ($vprocode <> "") {
		
			if(!empty($_POST['promojdesc']) && is_array($_POST['promojdesc'])) 
			{	
				$sql = "delete from pro_jobmodel where prod_code ='".$vprocode."'";
				mysql_query($sql);
					
				// Array does not have duplicates
				$i = 1;
				$vartoday = date("Y-m-d");
				foreach($_POST['jobid'] as $row=>$jobidr ) {
					$jobid   = mysql_real_escape_string($jobidr);
									
					$sqld = "select jobfile_desc from jobfile_master";
     		 		$sqld .= " where jobfile_id ='".$jobid."'";
     		 		$sql_resultd = mysql_query($sqld);
     		 		$rowd = mysql_fetch_array($sql_resultd);
     		 		$jobdesc = stripslashes(mysql_real_escape_string($rowd['jobfile_desc']));
     		 		
     		 		$jobsec  = $_POST['prosec'][$row];
     		 		$jobdte  = $_POST['prodteame'][$row];
     		 		
     		 		if (empty($jobdte)){
     		 			$jobdte = "";
     		 		}else{	
     		 			$jobdte = date('Y-m-d', strtotime($jobdte)); 
					}
									
					$jobrate = mysql_real_escape_string($_POST['promojrate'][$row]);

					if ($jobid <> ""){
						if ($jobdte == ""){
							$sql  = "INSERT INTO pro_jobmodel";
							$sql .=	" (prod_code, prod_desc, prod_jobseq, prod_jobid, prod_jobdesc, ";
							$sql .= "  prod_jobrate, prod_jobsec, modified_by, modified_on)";
							$sql .= "  values "; 
							$sql .= " ('$vprocode', '$vprodesc', '$i', '$jobid', '$jobdesc', '$jobrate', '$jobsec',";
							$sql .= "  '$var_loginid', '$vartoday')";	
						}else{
							$sql = "INSERT INTO pro_jobmodel values 
									('$vprocode', '$vprodesc', '$i','$jobid', '$jobdesc', '$jobrate', '$jobsec',
									 '$jobdte','$var_loginid', CURDATE())";	
						} 
						mysql_query($sql) or die(mysql_error());
					}
					$i = $i + 1;				
				}
			}
			
			$backloc = "../bom_master/projob_rate1.php?menucd=".$var_menucode;
			echo "<script>";
			echo 'location.replace("'.$backloc.'")';
			echo "</script>";		
				
		}else{
			$var_stat = 4;
		}
    }
	
	if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menudcode'];
         $backloc = "../bom_master/projob_rate1.php?menucd=".$var_menucode;
        
 		 echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<link rel="stylesheet" href="../css/autocomplete.css" type="text/css" media="screen">
	
<style media="all" type="text/css">
@import "../css/styles.css";
@import "../css/demo_table.css";

.style2 {
	margin-right: 0px;
}
</style>
<script type="text/javascript" language="javascript" src="../js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../js/dimensions.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../js/datetimepicker_css.js"></script>
<script type="text/javascript" src="../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../js/Parsers.js"></script>
<script type="text/javascript" src="../js/InputMask.js"></script>


<script type="text/javascript"> 

$(document).ready(function() {
				$('#example').dataTable( {
					"sPaginationType": "full_numbers"
				} );
			} );

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

function getState(vjobid, rowid)
{
	var strURL="aja_get_jobiddesc.php?verjobid="+vjobid;
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
				    var rowidde = 'promojdescid'+rowid;
				    var rowidre = 'promojrateid'+rowid;
				    var rowiddte = 'prodteame'+rowid;
							
					var strrep = req.responseText;
				    
					if (strrep != ""){
						var array1 = strrep.split('|');
						
						if (array1[0] != ""){
						  array1[0] = array1[0].replace('^',"'");
						  array1[0] = array1[0].replace('^',"'");	
						  document.getElementById(rowidde).value = array1[0];
						}
						
						if (array1[2] != ""){
						  document.getElementById(rowidre).value = array1[2];
						}
                
                		var now = new Date();
						var cdate = now.getDate();
						var cmonth = now.getMonth()+1;
						cmonth = ('0' + cmonth).slice(-2);
						var cyear = now.getFullYear();
                		document.getElementById(rowiddte).value = cdate + "-" + cmonth + "-" + cyear;
										
						var table = document.getElementById('dataTable');
						var rowCount = table.rows.length; 
			
						var totjob = 0;
						for(var i = 1; i < rowCount; i++) { 
							var rowidre = 'promojrateid'+i;
							var coljob = document.getElementById(rowidre).value;
						
							if (!isNaN(coljob) && (coljob != "")){
						
								totjob = parseFloat(totjob) + parseFloat(coljob);		
							   //totjob += parseFloat(coljob).toFixed(4);
						 
							}
						}
						document.InpJobFMas.totaljob.value = parseFloat(totjob).toFixed(4);
							
					}else{
					    document.getElementById(rowidde).value = "";
						document.getElementById(rowidre).value = "";
						document.getElementById(rowiddte).value = "";
						
						var table = document.getElementById('dataTable');
						var rowCount = table.rows.length; 
			
						var totjob = 0;
						for(var i = 1; i < rowCount; i++) { 
							var rowidre = 'promojrateid'+i;
							var coljob =  document.getElementById(rowidre).value;
							
							if (!isNaN(coljob) && (coljob != "")){
								totjob = parseFloat(totjob) + parseFloat(coljob);		
							}
						}
						document.InpJobFMas.totaljob.value = parseFloat(totjob).toFixed(4);
					}
	
				} else {
					alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
				}
			}
		}
		req.open("GET", strURL, true);
		req.send(null);
	}
	
	
}

function addRow(tableID) {

	var table = document.getElementById(tableID);
	var rowCount = table.rows.length; 
	var row = table.insertRow(rowCount);             
	var colCount = table.rows[0].cells.length;             
	
	for(var i = 0; i < colCount; i++) {                 
		var newcell = row.insertCell(i);                 
		newcell.innerHTML = table.rows[rowCount-1].cells[i].innerHTML;
		
		newcell.id = rowCount;

		switch(newcell.childNodes[0].type) {
		//case "hidden":                            
			//newcell.childNodes[0].value = "";
           // newcell.childNodes[0].id = rowCount;				
			//break; 
		case "text":  
		    switch(i){
		     case 0:
		      	newcell.childNodes[0].value = "";
                newcell.childNodes[0].id = 'prosec'+rowCount;	
                break;  
		     case 2:
		        newcell.childNodes[0].value = "";
                newcell.childNodes[0].id = 'promojdescid'+rowCount;	
               	break;
		     case 3:
		      	newcell.childNodes[0].value = "";
                newcell.childNodes[0].id = 'promojrateid'+rowCount;	
                break;
             case 4:
		      	newcell.childNodes[0].value = "";
                newcell.childNodes[0].id = 'prodteame'+rowCount;	
                break;    
		    }    	             
		case "checkbox":                            
			newcell.childNodes[0].checked = false; 
            //newcell.childNodes[0].id = rowCount;			
			break;                    
		case "select-one":                            
			newcell.childNodes[0].selectedIndex = 0;
			newcell.childNodes[0].options[0] = new Option('', '');
			newcell.childNodes[0].id = rowCount;			
			break; 
       	}            
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
            
			var table = document.getElementById('dataTable');
			var rowCount = table.rows.length; 
			
			var totjob = 0;
			for(var i = 1; i < rowCount; i++) { 
			   var rowidre = 'promojrateid'+i;
			   var coljob = document.getElementById(rowidre).value;
						
			   if (!isNaN(coljob) && (coljob != "")){
					totjob = parseFloat(totjob) + parseFloat(coljob);		
					}
				}
				document.InpJobFMas.totaljob.value = parseFloat(totjob).toFixed(4);
			}catch(e) {
				alert(e);
			}
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

function setDesc(x){

   var strprocode = document.getElementById("searchField").value
   var strURL="aja_get_prodesc.php?procode="+strprocode;
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
	      document.getElementById(x).value=req.responseText;
	     } else {
   	      alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
	     }
      }
     } 
   }
   req.open("GET", strURL, true);
   req.send(null);
}

function validateForm()
{
	var table = document.getElementById('dataTable');
	var rowCount = table.rows.length; 
		
	var mylist = new Array();	
	for(var i = 1; i < rowCount; i++) { 
	                
	    var rowidde = 'promojdescid'+i;
		var vprocdbuy = document.getElementById(rowidde).value;
       
		if (vprocdbuy != ""){
			    mylist.push(vprocdbuy); 
		}   
	}
	
	mylist.sort();
	var last = mylist[0];
	
	for (var i=1; i < mylist.length; i++) {
		if (mylist[i] == last){ 
			alert ("Duplicate Job ID Found; Job ID " + last);
			 return false;
		}	
		last = mylist[i];
	}
}

function calcAmt(vid)
{
    var vproqty = "promojrateid"+vid;
	
    var col1 = document.getElementById(vproqty).value;	
	if (col1 != ""){
		if(isNaN(col1)) {
    	   alert('Please Enter a valid number for Rate :' + col1);
    	   col1 = 0;
    	}
    	document.getElementById(vproqty).value = parseFloat(col1).toFixed(4);
    }else{
    	col1 = 0;
    	document.getElementById(vproqty).value = parseFloat(col1).toFixed(4);

    }
    
    var table = document.getElementById('dataTable');
	var rowCount = table.rows.length; 

    var totjob = 0;
	for(var i = 1; i < rowCount; i++) { 
		var rowidre = 'promojrateid'+i;
		var coljob = document.getElementById(rowidre).value;
						
		if (!isNaN(coljob) && (coljob != "")){
			totjob = parseFloat(totjob) + parseFloat(coljob);		
		}
	}
	document.InpJobFMas.totaljob.value = parseFloat(totjob).toFixed(4);
}

</script>
</head>
  
 	<!--<?php include("../sidebarm.php"); ?> -->
<body>
<?php include("../topbarm.php"); ?> 
<?php
		$sql = "select *";
        $sql .= " from pro_cd_master";
        $sql .= " where prod_code ='".$var_prodcode."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $proddesc = $row[6];
		
		$vartotal = 0;
		$sql = "SELECT prod_jobid, prod_jobrate ";
		$sql .= " FROM pro_jobmodel Where prod_code ='".$var_prodcode."'";
 		$rs_result = mysql_query($sql); 
		
		while ($rowq = mysql_fetch_assoc($rs_result)) { 
			$vartotal = $vartotal + $rowq['prod_jobrate'];
        }
     
?>
 
	<div class="contentc">

	<fieldset name="Group1" style=" width: 700px;" class="style2">
	 <legend class="title">EDIT PRODUCT JOB PAY RATE - BY PRODUCT CODE <?php echo $var_prodcode; ?>
	 &amp; JOB ID</legend>
	  <br>
	 
	  <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	    <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table style="width: 637px; height: 96px;">
	   	   <tr>
	   	    <td style="width: 14px"></td>
	  	    <td>Product Code</td>
	  	    <td>:</td>
	  	    <td>
			&nbsp;<input class="inputtxt" name="pro_code" id ="pro_code" type="text" style="width: 363px;" value="<?php echo $proddesc; ?>"></td>
			
	  	  </tr>  
	  	  <tr>
	  	   <td style="width: 14px;"></td>
	  	   <td></td>
	  	   <td></td>
	  	   <td><div id="msgcd"></div></td>
	  	  </tr>
	  	  <tr>
	  	   <td style="width: 14px"></td>
	  	   <td>Job ID</td>
	  	   <td>:</td>
	  	   <td>
		   <input class="inputtxt" name="procddesc" id ="procddescid" type="text" style="width: 363px;" value="<?php echo $proddesc; ?>"></td>
		 
	  	  </tr>
	  	   <tr>
	  	   <td style="width: 14px"></td>
	  	   <td></td>
	  	   <td></td>
	  	   <td></td>
	  	  </tr>
		  <tr>
	  	   <td style="width: 14px"></td>
	  	   <td>&nbsp;</td>
	  	   <td>&nbsp;</td>
	  	   <td>
		   &nbsp;</td>
	  	  </tr>
			<tr>
	  	   <td style="width: 14px"></td>
	  	   <td></td>
	  	   <td></td>
	  	   <td></td>
	  	  </tr>
	  	  </table>
		   <br><br>
	  	  <table id="dataTable" align="center" border="1px solid black;" class="general-table">
	  	     <thead>
	  	     <tr>
	  	     	<th class="tabheader" style="width: 15px">Sec</th>
	  	     	<th class="tabheader" style="width: 30px;">Job ID</th>
	  	        <th class="tabheader" style="width: 60px;">Job Description</th>
          		<th class="tabheader" style="width: 30px;">Rate</th>
                <th class="tabheader" style="width: 80px">Date Modified</th>
			 </tr>
         	 </thead>
         	 <?php
         	    $sql = "SELECT prod_jobid, prod_jobdesc, prod_jobrate, prod_jobsec, prod_jobdame ";
				$sql .= " FROM pro_jobmodel Where prod_code ='".$var_prodcode."' Order by prod_jobseq";
		
				$rs_result = mysql_query($sql); 
				$numi = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)) {
				
					if (empty($rowq['prod_jobdame'])){
						$rowq['prod_jobdame'] = "";
					}else{	 
						$rowq['prod_jobdame'] = date('d-m-Y', strtotime($rowq['prod_jobdame'])); 
					}

				    $var_jofdesc = str_replace("^", "'", htmlentities($rowq['prod_jobdesc']));
					echo '<tr>';
					echo '<td align="center">';
              		echo '<input class="inputtxt" name="prosec[]" id ="prosec'.$numi.'" type="text" style="width: 30px;" value="'.$rowq['prod_jobsec'].'">';
              		echo '</td>';
              		
					echo '<td>';
					echo '<select name="jobid[]" style="width: 50px" id="'.$numi.'" onchange="getState(this.value,this.id)">';
						$sql = "select jobfile_id, jobfile_desc from jobfile_master Where actvty != 'Z' ORDER BY jobfile_id ASC";
						$sql_result = mysql_query($sql);
					echo '<option value='.$rowq['prod_jobid'].'>'.$rowq['prod_jobid'].' | '.$var_jofdesc."</option>";  
					echo '<option></option>';   
						if(mysql_num_rows($sql_result)) 
						{
						while($row = mysql_fetch_assoc($sql_result)) 
						{ 
							$var_jof = str_replace("^", "'", htmlentities($row['jobfile_desc']));
							echo '<option value="'.$row['jobfile_id'].'">'.$row['jobfile_id'].' | '.$var_jof.'</option>';
						} 
					} 
					echo '</select>';
					echo '</td>';
					
					echo '<td>';
					echo '<input class="inputtxt" readonly="readonly" name="promojdesc[]" id ="promojdescid'.$numi.'" type="text" style="width: 356px" value="'.$var_jofdesc.'">';
					echo '</td>';
					
					echo '<td>';
					echo '<input class="inputtxt" name="promojrate[]" id ="promojrateid'.$numi.'" type="text" style="width: 83px" value="'.$rowq['prod_jobrate'].'" onBlur="calcAmt('.$numi.');">';
					echo '</td>';

					echo '<td align="center">';
              		echo '<input class="inputtxt" readonly="readonly" name="prodteame[]" id ="prodteame'.$numi.'" type="text" style="width: 80px;" value="'.$rowq['prod_jobdame'].'">';
              		echo '</td>';
					
					echo '</tr>';
					$numi = $numi + 1;
				}
              ?>
              </table>
		  
			 <div align="left">
				<a href="javascript:addRow('dataTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Row</span></a>
				<a href="javascript:deleteRow('dataTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>
			  </div> 
			 
			
			<table>
		  	<tr>
				<td style="width: 649px" align="center">
				 <?php
				 $locatr = "projob_rate1.php?menucd=".$var_menucode;
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
				 ?>
				<input type=submit name = "Submit" value="Update" class="butsub" style="width: 60px; height: 32px" >
				</td>
			</tr>
		 </table>
	   </form>	
	   </fieldset>
	  </div>
	  <div class="spacer"></div>
</body>

</html>
