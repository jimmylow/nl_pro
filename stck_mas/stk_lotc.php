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
     	$lotcd   = mysql_real_escape_string($_POST['locacd']);
     	$lotdesc = mysql_real_escape_string($_POST['locade']);
     
     	if ($lotcd <> "") {
      
         	$sql = "INSERT INTO stk_location values 
                	('$lotcd', '$lotdesc', '$var_loginid', CURDATE(), '$var_loginid', CURDATE())";
     	 	mysql_query($sql) or die("Query 1 :".mysql_error()); 
     	 
     	 	$backloc = "../stck_mas/stk_lotc.php?stat=1&menucd=".$var_menucode;
         	echo "<script>";
         	echo 'location.replace("'.$backloc.'")';
         	echo "</script>";       
        }else{
        	$backloc = "../stck_mas/colormas.php?stat=4&menucd=".$var_menucode;
        	echo "<script>";
        	echo 'location.replace("'.$backloc.'")';
        	echo "</script>";      
        }
    }
       
    if ($_POST['Submit'] == "Delete") {
     if(!empty($_POST['lotcd']) && is_array($_POST['lotcd'])) 
     {
        
           foreach($_POST['lotcd'] as $value ) {
		    	$sql = "DELETE FROM stk_location WHERE loca_code ='".$value."'"; 
		 		mysql_query($sql) or die("Query Delete :".mysql_error()); 
		   }
		   $backloc = "../stck_mas/stk_lotc.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>"; 
       }      
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
		
		// Redirect browser
        $fname = "loca_rpt.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&dbsel=".$varrpturldb;
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

function AjaxFunction(stkcd)
{
	if (document.getElementById("locacd").value != "") {

	var strURL="aja_chk_loca.php?lotcd="+stkcd;
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
						document.getElementById("msgcd").innerHTML ="<font color=red>This Location Code Has Been Create.</font>";
					}else{
						document.getElementById("msgcd").innerHTML ="<font color=green>This Location Code Is Valid.</font>";
					}	
				} 
			}
		}	 
	}
	req.open("GET", encodeURI(strURL), true);
	req.send(null);
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
	
function chkSubmit (getdata) {
 		if (document.getElementById("locacd").value == "") {
      		alert ("Please fill in the Location Code to Continue");
      		document.InpColMas.locacd.focus();
      		return false;
     	}
     	
 		if (document.getElementById("locade").value == "") {
      		alert ("Please fill in the Description to Continue");
      		document.InpColMas.locade.focus();
      		return false;
     	}
     	
     	//Check Duplicate Location Code--------------------------------------------------------
		var flgchk = 1;
		var x = document.forms["InpColMas"]["locacd"].value;
		var strURL="aja_chk_loca.php?lotcd="+x;
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
						if (req.responseText > 0)
						{
					  		flgchk = 0;
					  		document.InpColMas.locacd.focus();
					  		alert ('This Location Code Has Been Create :'+x);
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
		   return false;
		}
		//---------------------------------------------------------------------------------------------------
}	
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="document.InpColMas.locacd.focus();">
   <?php include("../topbarm.php"); ?> 
<div class="contentc">
	<fieldset name="Group1" style=" width: 933px;" class="style2">
	 <legend class="title">STOCK LOCATION MASTER</legend>
	  <br>
	  <fieldset name="Group1" style="width: 910px; height: 160px">
	  <form name="InpColMas" method="POST" onSubmit= "return chkSubmit(this)" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 910px;">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Location Code</td>
	  	    <td>:</td> 
	  	    <td>
			<input class="inputtxt" name="locacd" id ="locacd" type="text" maxlength="10" onchange ="upperCase(this.id)" onBlur="AjaxFunction(this.value);">
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
	  	    <td style="width: 138px" class="tdlabel">Location Description</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="locade" id ="locade" type="text" maxlength="100" onchange ="upperCase(this.id)" style="width: 354px">
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
	  	   <tr>
	  	     <td></td>
	  	     <td></td>
	  	     <td></td>
	  	     <td style="width: 505px; height: 17px;" colspan="7"><span style="color:#FF0000">Message :</span>
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
				    echo("<span>Fail! Duplicated Found</span>");
  					break;
  				case 4:
				    echo("<span>Please Fill In The Data To Save</span>");
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
	   
		<br><br>
        <form name="LstColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
		 <table>
		 <tr>
		
           <td style="width: 915px; height: 38px;" align="right">
           
              <?php
		        include("../Setting/btnprint.php");
			    $msgdel = "Are You Sure Delete Selected Colour Code?";
			    include("../Setting/btndelete.php");
    	
		      ?>
           </td>
		 </tr>
		 </table>
	     
		 <table cellpadding="0" cellspacing="0" id="example"  class="display" width="100%">
         <thead>
         	<tr>
          	<th></th>
          	<th>Location Code</th>
          	<th style="width: 359px">Description</th>
          	<th></th>
          	<th></th>
         	</tr>
         	<tr>
          	<th class="tabheader" style="width: 27px">#</th>
          	<th class="tabheader" style="width: 138px">Location Code</th>
          	<th class="tabheader" style="width: 359px">Description</th>
          	<th class="tabheader" style="width: 50px">Update</th>
          	<th class="tabheader">Delete</th>
         	</tr>
         </thead>
		 <tbody>
		 <?php 
		 	$sql = "SELECT loca_code, loca_desc, modified_by, modified_on ";
			$sql .= " FROM stk_location";
    		$sql .= " ORDER BY loca_code";  
			$rs_result = mysql_query($sql); 
		 
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) { 
			
			$showdte = date('d-m-Y', strtotime($row['modified_on']));
			$urlpop = 'upd_stk_loca.php';
			echo '<tr bgcolor='.$defaultcolor.'>';
            echo '<td>'.$numi.'</td>';
            echo '<td>'.$row['loca_code'].'</td>';
            echo '<td>'.$row['loca_desc'].'</td>';
            
            if ($var_accupd == 0){
            echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
            }else{
            echo '<td align="center"><a href="'.$urlpop.'?lotcd='.$row['loca_code'].'&lotde='.$row['loca_desc'].'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
            }
            
            if ($var_accdel == 0){
            echo '<td align="center"><input type="checkbox" DISABLED  name="lotcd[]" value="'.$row['loca_code'].'" />'.'</td>';
            }else{
            echo '<td align="center"><input type="checkbox" name="lotcd[]" value="'.$row['loca_code'].'" />'.'</td>';
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
