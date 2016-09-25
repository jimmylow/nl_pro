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

    }
   
   	if ($_POST['Submit'] == "Print") {

		if(!empty($_POST['srefno']) && is_array($_POST['srefno'])) 
		{
       		$sql  = " Delete from tmpstck_lbl Where usernm = '$var_loginid'";
   			mysql_query($sql) or die("Unable To Delete Temp Table".mysql_error());
	
			foreach($_POST['srefno'] as $row=>$refno ) {
				$mrefno = $refno;
				$mseqno = $_POST['seqno'][$row];
				$mitmcd = $_POST['sitmcd'][$row];
					
				if ($mitmcd <> "" && $mrefno <> ""){
				
				   $sql  = "select * from stck_lbl ";
				   $sql .= " Where refno  = '$mrefno'";
				   $sql .= " And sub_code = '$mitmcd'";
                   $sql_result = mysql_query($sql);
                       
				   while($row = mysql_fetch_assoc($sql_result)) 
				   {  
				   	  $sitmcd =	$row['sub_code'];
				   	  $srefno =	$row['refno'];
				   	  $sreopt =	$row['refopt'];
				   	  $sfcopy =	$row['refcopy'];
				   	  $slblse =	$row['lblser'];
				   	  $slblsq =	$row['lblserqty'];
				   	  $sdtepr =	$row['datepri'];
				   	  $stotiq =	$row['totq'];
				   	  $sponum =	$row['sponum'];
				   	  $sdonum =	$row['sdonum'];
				   	  
					  $sqli = "INSERT INTO tmpstck_lbl values 
				 	   		  ('$sitmcd', '$srefno', '$sreopt', '$sfcopy','$slblse','$slblsq','$sdtepr','$stotiq', '$var_loginid', 
				 	   		   '$sponum', '$sdonum')";
					  mysql_query($sqli) or die("Can't Insert Temporily Table :".mysql_error());;
				   }  
           		}	
			}
			
			// Redirect browser
        	$fname = "stk_lbl.rptdesign&__title=myReport"; 
        	$dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&dbsel=".$varrpturldb."&usernm=".$var_loginid;
        	$dest .= urlencode(realpath($fname));

        	//header("Location: $dest" );
        	echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
		}
			
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

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

function addRow(tableID) {

	var browserName=navigator.appName; 

	if (browserName == 'Netscape'){
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length; 
		var row = table.insertRow(rowCount);             
		var colCount = table.rows[0].cells.length;             

		for(var i = 0; i < colCount; i++) { 
		
			var newcell = row.insertCell(i);                 
			newcell.innerHTML = table.rows[rowCount-1].cells[i].innerHTML;
		
			switch(i){
			case 0:
		    	newcell.childNodes[1].value = rowCount;
		    	newcell.childNodes[1].id = "sno"+rowCount;
		    	break;	
		 	case 1:
		        newcell.childNodes[1].value = "";
                newcell.childNodes[1].id = rowCount;	
               	break;
            case 2:
				table.rows[rowCount].cells['2'].innerHTML = "";
            	table.rows[rowCount].cells['2'].id = "assiitmcd"+rowCount;
				
                break; 	
         	case 3:
		      	newcell.childNodes[1].value = "";
                newcell.childNodes[1].id = 'snoolbl'+rowCount;	
                break;    
			}    	             
		}
	}else{
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length; 
		var row = table.insertRow(rowCount);             
		var colCount = table.rows[0].cells.length;             

		for(var i = 0; i < colCount; i++) {                 
			var newcell = row.insertCell(i);                 
			newcell.innerHTML = table.rows[rowCount-1].cells[i].innerHTML;

			switch(i){
			case 0:
		    	newcell.childNodes[0].value = rowCount;
		    	newcell.childNodes[0].id = "sno"+rowCount;
		    	break;	
		 	case 1:
		        newcell.childNodes[0].value = "";
                newcell.childNodes[0].id = rowCount;	
               	break;
             case 2:
				table.rows[rowCount].cells['2'].innerHTML = "";
            	table.rows[rowCount].cells['2'].id = "assiitmcd"+rowCount;
				break;				
         	case 3:
		      	newcell.childNodes[0].value = "";
                newcell.childNodes[0].id = 'snoolbl'+rowCount;	
                break;    
			}    	             
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

function chkrefcre(refnop, refid){
	
     var cellid = "assiitmcd"+refid;
	//var cel = document.getElementById(cellid).childNodes[0].type;

	//if (typeof cel == 'undefined'){
		if (refnop != ""){
			var itmid = "sitmcd"+refid;
			var cellid = "assiitmcd"+refid;
	
			var strURL="aja_get_chkref.php?ref="+refnop+"&pid="+refid;
			var req = getXMLHTTP();
			
			var chkflg = 0;
    		if (req)
			{
				req.onreadystatechange = function()
				{
					if (req.readyState == 4)
					{
						// only if "OK"
						
						if (req.status == 200)
						{
							if (req.responseText == 0){
						 		 alert("This Reference No. Not Create Label Yet : "+refnop);
						 		 document.getElementById(cellid).innerHTML = "";
								 chkflg = 1;
							}else{
								  var idtd = 'assiitmcd'+refid;
								  document.getElementById(idtd).innerHTML=req.responseText;	 					
							}
						} 
					}
				}	 
			}
			req.open("GET", strURL, false);
			req.send(null);
			if (chkflg == 1){
				 document.getElementById(refid).focus();	
			}
 	}		
}

function getnooflbl(itmcd, itmcdid, rowid){
	if (itmcd != ""){
		var lblnoid = "snoolbl"+rowid;
		var refnop  = document.getElementById(rowid).value;		
		var strURL  ="aja_get_lblno.php?ref="+refnop+"&titm="+itmcd;
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
						document.getElementById(lblnoid).value = req.responseText;
						calctotlbl();
					} 
				}
			}	 
		}
		req.open("GET", strURL, true);
		req.send(null);
			
	}
	
}

function calctotlbl(){
	var table = document.getElementById('dataTable');
	var rowCount = table.rows.length; 
	var fita4 = 30;
	
	var ttotlbl = 0;
	for(var i = 1; i < rowCount; i++) { 

		 var lblnoid = "snoolbl"+i;

		 var nolbl = document.getElementById(lblnoid).value;					
		
		 if (!isNaN(nolbl) && (nolbl != "")){
				ttotlbl = parseFloat(ttotlbl) + parseFloat(nolbl);		
		 }
	}
	document.LstDetMas.totlbl.value = parseFloat(ttotlbl).toFixed(0);
	
	var pagea4 = ttotlbl / fita4;
	
	if (ttotlbl <= 30){
		document.LstDetMas.tota4.value = 1;
	}else{	
		if (pagea4 == 0){
			document.LstDetMas.tota4.value = 1;
		}else{
			pagea4 = parseFloat(pagea4).toFixed(0);
			if ((pagea4 % fita4) != 0){
				document.LstDetMas.tota4.value = parseInt(pagea4) + 1;
			}else{
				document.LstDetMas.tota4.value = pagea4;
			}
		}
	}	
}

function validateForm()
{	
    var table = document.getElementById('dataTable');
	var rowCount = table.rows.length; 
	var ttotcnt = 0;
	
	for(var i = 1; i < rowCount; i++) { 
		 var nolbl = document.getElementById(i).value;					
		 
		 if (nolbl != ""){
				ttotcnt = parseInt(ttotcnt) + 1;		
		 }
	}

	if (ttotcnt == 0)
	{
		alert("Cant Print With Empty Table");
		document.getElementById('1').focus();
		return false;
	}

	var table = document.getElementById('dataTable');
	var rowCount = table.rows.length; 
	var chkflg = 0;
		
	for(var i = 1; i < rowCount; i++) { 
		var refnop = document.getElementById(i).value;
		var refid = i;
		
		if (refnop != ""){
			var strURL="aja_chk_chkref.php?ref="+refnop+"&pid="+i;
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
							if (req.responseText == 0){
						 		 alert("This Reference No. Not Create Label Yet : "+refnop);
								 chkflg = 1;	 
							}
						} 
					}
				}	 
			}	
		}
	}		
	req.open("GET", strURL, false);
	req.send(null);
	if (chkflg == 1){
		document.getElementById(refid).focus();
		return false;
    }
    
	//--------------CountTable Row Length Got Value------------
	var table = document.getElementById('dataTable');
	var rowCount = table.rows.length; 
	
	var rowCalc = 0;
	for(var i = 1; i < rowCount; i++){
		var refid = i;
    	var refcd = document.getElementById(refid).value;
		if (refcd != ""){
    			rowCalc = rowCalc + 1;
		}	
	}	
	//---------------------------------------------------------     
	
  	//--------------check the list got intergriti column-------
	for(var i = 1; i <= rowCalc; i++){
		var itmid = "sitmcd"+i; 
		var refid = i;
		
		var itmcd = document.getElementById(itmid).value;
		var refcd = document.getElementById(refid).value;
		
		if (itmcd == "" || refcd == ""){
			alert("Cant Proceed With Empty Sub Code With Ref No");
			document.getElementById(refid).focus();
			return false;
		}
	}
    //---------------------------------------------------------

  	//-------------------CHeck duplicat ref no and item code--------------------------	  	
	//var mylist1 = new Array();
	//var mylist2 = new Array();
	
	//for(var i = 1; i <= rowCalc; i++) { 
	     
    //    var iditm = "sitmcd"+i; 
        
	//	var refnoo = document.getElementById(i).value;
	//	var itemno = document.getElementById(iditm).value;

	//	if (refnoo != ""){
	//		  mylist1[i] = refnoo; 
	//	}
	//	if (itemno != ""){
	//		  mylist2[i] = itemno; 
	//	}   
	//}
		
	//mylist1.sort();
	//mylist2.sort();
	//var last1 = mylist1[0];
	//var last2 = mylist2[0];
	
	//for (var i=1; i < mylist1.length; i++) {

	//	if (mylist1[i] == last1){ 
	//		if (mylist2[i] == last2){ 
	//			alert ("Duplicate Ref No & Item No Found; RefNo " +last2);
	//			return false;
	//		}
	//		last2 = mylist2[i];	
	//	}	
	//	last1 = mylist1[i];
	//}
	//-------------------------------------------------------------------------------------

   
}

</script>
</head>
 
  <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="document.getElementById('1').focus();">
 <?php include("../topbarm.php"); ?> 
  <div class="contentc">
  	<fieldset name="Group1" style=" width: 627px;" class="style2">
	 <legend class="title">STOCK LABEL PRINTING</legend>
	 <form name="LstDetMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return validateForm()">
	 
	  	   <table id="dataTable" style="width: 608px" border="1px solid black;" class="general-table">
	  	     <thead>
	  	     <tr>
	  	        <th class="tabheader" style="width: 40px;">No</th>
          		<th class="tabheader" style="width: 170px;">Ref No</th>
          		<th class="tabheader" style="width: 200px;">Item Sub Code</th>
          		<th class="tabheader" style="width: 100px;">No of Label</th>
			 </tr>
         	 </thead>
         	 <?php
         	 $i=1;
         	 do{
         	 ?>
         	  	<tr>
         	  		<td>
         	  		<input class="inputtxt" readonly="readonly" name="seqno[]" id ="<?php echo "sno".$i; ?>" type="text" style="width: 40px; text-align:center" value="<?php echo $i; ?>"> 
         	  		</td>
         	  		<td align="center">
					<input class="inputtxt" name="srefno[]" type="text" id="<?php echo $i; ?>"  maxlength="50" onchange="upperCase(this.id)" style="width: 170px" onblur="chkrefcre(this.value, this.id)">
					</td>
					<td id="<?php echo "assiitmcd".$i; ?>">				
					</td>
					<td>
					<input class="inputtxt" name="snoolbl[]" type="text" id="<?php echo "snoolbl".$i; ?>" readonly="readonly" style="width: 100px; text-align:right;">
					</td>
                </tr>
             <?php   
             $i++;
             }while ($i<=7);
             ?>
           </table>
		
			<a href="javascript:addRow('dataTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row">Add Row</span></a>
			<a href="javascript:deleteRow('dataTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>
		  
		   <table style="width:608px">
		   		<tr>
		   			<td style="width:40px"></td>
		   			<td style="width:170px"></td>
		   			<td style="width:224px; text-align:right;">Total Label :</td>
		   			<td style="width:100px">
		   			<input name="totlbl" type="text" id="totlbl" readonly="readonly" style="width: 100px; text-align:right" class="textnoentry1">
		   			</td>
		   		</tr>
		   		<tr>
		   			<td style="width:40px"></td>
		   			<td style="width:170px"></td>
		   			<td style="width:224px; text-align:right;">Page :</td>
		   			<td style="width:100px">
		   			<input name="tota4" type="text" id="tota4" readonly="readonly" style="width: 100px; text-align:right" class="textnoentry1">
		   			</td>
		   		</tr>
		   </table>	
	  		<table style="width:608px">
	  			<tr>
	  				<td colspan="4" align="center">
	  					<input type=submit name = "Submit" value="Print" class="butsub" style="width: 60px; height: 32px" >
	  				</td>
	  			</tr>
	  		</table>
		</form>
		 </fieldset>
    </div>
      <div class="spacer"></div>
</body>

</html>
