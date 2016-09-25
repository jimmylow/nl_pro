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
     $probuyccd   = $_POST['probuycd'];
     $probuycde = $_POST['probuyde'];
     $probuypre = $_POST['probuycdpre'];
     
     if ($probuyccd <> "") {

         $sql = "INSERT INTO pro_buy_master values 
                ('$probuyccd', '$probuycde', '$var_loginid', CURDATE(), '$var_loginid', CURDATE(), '$probuypre')";
     	 mysql_query($sql); 
     	 
     	 $backloc = "../bom_master/pro_buy_master.php?stat=1&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
     }else{
       $backloc = "../bom_master/pro_buy_master.php?stat=4&menucd=".$var_menucode;
       echo "<script>";
       echo 'location.replace("'.$backloc.'")';
       echo "</script>";
     }
    }
       
    if ($_POST['Submit'] == "Delete") {
     if(!empty($_POST['probuyccd']) && is_array($_POST['probuyccd'])) 
     {
           foreach($_POST['probuyccd'] as $value ){
           
           	 $defarr = explode(",", $value);

             $var_probuy = $defarr[0];
             $var_propre = $defarr[2];

		     $sql = "DELETE FROM pro_buy_master WHERE pro_buy_code ='$var_probuy' and pro_buy_pre='$var_propre'"; 
		     mysql_query($sql); 
		   }
		   //$backloc = "../bom_master/pro_buy_master.php?stat=1&menucd=".$var_menucode;
          // echo "<script>";
          // echo 'location.replace("'.$backloc.'")';
         // echo "</script>";
       }      
    }
    
    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
    
        $fname = "pro_buy_master.rptdesign&__title=myReport"; 
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
thead th input { width: 90% }

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
    					null,
    					null
    				]

	})
	.columnFilter({sPlaceHolder: "head:after",

		aoColumns: [ 
					 null,	
					 { type: "text" },
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

function AjaxFunction(prefix)
{
   var probuycd = document.getElementById("probuycdid").value;	
   var strURL= "aja_chk_probuycd.php?probuycd="+probuycd+"&pre="+prefix;

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
        		document.getElementById("msgcd").innerHTML = "<font color=red> This Product Buyer Code And Prefix Has Been Use</font>";	
        	}else{
        		document.getElementById("msgcd").innerHTML = "<font color=green>This Product Buyer Code And Prefix Is Valid</font>";	
        	}
        	    		
	 	}else{
   	   		alert("There was a problem while using XMLHTTP:\n" + req.statusText);
	 	}
      }
     }
   	req.open("GET", strURL, true);
   	req.send(null);
   }
}

function validateForm()
{
    var x=document.forms["InpProCatMas"]["probuycd"].value;
	if(!x.match(/\S/)){	
		alert("Product Buyer Code Cannot Not Be Blank");
		document.InpProCatMas.probuycd.focus();
		return false;
	}
	
    var x=document.forms["InpProCatMas"]["probuycdpre"].value;
	if(!x.match(/\S/)){
		alert ('Prefix Cannot Not Be Blank');
		document.InpProCatMas.probuycdpre.focus();
		return false;
	}
	
	var chkflg = 1;
	var probuycd = document.getElementById("probuycdid").value;
	var prefix   = document.getElementById("probuycdpreid").value;		
    var strURL= "aja_chk_probuycd.php?probuycd="+probuycd+"&pre="+prefix;

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
        		chkflg = 0;
        	}else{
        		chkflg = 1;	
        	}
        	    		
	 	}else{
   	   		alert("There was a problem while using XMLHTTP:\n" + req.statusText);
	 	}
      }
     }
   	req.open("GET", strURL, false);
   	req.send(null);
   }
   
   if (chkflg == 0){
   	alert("This Product Buyer Code And Prefix Has Been Use");
   	document.InpProCatMas.probuycd.focus();
   	return false;
   }

}

</script>
</head>

 <!-- <?php include("../sidebarm.php"); ?>--> 
<body onload="document.InpProCatMas.probuycd.focus()">
  <?php include("../topbarm.php"); ?> 
  <div class="contentc">
	<fieldset name="Group1" style=" width: 767px;">
	 <legend class="title">PRODUCT PREFIX SETTING</legend>
	  <br>
	  <fieldset name="Group1" style="width: 745px; height: 168px">
	  <form name="InpProCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 727px;" onsubmit="return validateForm()">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 176px" class="tdlabel">Product Buyer Code</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="probuycd" id ="probuycdid" type="text" maxlength="3" onchange ="upperCase(this.id)" style="width: 59px">
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td style="width: 176px" class="tdlabel"></td>
	  	    <td></td> 
            <td></td> 
	   	  </tr> 
	   	   <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 176px" class="tdlabel">Description</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="probuyde" id ="probuydeid" type="text" maxlength="60" style="width: 437px" onchange ="upperCase(this.id)">
			</td>
	  	  </tr>  
	  	  <tr>
	  	    <td></td> 
	  	    <td></td>
	  	    <td></td> 
            <td></td> 
	   	  </tr> 
	   	  

           <tr>
	  	    <td></td> 
	  	    <td>Prefix</td>
	  	    <td>:</td> 
            <td>
			<input class="inputtxt" name="probuycdpre" id ="probuycdpreid" type="text" maxlength="1" onchange ="upperCase(this.id)" style="width: 37px" onBlur="AjaxFunction(this.value);"></td> 
	   	  </tr> 
          <tr>
	  	    <td></td> 
	  	    <td></td>
	  	    <td></td> 
            <td><div id="msgcd"></div></td> 
	   	  </tr> 
	  	  <tr>
	  	   <td>
	  	   </td>
	  	   <td style="width: 176px">
	  	   </td>
	  	   <td>
	  	   </td>
	  	   <td>
	  	  <?php
	  	   include("../Setting/btnsave.php");
	  	   ?>
	  	   </td>
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td></td>
	  	  <td style="height: 17px"></td>
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
	  	<?php
				$sql = "SELECT pro_buy_code, pro_buy_desc, pro_buy_pre ";
				$sql .= " FROM pro_buy_master";
    		    $sql .= " ORDER BY pro_buy_code";  
			    $rs_result = mysql_query($sql); 
        ?>
		 <br><br>
        <form name="LstColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table>
		 <tr>
		   
           <td style="width: 754px; height: 38px;" align="right">
		      <?php
    	  	   include("../Setting/btnprint.php");
		       $msgdel = "Are You Sure Delete Selected Buyer Code?";
    	  	   include("../Setting/btndelete.php"); 
		      ?>
           </td>
		 </tr>
		 </table>
		
		 <table cellpadding="0" cellspacing="0" id="example" class="display" style="width: 99%">
         <thead>
         	<tr>
         	 <th></th>
         	 <th>Product Buyer Code</th>
         	 <th>Description</th>
         	 <th>Prefix</th>
         	 <th></th>
         	 <th></th>
         	</tr>

         	<tr>
         	 <th class="tabheader" style="width: 12px">#</th>
         	 <th class="tabheader" style="width: 30px">Product Buyer Code</th>
         	 <th class="tabheader" style="width: 303px">Description</th>
         	 <th class="tabheader" style="width: 30px">Prefix</th>
         	 <th class="tabheader" style="width: 30px">Update</th>
         	 <th class="tabheader">Delete</th>
         	</tr>
         </thead>
		 <tbody>
		 <?php 
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) { 
			
			              
			$showdte = date('d-m-Y', strtotime($row['modified_on']));
			$urlpop = 'upd_probuy_mas.php';
			echo '<tr bgcolor='.$defaultcolor.'>';
            echo '<td>'.$numi.'</td>';
            echo '<td>'.$row['pro_buy_code'].'</td>';
            echo '<td>'.$row['pro_buy_desc'].'</td>';
            echo '<td>'.$row['pro_buy_pre'].'</td>';
            
            if ($var_accupd == 0){
            echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
            }else{
            echo '<td align="center"><a href="'.$urlpop.'?probuyccd='.$row['pro_buy_code'].'&probuycde='.$row['pro_buy_desc'].'&probuypre='.$row['pro_buy_pre'].'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
            }
            
            if ($var_accdel == 0){
            	echo '<td align="center"><input type="checkbox" DISABLED  name="probuyccd[]" value="'.$row['pro_buy_code'].'" />'.'</td>';
            }else{
            	$values = implode(',', $row);
            	echo '<td align="center"><input type="checkbox" name="probuyccd[]" value="'.$values.'" />'.'</td>';
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
