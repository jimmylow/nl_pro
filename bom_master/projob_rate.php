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
      include("../Setting/ChqAuth.php");
    }
   
    if ($_POST['Submit'] == "Save") {
		$vprocode = $_POST['promocode'];
		$vprodesc = $_POST['promodesc'];
            
		if ($vprocode <> "") {
			
			$var_sql = " SELECT count(*) as cnt from pro_jobmodel";
	      	$var_sql .= " Where prod_code = '$vprocode'";

	      	$query_id = mysql_query($var_sql) or die ("Cant Check Product Job Code");
	      	$res_id = mysql_fetch_object($query_id);
             
	      	if ($res_id->cnt > 0 ){
				$var_stat = 5;
			}else{
				$var_sql = " SELECT count(*) as cnt from pro_cd_master";
				$var_sql .= " Where prod_code = '$vprocode'";

				$query_id = mysql_query($var_sql) or die ("Cant Check Product Job Code");
				$res_id = mysql_fetch_object($query_id);
             
				if ($res_id->cnt == 0 ){
					$var_stat = 7;
				}else{	
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
				}
			}		
		}else{
			$var_stat = 4;
		}
    }
	
	 if ($_POST['Submit'] == "Delete") {

		if(!empty($_POST['procd']) && is_array($_POST['procd'])) 
		{
            foreach($_POST['procd'] as $value ) {
				$sql = "DELETE FROM pro_jobmodel WHERE prod_code ='".$value."'"; 
			
				mysql_query($sql); 
			}
		   $var_stat = 1;
       }      
    }
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>PRODUCT CATEGORY MASTER</title>

<link rel="stylesheet" href="../css/autocomplete.css" type="text/css" media="screen">
	
<style media="all" type="text/css">
@import "../css/styles.css";
@import "../css/demo_table.css";

.style2 {
	margin-right: 0px;
}
</style>

<script type="text/javascript" language="javascript" src="../js/dimensions.js"></script>
<script type="text/javascript" language="javascript" src="../js/autocomplete.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>

<script type="text/javascript"> 
$(function(){
	    setAutoComplete("searchField", "results",  "autocomscrpro.php?part=");
});

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
	<fieldset name="Group1" style=" width: 718px;" class="style2">
	 <legend class="title">PRODUCT JOB PAY RATE </legend>
	  <br>
	  <fieldset name="Group1" style="width: 649px;">
	  <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
		<table style="width: 637px; height: 96px;">
	   	   <tr>
	   	    <td style="width: 14px"></td>
	  	    <td>Product Code <span style="color:#FF0000">*</span></td>
	  	    <td>:</td>
	  	    <td><p id="auto">
			<input class="inputtxt" name="promocode" id="searchField" type="text" maxlength="15" style="width: 109px" onchange ="upperCase(this.id)">
			</p>
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
		   <input class="inputtxt" readonly="readonly" name="promodesc" id ="jobfrateid" type="text" style="width: 450px;" onfocus="setDesc(this.id)">
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
		   <input class="inputtxt" readonly="readonly" name="totaljob" id ="totaljobid" type="text" style="width: 200px;" >
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
	  	   <table id="dataTable" align="center" style="width: 588px" border>
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
         	 $i=1;
         	 do{
         	  echo '<tr>';
         	  echo '<td style="width: 76px">';
         	    echo '<select name="jobid[]" style="width: 93px" id="'.$i.'" onchange="getState(this.value,this.id)">';
			       $sql = "select jobfile_id from jobfile_master Where actvty != 'Z' ORDER BY jobfile_id ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option selected></option>";    
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
              echo '<input class="inputtxt" readonly="readonly" name="promojdesc[]" id ="promojdescid'.$i.'" type="hidden" style="width: 411px">';
              echo '</td>';
              echo '<td style="width: 69px">';
              echo '<input class="inputtxt" readonly="readonly" name="promojrate[]" id ="promojrateid" type="hidden" style="width: 83px">';
              echo '</td>';
			  echo '<td><INPUT type="checkbox" name="chk"/></td>';
             echo '</tr>';
             $i++;
             }while ($i<=6);
             ?>
           </table>
		    
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="javascript:addRow('dataTable')"><img src="../images/addrow.png" alt="Add Row" title='Add Row'></a>
			<a href="javascript:deleteRow('dataTable')"><img src="../images/deleterow.png" alt="Delete Row" title='Delete Row'></a>
			
			<table>
		  	<tr>
				<td style="width: 200px"></td>
				<td style="width: 123px"></td>
				<td style="width: 12px"></td>
				<td style="width: 524px">
				<?php
					include("../Setting/btnsave.php");
				?>
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
	   
	   
	    <br/><br/>
        <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table>
		 <tr>
		   <td style="width: 97px; height: 38px;"></td>
           <td style="width: 35px; height: 38px;"></td>
           <td style="width: 295px; height: 38px;"></td>
           <td style="width: 236px; height: 38px;"></td>
          
           <td style="width: 50px; height: 38px;">
              <?php
    	  	   $msgdel = "Are You Sure Delete Selected Product Code Product Model Job File List?";
    	  	   include("../Setting/btndelete.php");
    	      ?>
           </td>
		 </tr>
		 </table>
		 <table cellpadding="0" cellspacing="0" border id="example" class="display" width="100%">
         <thead><tr>
          <th class="tabheader" style="width: 27px">#</th>
          <th class="tabheader" style="width: 100px">Product Code </th>
          <th class="tabheader" style="width: 97px">Modified By</th>
          <th class="tabheader" style="width: 91px">Modified On</th>
          <th class="tabheader" style="width: 64px">Detail</th>
          <th class="tabheader" style="width: 64px">Update</th>
		  <th class="tabheader" style="width: 64px">Delete</th>
         </tr></thead>
		 <tbody>
		 <?php 
		    $sql = "SELECT DISTINCT prod_code, modified_by, modified_on ";
		    $sql .= " FROM pro_jobmodel";
    		$sql .= " ORDER BY prod_code";  
			$rs_result = mysql_query($sql); 
		 
		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 
			
			
			$showdte = date('Y-m-d', strtotime($rowq['modified_on']));
			$urlpop = 'upd_projob_rate.php';
			$urlvie = 'vm_projob_rate.php';
			echo '<tr bgcolor='.$defaultcolor.'>';
            echo '<td>'.$numi.'</td>';
            echo '<td align="center">'.htmlentities($rowq['prod_code']).'</td>';
            echo '<td>'.$rowq['modified_by'].'</td>';
            echo '<td>'.$showdte.'</td>';
            
            if ($var_accvie == 0){
            echo '<td align="center"><a target="frame1" href="#">[VIEW]</a>';'</td>';
            }else{
            echo '<td align="center"><a target="frame1" href="'.$urlvie.'?procd='.htmlentities($rowq['prod_code']).'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            }

            
            if ($var_accupd == 0){
            echo '<td align="center"><a target="frame1" href="#">[EDIT]</a>';'</td>';
            }else{
            echo '<td align="center"><a target="frame1" href="'.$urlpop.'?procd='.htmlentities($rowq['prod_code']).'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
            }
            
            if ($var_accdel == 0){
              echo '<td align="center"><input type="checkbox" DISABLED  name="procd[]" value="'.htmlentities($rowq['prod_code']).'" />'.'</td>';
            }else{
             
              echo '<td align="center"><input type="checkbox" name="procd[]" value="'.htmlentities($rowq['prod_code']).'" />'.'</td>';
            }
            echo '</tr>';
           
            $numi = $numi + 1;
			}
		 ?>
		 </tbody>
		 </table>
		</form>
	   
	  <div class="spacer"></div>
	</fieldset>

</body>

</html>
