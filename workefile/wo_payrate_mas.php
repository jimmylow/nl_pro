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
     	$decd = $_POST['paycd'];
     	$dede = $_POST['payde'];
     	
     	if ($decd <> "") {
     		$vartoday = date("Y-m-d");       
        	$sql = "INSERT INTO wor_payrate values 
            	   ('$decd', '$dede', '$var_loginid', '$vartoday', '$var_loginid', '$vartoday')";
     	 	mysql_query($sql) or die("Unable To Save Payrate Code ".mysql_error()); 
     	 
     	 	$backloc = "../workefile/wo_payrate_mas.php?menucd=".$var_menucode;
         	echo "<script>";
         	echo 'location.replace("'.$backloc.'")';
         	echo "</script>";       
		}
    }
       
    if ($_POST['Submit'] == "Delete") {
     if(!empty($_POST['paycd']) && is_array($_POST['paycd'])) 
     {
        
           foreach($_POST['paycd'] as $value ) {
		    	$sql = "DELETE FROM wor_payrate WHERE paycode ='$value'"; 
		 		mysql_query($sql); 
		   }
		   $backloc = "../workefile/wo_payrate_mas.php?menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>"; 
       }      
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
     
		// Redirect browser
        $fname = "wo_payrate.rptdesign&__title=myReport"; 
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
@import "../css/styles.css";
@import "../css/demo_table.css";

.style2 {
	margin-right: 8px;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.nightly.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>

<script type="text/javascript" charset="utf-8"> 
$(document).ready(function() {
	$('#example').dataTable( {
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50,"All"]],
		"bStateSave": true,
		"bFilter": true,
		"sPaginationType": "full_numbers",
		"aoColumns": [
    			null,
    			null,
    			null,
    			{ "bSortable": false },
    			{ "bSortable": false }
    	]
	})
	.columnFilter({sPlaceHolder: "head:after",

		aoColumns: [ 
					 null,	
					 { type: "text" },
				     { type: "text" },
				     null,
				     null
				   ]
		});
} );


jQuery(function($) {
  
    $("tr :checkbox").live("click", function() {
        $(this).closest("tr").css("background-color", this.checked ? "#FFCC33" : "");
    });
  
});				

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

function AjaxFunction(x){

   if (x != ""){	
   	var strURL="aja_chk_payrcd.php?de="+x;
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
   	        	if (req.responseText == 1){    	
					document.getElementById("msgcd").innerHTML = "<font color='red'>This Payrate Code Has Been Create</font>";
		    	}else{
		    		document.getElementById("msgcd").innerHTML = "";
		    	}
		    }else{
   	      		alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
	     	}
      }
     } 
   	}
   	req.open("GET", strURL, true);
   	req.send(null);
   }	
}

function chkSubmit () {
	if (document.getElementById("paycd").value == "") {
      	alert ("Please fill in the Payrate Code to Continue");
      	document.InpPayMas.paycd.focus();
      	return false;
   	}
     	
	if (document.getElementById("payde").value == "") {
      	alert ("Please fill in the Description to Continue");
      	document.InpPayMas.payde.focus();
      	return false;
   	}
   	
   	var varchk = 1;
   	x = document.getElementById("paycd").value
   	var strURL="aja_chk_payrcd.php?de="+x;
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
   	        		if (req.responseText == 1){    	
						varchk = 0;
		    		}
		  		}	
		    }else{
   	      		//alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
	     	}
      	}
   	}
   	req.open("GET", strURL, false);
   	req.send(null);

	if (varchk == 0){
		alert("This Payrate Code Has Been Create");
		document.InpPayMas.paycd.focus();
		return false;
	}	
	
}	
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="document.InpPayMas.paycd.focus();">
   <?php include("../topbarm.php"); ?> 
<div class="contentc">
	<fieldset name="Group1" style=" width: 692px;" class="style2">
	 <legend class="title">WORKER PAYRATE MASTER</legend>
	  <br>
	  <fieldset name="Group1" style="width: 666px; height: 128px">
	  <form name="InpPayMas" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 641px;">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Payrate Code</td>
	  	    <td>:</td> 
	  	    <td>
			<input class="inputtxt" name="paycd" id ="paycd" type="text" maxlength="10" onchange ="upperCase(this.id)" onBlur="AjaxFunction(this.value);">
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td style="width: 138px" class="tdlabel"></td>
	  	    <td></td> 
            <td><div id="msgcd"></div></td> 
	   	  </tr> 
	   	   <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Description</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="payde" id ="payde" type="text" maxlength="100" style="width: 454px">
			</td>
	  	  </tr>  
	  	  <tr>
	  	   <td>
	  	   </td>
	  	   <td>
	  	   </td>
	  	   <td>
	  	   </td>
	  	   <td>
	  	   <?php
	  	   include("../Setting/btnsave.php");

	  	   ?>
	  	   </td>
	  	  </tr>
	  	   <tr><td></td></tr>
	  	
	  	</table>
	   </form>	
	   </fieldset>
	  	<?php
				$sql = "SELECT paycode, paydesc ";
				$sql .= " FROM wor_payrate";
    		    $sql .= " ORDER BY paycode";  
			    $rs_result = mysql_query($sql); 
        ?>
		<br><br>
        <form name="LstColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
		 <table>
		 <tr>
		
           <td style="width: 915px; height: 38px;" align="right">
           
              <?php
		        include("../Setting/btnprint.php");
			    $msgdel = "Are You Sure Delete Selected Payrate Code?";
			    include("../Setting/btndelete.php");
    	
		      ?>
           </td>
		 </tr>
		 </table>
	     
		 <table cellpadding="0" cellspacing="0" id="example"  class="display" width="100%">
         <thead>
         	<tr>
          	<th style="width: 27px"></th>
          	<th style="width: 91px">Payrate Code</th>
          	<th style="width: 506px">Description</th>
          	<th></th>
          	<th></th>
         	</tr>
         	<tr>
          	<th class="tabheader" style="width: 27px">#</th>
          	<th class="tabheader" style="width: 91px">Payrate Code</th>
          	<th class="tabheader" style="width: 506px">Description</th>
          	<th class="tabheader" style="width: 50px">Update</th>
          	<th class="tabheader">Delete</th>
         	</tr>
         </thead>
		 <tbody>
		 <?php 
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) {
				$pacode = htmlentities($row['paycode']);
				$padesc = htmlentities($row['paydesc']); 
 
				$urlpop = 'upd_payrmas.php?cd='.$pacode."&de=".$padesc.'&menucd='.$var_menucode;
				echo '<tr>';
            	echo '<td style="width: 27px">'.$numi.'</td>';
            	echo '<td style="width: 91px">'.$pacode.'</td>';
            	echo '<td style="width: 506px">'.$padesc.'</td>';
            
            	if ($var_accupd == 0){
            		echo '<td align="center"><a href="#" title="You Are Not Authorice To Update The Payrate Code">[EDIT]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlpop.'">[EDIT]</a></td>';
            	}
            
            	if ($var_accdel == 0){
            		echo '<td align="center"><input type="checkbox" DISABLED  name="paycd[]" value="'.$pacode.'" title="You Are Not Authorice To Update The Payrate Code"/>'.'</td>';
            	}else{
            		echo '<td align="center"><input type="checkbox" name="paycd[]" value="'.$pacode.'" />'.'</td>';
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
