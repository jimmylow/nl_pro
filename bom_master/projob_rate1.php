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
		$vprocode = $_POST['prod_code'];
		$vprodesc = $_POST['promodesc'];
            
		if ($vprocode <> "") {
			
			$var_sql = " SELECT count(*) as cnt from pro_jobmodel";
	      	$var_sql .= " Where prod_code = '$vprocode'";

	      	$query_id = mysql_query($var_sql) or die ("Cant Check Product Job Code");
	      	$res_id = mysql_fetch_object($query_id);
             
	      	if ($res_id->cnt > 0 ){
				$backloc = "../bom_master/projob_rate1.php?stat=5&menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
           		echo "</script>";  			
            }else{
				$var_sql = " SELECT count(*) as cnt from pro_cd_master";
				$var_sql .= " Where prod_code = '$vprocode'";

				$query_id = mysql_query($var_sql) or die ("Cant Check Product Job Code");
				$res_id = mysql_fetch_object($query_id);
             
				if ($res_id->cnt == 0 ){
					$backloc = "../bom_master/projob_rate1.php?stat=7&menucd=".$var_menucode;
           			echo "<script>";
           			echo 'location.replace("'.$backloc.'")';
           			echo "</script>"; 
				}else{	
					if(!empty($_POST['jobid']) && is_array($_POST['jobid'])) 
					{	
						foreach($_POST['jobid'] as $value ) {
							if ($value <> ""){
								$arraypre[] = $value;	
							}			
						}
				
				        if (!empty($arraypre)){
							if(count(array_unique($arraypre))<count($arraypre))
							{
								// Array has duplicates
								$backloc = "../bom_master/projob_rate1.php?stat=6&menucd=".$var_menucode;
           						echo "<script>";
           						echo 'location.replace("'.$backloc.'")';
           						echo "</script>"; 
							}
							else
							{
								// Array does not have duplicates
								$j = 1;
								$vartoday = date("Y-m-d");
								foreach($_POST['jobid'] as $row=>$jobidr ) {
									$jobid   = mysql_real_escape_string($jobidr);
									
									$sqld = "select jobfile_desc from jobfile_master";
     		 						$sqld .= " where jobfile_id ='".$jobid."'";
     		 						$sql_resultd = mysql_query($sqld);
     		 						$rowd = mysql_fetch_array($sql_resultd);
     		 						$jobdesc = stripslashes(mysql_real_escape_string($rowd['jobfile_desc']));
									
									$jobrate = mysql_real_escape_string($_POST['promojrate'][$row]);
									$jobsec  = $_POST['prosec'][$row];
									$jobdte  = $_POST['prodteame'][$row];
									
									if (empty($jobdte)){
     		 							$jobdte = "";
     		 						}else{	
     		 							$jobdte = date('Y-m-d', strtotime($jobdte)); 
									}

									if ($jobid <> ""){
										$sql = "INSERT INTO pro_jobmodel values 
												('$vprocode', '$vprodesc', '$j','$jobid', '$jobdesc', '$jobrate', '$jobsec',
												 '$jobdte','$var_loginid', '$vartoday')";
										mysql_query($sql) or die(mysql_error());
										$j = $j + 1;
									}			
								}
								$backloc = "../bom_master/projob_rate1.php?stat=1&menucd=".$var_menucode;
           						echo "<script>";
           						echo 'location.replace("'.$backloc.'")';
           						echo "</script>"; 							
           					}
						}else{
								$backloc = "../bom_master/projob_rate1.php?stat=8&menucd=".$var_menucode;
           						echo "<script>";
           						echo 'location.replace("'.$backloc.'")';
           						echo "</script>"; 						
           				}	
					}
				}
			}		
		}else{
			$backloc = "../bom_master/projob_rate1.php?stat=4&menucd=".$var_menucode;
           	echo "<script>";
            echo 'location.replace("'.$backloc.'")';
            echo "</script>"; 
		}
    }
	
	 if ($_POST['Submit'] == "Delete") {

		if(!empty($_POST['procd']) && is_array($_POST['procd'])) 
		{
            foreach($_POST['procd'] as $value ) {
				$sql = "DELETE FROM pro_jobmodel WHERE prod_code ='".$value."'"; 
				echo $sql; 			
				//mysql_query($sql); 
			}
		   //$backloc = "../bom_master/projob_rate1.php?stat=1&menucd=".$var_menucode;
           //echo "<script>";
           //echo 'location.replace("'.$backloc.'")';
           //echo "</script>";        
        }      
    }
    
     if ($_GET['p'] == "Print"){
   	 	$prcode = $_GET['pr'];
        $var_menucode  = $_GET['menucd'];

        $fname = "job_rate.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&pr=".$prcode."&dbsel=".$varrpturldb."&usernm=".$var_loginid;
        $dest .= urlencode(realpath($fname));
        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
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

<style media="all" type="text/css">
@import "../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css";
@import "../css/styles.css";
@import "../css/demo_table.css";
thead th input { width: 90% }

.style2 {
	margin-right: 0px;
}
</style>

<script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.nightly.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>

<script type="text/javascript"> 

$(document).ready(function(){
	var ac_config = {
		source: "autocomscrpro1.php",
		select: function(event, ui){
			$("#prod_code").val(ui.item.prod_code);
			$("#promodesc").val(ui.item.prod_desc);
	
		},
		minLength:1
		
	};
	$("#prod_code").autocomplete(ac_config);
});

$(document).ready(function() {
	$('#example').dataTable( {
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50,"All"]],
		"bStateSave": true,
		"bFilter": true,
		"sPaginationType": "full_numbers",
		"bAutoWidth":false,
		"aoColumns": [
    					null,
    					null,
    					null,
    					null,
    					null,
    					null,
    					null
    				]

		})
	
	.columnFilter({sPlaceHolder: "head:after",

		aoColumns: [ 
					 null,	
					 { type: "text" },
				     { type: "text" },
				     null,
				     null,
				     null,
				     null
			   ]
		});	
} );


function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

jQuery(function($) {
  
    $("tr :checkbox").live("click", function() {
        $(this).closest("tr").css("background-color", this.checked ? "#FFCC33" : "");
    });
  
});


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


function validateForm()
{
    var flgchk = 1;
	var x=document.forms["InpJobFMas"]["prod_code"].value;
	if (x==null || x=="")
	{
	alert("Product Code Must Not Be Blank");
	document.InpJobFMas.prod_code.focus()
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
					  flgchk = 0;
					  alert ('This Product Code Not Found');
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
	
	if (flgchk == 0){
		document.InpJobFMas.prod_code.focus()
	   return false;
	}
	
	var table = document.getElementById('dataTable');
	var rowCount = table.rows.length; 
		
	var mylist = new Array();	
	for(var i = 1; i < rowCount; i++) { 
	                
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

function get_desc(itemcode)
{
	var strURL="aja_get_prodesc.php?procode="+itemcode;
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
				  
					   document.InpJobFMas.promodesc.value = req.responseText;
		
				} 
			}
		}	 
	}
	req.open("GET", strURL, true);
	req.send(null);

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
 
  <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="document.InpJobFMas.prod_code.focus()">
 <?php include("../topbarm.php"); ?> 
  <div class="contentc">
  	<fieldset name="Group1" style=" width: 718px;" class="style2">
	 <legend class="title">PRODUCT JOB PAY RATE </legend>
	  <br>
	  <fieldset name="Group1" style="width: 649px;">
	  <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()">
		<table style="width: 637px; height: 96px;">
	   	   <tr>
	   	    <td style="width: 14px"></td>
	  	    <td style="width: 136px">Product Code </td>
	  	    <td style="width: 14px">:</td>
	  	    <td><p>
	  	     <input type="text" class="autosearch" name="prod_code" id="prod_code" maxlength="15" style="width: 129px" onchange ="upperCase(this.id)" onblur="get_desc(this.value)"></p>
	  	     </td>
	  	  </tr>  
	  	  <tr>
	  	   <td style="width: 14px;"></td>
	  	   <td style="width: 136px"></td>
	  	   <td style="width: 14px"></td>
	  	   <td><div id="msgcd"></div></td>
	  	  </tr>
	  	  <tr>
	  	   <td style="width: 14px"></td>
	  	   <td style="width: 136px">Product Description</td>
	  	   <td style="width: 14px">:</td>
	  	   <td>
		   <input class="inputtxt" readonly="readonly" name="promodesc" id ="promodesc" type="text" style="width: 450px;">
		   
		   </td>
	  	  </tr>
	  	   <tr>
	  	   <td style="width: 14px"></td>
	  	   <td style="width: 136px"></td>
	  	   <td style="width: 14px"></td>
	  	   <td></td>
	  	  </tr>
		  <tr>
	  	   <td style="width: 14px"></td>
	  	   <td style="width: 136px">Total Job Rate</td>
	  	   <td style="width: 14px">:</td>
	  	   <td>
		   <input readonly="readonly" name="totaljob" id ="totaljobid" type="text" style="width: 200px;" class="textnoentry1" >
		   </td>
	  	  </tr>
			<tr>
		   <td></td>
	  	   <td style="width: 14px"></td>
	  	   <td style="width: 136px"></td>
	  	   <td style="width: 14px"></td>
	  	  </tr>
	  	  </table>
		   <br><br><br>
	  	   <table id="dataTable" align="center" style="width: 588px" border="1px solid black;" class="general-table">
	  	     <thead>
	  	     <tr>
	  	     	<th class="tabheader">Sec</th>
          		<th class="tabheader" style="width: 76px;">Job ID</th>
          		<th class="tabheader" style="width: 359px;">Job Description</th>
          		<th class="tabheader" style="width: 83px;">Rate</th>
          		<th class="tabheader" style="width: 80px">Date Modified</th>  		
			 </tr>
         	 </thead>
         	 <?php
         	 $i=1;
         	 do{
         	  echo '<tr>';
         	  
         	  echo '<td align="center">';
              echo '<input class="inputtxt" name="prosec[]" id ="prosec'.$i.'" type="text" style="width: 30px;">';
              echo '</td>';
         	  
         	  echo '<td align="center">';
         	  echo '<select name="jobid[]" style="width: 50px" id="'.$i.'" onchange="getState(this.value,this.id)">';
			       $sql = "select jobfile_id,jobfile_desc from jobfile_master Where actvty != 'D' ORDER BY jobfile_id ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option selected></option>";    
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
				     	$varjobde = htmlentities($row['jobfile_desc']);
				     	$varjobde = str_replace("^", "'", $varjobde);
					  echo '<option value="'.$row['jobfile_id'].'">'.$row['jobfile_id'].' | '.$varjobde.'</option>';
				 	 } 
				   } 
	            echo '</select>';
             
              echo '</td>';
         	  
         	  echo '<td align="center" class="tInput">';
         	   echo '<input class="inputtxt" readonly="readonly" name="promojdesc[]" id ="promojdescid'.$i.'" type="text" style="width: 356px; border: none;" >';  
              echo '</td>';
             
              echo '<td align="center">';
              echo '<input class="inputtxt" name="promojrate[]" id ="promojrateid'.$i.'" type="text" style="width: 83px; text-align:right;" onBlur="calcAmt('.$i.');">';
              echo '</td>';
              
              echo '<td align="center">';
              echo '<input class="inputtxt" readonly="readonly" name="prodteame[]" id ="prodteame'.$i.'" type="text" style="width: 80px;" value="'.$rowq['prod_jobdame'].'">';
              echo '</td>';
			
             echo '</tr>';
             $i++;
             }while ($i<=7);
             ?>
           </table>
		    
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="javascript:addRow('dataTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Row</span></a>
			<a href="javascript:deleteRow('dataTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>
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
				<td colspan="5">
				<span style="color:#FF0000">Message:</span>
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
						case 8:
							echo("<span>No Job ID Selected; Not Insert!</span>");
							break;	
						case 9:
							echo("<span>Job Rate Updated Successfully. </span>");
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
		   <td style="width: 733px; height: 38px;" align="right">
              <?php
              
          		$locatr = "../bom_tran/upd_job_rate_x.php?menucd=".$var_menucode;
  				if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Update Price" class="butsub" style="width: 120px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Update Price" class="butsub" style="width: 120px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}

               $locatr = "copy_projob_rate.php?menucd=".$var_menucode;
 			   if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Copy" class="butsub" style="width: 60px; height: 32px">';
  			   }else{
   					echo '<input type="button" value="Copy" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  			   }
              
    	  	   $msgdel = "Are You Sure Delete Selected Product Code Product Model Job File List?";
    	  	   include("../Setting/btndelete.php");
    	      ?>
           </td>
		 </tr>
		 </table>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
         	<tr>
         	 <th></th>
         	 <th>Product Code </th>
         	 <th>Product Description</th>
         	 <th></th>
         	 <th></th>
		 	 <th></th>
		 	 <th></th>
         	</tr>

         	<tr>
         	 <th class="tabheader" style="width: 20px">#</th>
         	 <th class="tabheader" style="width: 100px">Product Code</th>
         	 <th class="tabheader" style="width: 200px">Product Description</th>
         	 <th class="tabheader" style="width: 20px">Detail</th>
         	 <th class="tabheader" style="width: 20px">Print</th>
         	 <th class="tabheader" style="width: 20px">Update</th>
		 	 <th class="tabheader" style="width: 20px">Delete</th>
         	</tr>
         	</thead>
		 <tbody>
		 <?php 
		   //blocked - cedric 20140922 - bcos will show more than 1 prod_code in the listing
		   // $sql = "SELECT DISTINCT prod_code, prod_desc, modified_on ";
		   // $sql .= " FROM pro_jobmodel";
    	   //	$sql .= " ORDER BY modified_on ";  
		   //	$rs_result = mysql_query($sql); 
		   //soluction : removed modified_on
		  //end blocked //
			
			$sql = "SELECT DISTINCT prod_code, prod_desc ";
		    $sql .= " FROM pro_jobmodel";
    		 
			$rs_result = mysql_query($sql); 

		 
		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) {
			
				$sql1 = "select prod_desc ";
        		$sql1 .= " from pro_cd_master";
        		$sql1 .= " where prod_code ='".htmlentities($rowq['prod_code'])."'";
        		$sql_result1 = mysql_query($sql1);
        		$row1 = mysql_fetch_array($sql_result1);
        		$proddesc = htmlentities($row1[0]);
			
				$showdte = date('d-m-Y', strtotime($rowq['modified_on']));
				$urlpop = 'upd_projob_rate.php';
				$urlvie = 'vm_projob_rate.php';
				echo '<tr>';
            	echo '<td>'.$numi.'</td>';
            	echo '<td>'.htmlentities($rowq['prod_code']).'</td>';
            	echo '<td>'.$proddesc.'</td>';
            
            	if ($var_accvie == 0){
            		echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlvie.'?procd='.htmlentities($rowq['prod_code']).'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            	}
            
            	echo '<td align="center"><a href="projob_rate1.php?p=Print&pr='.htmlentities($rowq['prod_code']).'&menucd='.$var_menucode.'" title="Print This Job Rate"><img src="../images/b_print.png" border="0" width="16" height="16" hspace="2" alt="Duplicate Job Rate" /></a></td>';
            
            	if ($var_accupd == 0){
            		echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlpop.'?procd='.htmlentities($rowq['prod_code']).'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
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
	</fieldset>
    </div>
      <div class="spacer"></div>
</body>

</html>
