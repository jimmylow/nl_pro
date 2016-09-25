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
		
			if(!empty($_POST['jobid']) && is_array($_POST['jobid'])) 
			{	
				foreach($_POST['jobid'] as $value ) {
					if ($value <> ""){
						$arraypre[] = $value;	
					}			
				}
				
				if(count(array_unique($arraypre))<count($arraypre))
				{
					// Array has duplicates
					$var_stat = 6;
				}
				else
				{
					$sql = "delete from pro_jobmodel where prod_code ='".$vprocode."'";
					mysql_query($sql);
					
					// Array does not have duplicates
					foreach($_POST['jobid'] as $value ) {
						if ($value <> ""){
							$sql = "INSERT INTO pro_jobmodel values 
									('$vprocode', '$vprodesc', '$value', '$var_loginid', CURDATE())";
							mysql_query($sql);
						}			
					}
					$var_stat = 1;
				}
			}
			$backloc = "../bom_master/projob_rate.php?menucd=".$var_menucode;
        
			echo "<script>";
			echo 'location.replace("'.$backloc.'")';
			echo "</script>";		
				
		}else{
			$var_stat = 4;
		}
    }
	
	if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menudcode'];
         $backloc = "../bom_master/projob_rate.php?menucd=".$var_menucode;
        
 		 echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
    }
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>EDIT PRODUCT JOB PAY RATE</title>

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
					
					//document.getElementById(rowidde).innerHTML=req.responseText;
					//document.getElementById('dataTable').rows[rowid].cells[2].innerhtml = req.responseText;
				
					var strrep = req.responseText;
				
					if (strrep != ""){
						var array1 = strrep.split('^');
                
						var x=document.getElementById('dataTable').rows
						var y=x[rowid].cells
					
						if (array1[0] != ""){
							y[1].innerHTML = array1[0];
						}
						if (array1[2] != ""){					
							y[2].innerHTML = array1[2];
						}
						
						var table = document.getElementById('dataTable');
						var rowCount = table.rows.length; 
			
						var totjob = 0;
						for(var i = 1; i < rowCount; i++) { 
							var coljob = table.rows[i].cells[2].innerHTML;
						
							if (!isNaN(coljob) && (coljob != "")){
						
								totjob = parseFloat(totjob) + parseFloat(coljob);		
							   //totjob += parseFloat(coljob).toFixed(4);
						 
							}
						}
						document.InpJobFMas.totaljob.value = parseFloat(totjob).toFixed(4);
							
					}else{
						var x=document.getElementById('dataTable').rows
						var y=x[rowid].cells
						y[1].innerHTML = "";
						y[2].innerHTML = "";
						
						var table = document.getElementById('dataTable');
						var rowCount = table.rows.length; 
			
						var totjob = 0;
						for(var i = 1; i < rowCount; i++) { 
							var coljob = table.rows[i].cells[2].innerHTML;
							
							if (!isNaN(coljob) && (coljob != "")){
							    //if (coljob = ' ') coljob = 0;
								
								totjob = parseFloat(totjob) + parseFloat(coljob);		
							   //totjob += parseFloat(coljob).toFixed(4);
						 
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
        
		//alert(newcell.childNodes);                
		switch(newcell.childNodes[0].type) {
		case "hidden":                            
			newcell.childNodes[0].value = "";
            newcell.childNodes[0].id = rowCount;				
			break; 
		case "text":                            
			newcell.childNodes[0].value = "";
            newcell.childNodes[0].id = rowCount;				
			break;                    
		case "checkbox":                            
			newcell.childNodes[0].checked = false; 
            newcell.childNodes[0].id = rowCount;			
			break;                    
		case "select-one":         
		
			newcell.childNodes[0].selectedIndex = 0;
			newcell.childNodes[0].selectedIndex.value = "";
            newcell.childNodes[0].id = rowCount;	
			break; 
       	}            
	}
 
}

function deleteRow(tableID) {
			try {
			var table = document.getElementById(tableID);
			var rowCount = table.rows.length;

           
			for(var i=0; i<rowCount; i++) {
				var row = table.rows[i];
				
				var chkbox = row.cells[3].childNodes[0];
				
				if(null != chkbox && true == chkbox.checked) {
					if(rowCount <= 2) {
						alert("Cannot delete all the rows.");
						break;
					}
					table.deleteRow(i);
					rowCount--;
					i--;
				}
 
 
			}
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
	var x=document.forms["InpJobFMas"]["promocode"].value;
	if (x==null || x=="")
	{
	alert("Product Code Must Not Be Blank");
	return false;
	}
	alert ("Product");
	var strURL="aja_chk_procode.php?procode="+x;
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
					  alert ('This Product Code Not Found');
					  return false;
					}
				} else {
					alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
				}
			}
		}	 
	}
	req.open("GET", strURL, true);
	req.send(null);
	
	var table = document.getElementById('dataTable');
	var rowCount = table.rows.length; 
		
	var mylist = new Array();	
	for(var i = 1; i < rowCount; i++) {                 
		           
		//newcell.innerHTML = table.rows[i].cells[0].innerHTML;
		var e = document.getElementById(i);
        var vprocdbuy = e.options[e.selectedIndex].value;
        
		if (vprocdbuy != ""){
		   alert(vprocdbuy);
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
</script>
</head>
<body>
<?php
		$sql = "select *";
        $sql .= " from pro_cd_master";
        $sql .= " where prod_code ='".$var_prodcode."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $proddesc = $row[6];
		
		$vartotal = 0;
		$sql = "SELECT prod_jobid ";
		$sql .= " FROM pro_jobmodel Where prod_code ='".$var_prodcode."'";
		
 		$rs_result = mysql_query($sql); 
		
		while ($rowq = mysql_fetch_assoc($rs_result)) { 
				
			$sql1 = "select jobfile_rate from jobfile_master ";
			$sql1 .= " where jobfile_id ='".$rowq['prod_jobid']."'";
			$sql_result1 = mysql_query($sql1);
			$row1 = mysql_fetch_array($sql_result1);
			$vartotal = $vartotal + $row1[0];
        }
     
?>
	<fieldset name="Group1" style=" width: 718px;" class="style2">
	 <legend class="title">EDIT PRODUCT JOB PAY RATE - PRODUCT CODE <?php echo $var_prodcode; ?></legend>
	  <br>
	 
	  <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
	    <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table style="width: 637px; height: 96px;">
	   	   <tr>
	   	    <td style="width: 14px"></td>
	  	    <td>Product Code</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" readonly="readonly" name="prodcode" id ="prodcodeid" type="text" value="<?php echo $var_prodcode;?>" style="width: 106px">
			</td>
			</td>
	  	  </tr>  
	  	  <tr>
	  	   <td style="width: 14px;"></td>
	  	   <td></td>
	  	   <td></td>
	  	   <td><div id="msgcd"></div></td>
	  	  </tr>
	  	  <tr>
	  	   <td style="width: 14px"></td>
	  	   <td>Product Description</td>
	  	   <td>:</td>
	  	   <td>
		   <input class="inputtxt" readonly="readonly" name="procddesc" id ="procddescid" type="text" style="width: 363px;" value="<?php echo $proddesc; ?>"></td>
		   </td>
	  	  </tr>
	  	   <tr>
	  	   <td style="width: 14px"></td>
	  	   <td></td>
	  	   <td></td>
	  	   <td></td>
	  	  </tr>
		  <tr>
	  	   <td style="width: 14px"></td>
	  	   <td>Total Job Rate</td>
	  	   <td>:</td>
	  	   <td>
		   <input class="inputtxt" readonly="readonly" name="totaljob" id ="totaljobid" type="text" style="width: 200px;" value="<?php echo $vartotal; ?>">
		   </td>
	  	  </tr>
			<tr>
	  	   <td style="width: 14px"></td>
	  	   <td></td>
	  	   <td></td>
	  	   <td></td>
	  	  </tr>
	  	  </table>
		   <br/><br/><br/>
	  	   <table id="dataTable" style="100%" border>
	  	     <thead>
	  	     <tr>
          		<th class="tabheader" style="width: 76px;">Job ID </th>
          		<th class="tabheader" style="width: 359px;">Job Description</th>
          		<th class="tabheader" style="width: 69px;">Rate</th>
				<th class="tabheader" align='center'>SelectRow
				                        Delete</th>
         	 </tr>
         	 </thead>
         	 <?php
				$sql = "SELECT prod_jobid ";
				$sql .= " FROM pro_jobmodel Where prod_code ='".$var_prodcode."' Order by prod_jobid";
		
				$rs_result = mysql_query($sql); 
				$numi = 1;
				while ($rowq = mysql_fetch_assoc($rs_result)) { 
				
					$sql1 = "select jobfile_desc, jobfile_rate from jobfile_master ";
					$sql1 .= " where jobfile_id ='".$rowq['prod_jobid']."'";
					$sql_result1 = mysql_query($sql1);
					$row1 = mysql_fetch_array($sql_result1);
	
					echo '<tr>';
					echo '<td style="width: 76px">';
					echo '<select name="jobid[]" style="width: 93px" id="'.$numi.'" onchange="getState(this.value,this.id)">';
						$sql = "select jobfile_id from jobfile_master Where actvty != 'Z' ORDER BY jobfile_id ASC";
						$sql_result = mysql_query($sql);
					echo "<option selected>".$rowq['prod_jobid']."</option>";    
						if(mysql_num_rows($sql_result)) 
					{
						while($row = mysql_fetch_assoc($sql_result)) 
						{ 
						echo '<option value="'.$row['jobfile_id'].'">'.$row['jobfile_id'].'</option>';
						} 
					} 
					echo '</select>';
					echo '</td>';
					echo '<td style="width: 359px">';
					echo '<input class="inputtxt" readonly="readonly" name="promojdesc[]" id ="promojdescid'.$numi.'" type="text" style="width: 411px" value="'.$row1[0].'">';
					echo '</td>';
					echo '<td style="width: 69px">';
					echo '<input class="inputtxt" readonly="readonly" name="promojrate[]" id ="promojrateid" type="text" style="width: 83px" value="'.$row1[1].'">';
					echo '</td>';
					echo '<td><INPUT type="checkbox" name="chk"/></td>';
					echo '</tr>';
					$numi = $numi + 1;
				}
             
             ?>
           </table>
		  
			<a href="javascript:addRow('dataTable')"><img src="../images/addrow.png" alt="Add Row" title='Add Row'></a>
			<a href="javascript:deleteRow('dataTable')"><img src="../images/deleterow.png" alt="Delete Row" title='Delete Row'></a>
			
			<table>
		  	<tr>
				<td style="width: 200px"></td>
				<td style="width: 123px"></td>
				<td style="width: 12px"></td>
				<td style="width: 524px">
				<input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px" >
				<input type=submit name = "Submit" value="Update" class="butsub" style="width: 60px; height: 32px" >
				</td>
			</tr>
			<tr>
				<td style="width: 25px" colspan="5">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<span style="color:#FF0000">Message :</span>
				<?php
					if (isset($var_stat)){
						switch ($var_stat)
						{
						case 1:
							echo("<span>Success Process</span>");
							break;
						case 0:
							echo("<span>Process Fail</span>");
							break;
						case 3:
							echo("<span>Duplicated Found Or Code Number Fall In Same Range</span>");
							break;
						case 4:
							echo("<span>Please Fill In The Data To Save</span>");
							break;
						case 5:
							echo("<span>This Product Code Has Been Assign Job File ID</span>");
							break;
						case 6:
							echo("<span>Duplicate Job File ID Found; Process Fail.</span>");
							break;
						case 7:
							echo("<span>This Product Code Dost Not Exits</span>");
							break;			
						default:
							echo "";
						}
					}	
				?>
				</td>
			</tr>
	  	</table>
	   </form>	
	   </fieldset>
	      
	  <div class="spacer"></div>
	

</body>

</html>
