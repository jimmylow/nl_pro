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
     	$decd = $_POST['grpcd'];
     	$dede = $_POST['grpde'];
     	
     	if ($decd <> "") {
     		$vartoday = date("Y-m-d");       
        	$sql = "INSERT INTO wor_grpmas values 
            	   ('$decd', '$dede', '$var_loginid', '$vartoday', '$var_loginid', '$vartoday')";
     	 	mysql_query($sql) or die("Unable To Save Group Code ".mysql_error()); 
     	 
     	 	$backloc = "../workefile/wo_group_mas.php?menucd=".$var_menucode;
         	echo "<script>";
         	echo 'location.replace("'.$backloc.'")';
         	echo "</script>";       
		}
    }
       
    if ($_POST['Submit'] == "Delete") {
     if(!empty($_POST['deptcd']) && is_array($_POST['deptcd'])) 
     {
        
           foreach($_POST['deptcd'] as $value ) {
		    	$sql = "DELETE FROM wor_grpmas WHERE grpcd ='$value'"; 
		 		mysql_query($sql); 
		   }
		   $backloc = "../workefile/wo_group_mas.php?menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>"; 
       }      
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
     
		// Redirect browser
        $fname = "wo_grpmas.rptdesign&__title=myReport"; 
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
   	var strURL="aja_chk_grpcd.php?de="+x;
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
					document.getElementById("msgcd").innerHTML = "<font color='red'>This Group Code Has Been Create</font>";
		    	}else{
		    		document.getElementById("msgcd").innerHTML = "";
		    	}
		    }else{
   	      		//alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
	     	}
      }
     } 
   	}
   	req.open("GET", strURL, true);
   	req.send(null);
   }	
}

function chkSubmit () {
	if (document.getElementById("grpcd").value == "") {
      	alert ("Please fill in the Group Code to Continue");
      	document.InpGRPMas.grpcd.focus();
      	return false;
   	}
     	
	if (document.getElementById("grpde").value == "") {
      	alert ("Please fill in the Description to Continue");
      	document.InpGRPMas.deptdeid.focus();
      	return false;
   	}
   	
   	var varchk = 1;
   	x = document.getElementById("grpcd").value
   	var strURL="aja_chk_grpcd.php?de="+x;
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
   	      		alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
	     	}
      	}
   	}
   	req.open("GET", strURL, false);
   	req.send(null);

	if (varchk == 0){
		alert("This Group Code Has Been Create");
		document.InpGRPMas.grpcd.focus();
		return false;
	}	
}	
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="document.InpGRPMas.grpcd.focus();">
   <?php include("../topbarm.php"); ?> 
<div class="contentc">
	<fieldset name="Group1" style=" width: 692px;" class="style2">
	 <legend class="title">WORKER GROUP MASTER</legend>
	  <br>
	  <fieldset name="Group1" style="width: 666px; height: 128px">
	  <form name="InpGRPMas" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 641px;">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Group Code</td>
	  	    <td>:</td> 
	  	    <td>
			<input class="inputtxt" name="grpcd" id ="grpcd" type="text" maxlength="20" onchange ="upperCase(this.id)" onBlur="AjaxFunction(this.value);" style="width: 160px">
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
			<input class="inputtxt" name="grpde" id ="grpde" type="text" maxlength="100" style="width: 454px">
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
				$sql = "SELECT grpcd, grpde ";
				$sql .= " FROM wor_grpmas";
    		    $sql .= " ORDER BY grpcd";  
			    $rs_result = mysql_query($sql); 
        ?>
		<br><br>
        <form name="LstColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
		 <table>
		 <tr>
		
           <td style="width: 915px; height: 38px;" align="right">
           
              <?php
		        include("../Setting/btnprint.php");
			    $msgdel = "Are You Sure Delete Selected Group Code?";
			    include("../Setting/btndelete.php");
    	
		      ?>
           </td>
		 </tr>
		 </table>
	     
		 <table cellpadding="0" cellspacing="0" id="example"  class="display" width="100%">
         <thead>
         	<tr>
          	<th style="width: 27px"></th>
          	<th style="width: 91px">Group Code</th>
          	<th style="width: 506px">Description</th>
          	<th></th>
          	<th></th>
         	</tr>
         	<tr>
          	<th class="tabheader" style="width: 27px">#</th>
          	<th class="tabheader" style="width: 91px">Group Code</th>
          	<th class="tabheader" style="width: 506px">Description</th>
          	<th class="tabheader" style="width: 50px">Update</th>
          	<th class="tabheader">Delete</th>
         	</tr>
         </thead>
		 <tbody>
		 <?php 
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) {
				$deptcd = htmlentities($row['grpcd']);
				$deptde = htmlentities($row['grpde']); 
 
				$urlpop = 'upd_grpmas.php?deptcd='.$deptcd."&deptde=".$deptde.'&menucd='.$var_menucode;
				echo '<tr>';
            	echo '<td style="width: 27px">'.$numi.'</td>';
            	echo '<td style="width: 91px">'.$deptcd.'</td>';
            	echo '<td style="width: 506px">'.$deptde.'</td>';
            
            	if ($var_accupd == 0){
            		echo '<td align="center"><a href="#" title="You Are Not Authorice To Update The Group Code">[EDIT]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlpop.'">[EDIT]</a></td>';
            	}
            
            	if ($var_accdel == 0){
            		echo '<td align="center"><input type="checkbox" DISABLED  name="deptcd[]" value="'.$deptcd.'" title="You Are Not Authorice To Update The Group Code"/>'.'</td>';
            	}else{
            		echo '<td align="center"><input type="checkbox" name="deptcd[]" value="'.$deptcd.'" />'.'</td>';
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
