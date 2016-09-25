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
    	$mplanrmk = $_POST['mplanrmk'];
    	$mplandte = date('Y-m-d', strtotime($_POST['mplandte']));
    	$mplanopt = $_POST['optplan'];
     	     
     	if ($mplandte <> "") {     
			
			$arrord = $_POST['matordno'];
			$arrordbuy = $_POST['matordbuy'];
			
			$strord = serialize($arrord);
            $strenc = urlencode($strord);
            
            $strbuy = serialize($arrordbuy);
            $strenb = urlencode($strbuy);

			if ($mplanopt == "P"){
				$rediuseracc = "../bom_tran/mat_plan_prodet.php?popt=".$mplanopt."&pdte=".$mplandte.'&prmk='.$mplanrmk.'&ord='.$strenc.'&ordbuy='.$strenb.'&menucd='.$var_menucode;
				echo "<script>";
				echo 'location.replace("'.$rediuseracc.'")';
				echo "</script>";
			}else{
				$rediuseracc = "../bom_tran/mat_plan_detcol.php?popt=".$mplanopt."&pdte=".$mplandte.'&prmk='.$mplanrmk.'&ord='.$strenc.'&ordbuy='.$strenb.'&menucd='.$var_menucode;
				echo "<script>";
				echo 'location.replace("'.$rediuseracc.'")';
				echo "</script>";
			}	
        }else{
			$backloc = "../bom_tran/mat_plan.php?stat=4&menucd=".$var_menucode;
           	echo "<script>";
          	echo 'location.replace("'.$backloc.'")';
           echo "</script>"; 
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

.style2 {
	margin-right: 0px;
}
</style>

<script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
<script type="text/javascript" src="../js/datetimepicker_css.js"></script>
<script type="text/javascript" src="../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../js/Parsers.js"></script>
<script type="text/javascript" src="../js/InputMask.js"></script>


<script type="text/javascript"> 

function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
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
		    case 1:
		        newcell.childNodes[0].value = "";
                newcell.childNodes[0].id = 'matordbuy'+rowCount;	
               
               	break;
		    case 2:
		      	newcell.childNodes[0].value = "";
                newcell.childNodes[0].id = 'matorddte'+rowCount;	
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

function getState(vsid, rowid)
{
	var strURL="aja_get_salesinfo.php?sono="+vsid;
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
				    var rowidde = 'matordbuy'+rowid;
				    var rowidre = 'matorddte'+rowid;
					var strrep = req.responseText;
			       
					if (strrep != ""){
						var array1 = strrep.split('^');
						if (array1[0] != ""){
						  document.getElementById(rowidde).value = array1[0];
						}
						if (array1[2] != ""){	
						  document.getElementById(rowidre).value = array1[3];
						}
						
					}else{
						document.getElementById(rowidde).value = "";
						document.getElementById(rowidre).value = "";	
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

function validateForm()
{
	var flgchk = 1;
	var x=document.forms["InpPlanMas"]["mplandte"].value;
	if (x==null || x=="")
	{
		alert("Planning Date Cannot Be Blank");
		document.InpPlanMas.mplandte.focus();
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
	
	if (mylist.length == 0) {
  		alert("Can't Making Planning Without Sales Order No.");
		document.getElementById("1").focus();
		return false;
    }
	
	mylist.sort();
	var last = mylist[0];
	
	for (var i=1; i < mylist.length; i++) {
		if (mylist[i] == last){ 
			alert ("Duplicate Sales Order No Found; Order No " + last);
			 return false;
		}	
		last = mylist[i];
	}
}

function setup() {

		document.InpPlanMas.mplanrmkid.focus();
						
 		//Set up the date parsers
        var dateParser = new DateParser("yyyy-MM-dd");
      
		//Set up the DateMasks
		var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
		var dateMask1 = new DateMask("dd-MM-yyyy", "mplandte");
		dateMask1.validationMessage = errorMessage;		
}

</script>
</head>
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
 <!-- <?php include("../sidebarm.php"); ?> -->

  <div class ="contentc">
  	<fieldset name="Group1" style=" width: 738px;" class="style2">
	 <legend class="title">MATERIAL PLANNING FROM SALES ORDER (1)</legend>
	  <br>
	   <form name="InpPlanMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" onsubmit="return validateForm()" style="width: 698px">
		<table style="width: 683px; height: 96px;">
	   	   <tr>
	   	    <td style="width: 14px"></td>
	  	    <td style="width: 136px">Remark</td>
	  	    <td style="width: 14px">:</td>
	  	    <td>
		   		<input class="inputtxt" name="mplanrmk" id ="mplanrmkid" maxlength="100" type="text" style="width: 492px;">
		   </td>
	  	  </tr>  
	  	  <tr>
	  	   <td style="width: 14px;"></td>
	  	   <td style="width: 136px"></td>
	  	   <td style="width: 14px"></td>
	  	   <td></td>
	  	  </tr>
	  	  <tr>
	  	   <td style="width: 14px"></td>
	  	   <td style="width: 136px">Document Date</td>
	  	   <td style="width: 14px">:</td>
	  	   <td> 
		   <input class="inputtxt" name="mplandte" id ="mplandte" type="text" style="width: 128px;" value="<?php  echo date("d-m-Y"); ?>">
		        <img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('mplandte','ddMMyyyy')" style="cursor:pointer">
           </td>
	  	  </tr>
	  	   <tr>
	  	   <td style="width: 14px"></td>
	  	   <td style="width: 136px"></td>
	  	   <td style="width: 14px"></td>
	  	   <td></td>
	  	  </tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td>Planning Option</td>
	  	  	<td>:</td>
	  	  	<td>
	  	  		<select name="optplan" style="width: 156px">
	  	  			<option value="P">By Product Code</option>
	  	  			<option value="C">By Color</option>
	  	  		</select>
	  	  	</td>
	  	  </tr>
		   </table>
		   <br>
	  	   <table id="dataTable" align="center" style="width: 80%" border="1px solid black;" class="general-table">
	  	     <thead>
	  	     <tr>
	  	        <th class="tabheader">Sales Order No</th>
          		<th class="tabheader">Buyer Code</th>
          		<th class="tabheader">Order Date</th>
			 </tr>
         	 </thead>
         	 <?php
         	 	$sql  = "CREATE TEMPORARY TABLE salestemp (";
    			$sql .=	" sordno VARCHAR(20), sbuycd VARCHAR(10), procd VARCHAR(20))";
   				mysql_query($sql) or die('1 '.mysql_error());
   				
   				$sql  = "Delete From Salestemp";
						mysql_query($sql) or die('2 '.mysql_error());   
			       
			       		$shardSize = 8000;
	 					$sqliq = "";		   			
	 					$k = 0;
						$sql  = "select distinct x.sordno, x.sbuycd, z.sprocd";
						$sql .= " from salesentry x, salesappr y, salesentrydet z";
						$sql .= " Where x.stat != 'CANCEL'";
						$sql .= " And x.sordno = y.sordno  and x.sbuycd = y.sbuycd";
						$sql .= " and x.sordno = z.sordno  and x.sbuycd = z.sbuycd";
						$sql .= " and y.sordno = z.sordno  and y.sbuycd = z.sbuycd";
						$sql .= " And y.app_stat = 'APPROVE'";
						$sql .= " and z.sprocd != ' '";
						$sql .= " Order by x.sorddte desc";
									       		#echo $sql."<br>";
                   		$sql_result = mysql_query($sql) or die('3 '.mysql_error());
                   		while($row = mysql_fetch_assoc($sql_result)){
                   			$odno = $row['sordno'];
                   			$odbu = $row['sbuycd'];
                   			$odpr = $row['sprocd'];
                   			        						
        					$sql2  = "select count(*) from costing_matord ";
        					$sql2 .= " where sordno ='$odno' And sbuycd='$odbu'";
        					$sql2 .= " And procd = '$odpr'";
        					$sql_result2 = mysql_query($sql2) or die('5 '.mysql_error());
        					$row2 = mysql_fetch_array($sql_result2);
        					$cntc = $row2[0];
        					if (empty($cntc)){$cntc = 0;}
                   			    
                   			if ($cntc == 0){
                   				#$sql3  = " insert into salestemp values ";
                   				#$sql3 .= " ('$odno','$odbu','$odpr')";
                   				#mysql_query($sql3) or die('6 '.mysql_error());
                   				if ($k % $shardSize == 0) {
       								if ($k != 0) {	  
        	   							mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
       								}
       								$sqliq = 'Insert Into salestemp values ';
    							}
   								$sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$odno', '$odbu', '$odpr')";
								$k = $k + 1;
                   			}	 
                   		}
                   		if (!empty($sqliq)){
							mysql_query($sqliq) or die ("Cant insert 2 : ".mysql_error());
						}
         	 	
         	 	$i=1;
         	 	do{
         	 		
         	  		echo '<tr>';		
						

                   
                   		$sql = "select distinct sordno from salestemp ";
        				$sql .= " order by 1";
        				$sql_result = mysql_query($sql);
        				
						echo '<td style="width: 40%" align="center">';
         	  			echo '<select name="matordno[]" style="width: 200px" id="'.$i.'" onchange="getState(this.value,this.id)">';
                   		echo "<option selected></option>";    
				   		if(mysql_num_rows($sql_result)) 
				   		{
				   	 		while($row = mysql_fetch_assoc($sql_result)) 
				     		{ 
					 			 echo '<option value="'.$row['sordno'].'">'.$row['sordno'].'</option>';
				 	 		} 
				   		} 
	            		echo '</select>';
                   echo '</td>';
         	  
         	  	   echo '<td style="width: 30%" align="center" class="tInput">';
         	       echo '<input class="inputtxt" readonly="readonly" name="matordbuy[]" id ="matordbuy'.$i.'" type="text" style="width: 150px; border: none;" >';  
              	   echo '</td>';
             
              	   echo '<td style="width: 30%" align="center">';
                   echo '<input class="inputtxt" readonly="readonly" name="matorddte[]" id ="matorddte'.$i.'" type="text" style="width: 150px; border: none;">';
              	   echo '</td>';
			
             	   echo '</tr>';
             	   $i++;
             }while ($i<=6);
           ?>
           </table>
           
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			
			<a href="javascript:addRow('dataTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-plus.png" alt="Add" title="Add Row"> Add Row</span></a>
			<a href="javascript:deleteRow('dataTable')" id="addRow" class="button-clean large"><span><img src="../images/icon-minus.png" alt="Add" title="Delete Row">Delete Row</span></a>
			<table>
		  	<tr>
				<td style="width: 693px" align="center">
				<?php
					$locatr = "m_mat_plan.php?menucd=".$var_menucode;
			
				 echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';

					include("../Setting/btnsave.php");
				?>
				</td>
			</tr>
			<tr>
				<td colspan="5" style="width: 693px">
				<span style="color:#FF0000">Message:</span>
				<?php
					if (isset($var_stat)){
						switch ($var_stat)
						{
						case 1:
							echo("<span>Success Process</span>");
							break;
						case 4:
							echo("<span>Process Fail; Planning Date Cannot Be Blank</span>");
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
    </div>
      <div class="spacer"></div>
</body>

</html>
