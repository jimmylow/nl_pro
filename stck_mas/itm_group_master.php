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
     $itmgrpcd   = $_POST['itmgrpcd'];
     $itmgrpde= $_POST['itmgrpde'];
     if ($itmgrpcd <> "") {

      $var_sql = " SELECT count(*) as cnt from item_group_master ";
      $var_sql .= " WHERE itm_grp_cd = '$itmgrpcd'";

      $query_id = mysql_query($var_sql) or die ("Cant Check Group Code");
      $res_id = mysql_fetch_object($query_id);

      if ($res_id->cnt > 0 ) {
	     $backloc = "../stck_mas/itm_group_master.php?stat=3&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
      }else {
     
         $sql = "INSERT INTO item_group_master values 
                ('$itmgrpcd', '$itmgrpde', '$var_loginid', CURDATE(), '$var_loginid', CURDATE())";

     	 mysql_query($sql); 
     	 $backloc = "../stck_mas/itm_group_master.php?stat=1&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
      } 
     }else{
       $backloc = "../stck_mas/itm_group_master.php?stat=4&menucd=".$var_menucode;
       echo "<script>";
       echo 'location.replace("'.$backloc.'")';
       echo "</script>";
     }
    }
       
    if ($_POST['Submit'] == "Delete") {
     if(!empty($_POST['itmgrpcd']) && is_array($_POST['itmgrpcd'])) 
     {
           foreach($_POST['itmgrpcd'] as $value ) {
		    $sql = "DELETE FROM item_group_master WHERE itm_grp_cd ='".$value."'"; 
		 	mysql_query($sql); 
		   }
		   $backloc = "../stck_mas/itm_group_master.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";
       }      
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
    
   		  $fname = "itmgrp_rpt.rptdesign&__title=myReport"; 
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

.style2 {
	margin-right: 0px;
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
			
		} )
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

function AjaxFunction(grpcd)
{
      
		var httpxml;
		try	{
			// Firefox, Opera 8.0+, Safari
			httpxml=new XMLHttpRequest();
		}catch (e){
		  // Internet Explorer
		  try{
			  httpxml=new ActiveXObject("Msxml2.XMLHTTP");
		  }catch (e){
		    try{
			   httpxml=new ActiveXObject("Microsoft.XMLHTTP");
		    }catch (e){
			   alert("Your browser does not support AJAX!");
			   return false;
		    }
		}
		
}

function stateck()
{
		if(httpxml.readyState==4)
		{
			document.getElementById("msgcd").innerHTML=httpxml.responseText;
		}
}
	
	var url="aja_chk_itmgrp.php";
	
	url=url+"?grpcd="+grpcd;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",encodeURI(url),true);
	httpxml.send(null);
}	

function isNumberKey(evt)
{
	   	var charCode = (evt.which) ? evt.which : event.keyCode
	   	 if (charCode != 46 && charCode > 31 
	   	&& (charCode < 48 || charCode > 57))
		   return false;
	    return true;
}

function upperCase(x)
{
	var y=document.getElementById(x).value;
	document.getElementById(x).value=y.toUpperCase();
}
</script>
</head>
<body onload="document.InpColMas.itmgrpcd.focus()">
   <?php include("../topbarm.php"); ?> 
   <!--<?php include("../sidebarm.php"); ?> -->

   <div class="contentc">
	<fieldset name="Group1" style=" width: 738px;" class="style2">
	 <legend class="title">ITEM GROUP MASTER</legend>
	  <br>
	  <fieldset name="Group1" style="width: 707px; height: 182px">
	  <form name="InpColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 696px;">
		<table style="height: 156px">
	  	  <tr>
	  	    <td style="height: 26px">
	  	    </td>
	  	    <td style="width: 138px; height: 26px;" class="tdlabel">Item Group Code</td>
	  	    <td style="height: 26px">:</td>
	  	    <td style="height: 26px">
			<input class="inputtxt" name="itmgrpcd" id ="itmgrpcdid" type="text" maxlength="10" onchange ="upperCase(this.id)" onBlur="AjaxFunction(this.value);"/>
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td style="height: 6px"></td> 
	  	    <td style="width: 138px; height: 6px;" class="tdlabel"></td>
	  	    <td style="height: 6px"></td> 
            <td style="height: 6px"><div id="msgcd"></div></td> 
	   	  </tr> 
	   	   <tr>
	   	    <td style="height: 20px">
	  	    </td>
	  	    <td style="width: 138px; height: 20px;" class="tdlabel">Description</td>
	  	    <td style="height: 20px">:</td>
	  	    <td style="height: 20px">
			<input class="inputtxt" name="itmgrpde" id ="itmgrpdeid" type="text" maxlength="80" onchange ="upperCase(this.id)" style="width: 354px"/>
			</td>
	  	  </tr>
	  	  	  	  <tr>
	  	    <td style="height: 4px"></td> 
	  	    <td style="width: 138px; height: 4px;" class="tdlabel"></td>
	  	    <td style="height: 4px"></td> 
            <td style="height: 4px"><div id="msgcd"></div></td> 
	   	  </tr> 
	   	  <tr>
	   	    <td style="height: 7px">
	  	    </td>
	  	    <td style="width: 138px; height: 7px;"></td>
	  	    <td style="height: 7px"></td>
	  	    <td style="height: 7px">
			<?php
	  	   include("../Setting/btnsave.php");
	  	   ?>
			</td>
	  	    <td style="height: 7px"></td> 
            <td style="height: 7px"><div id="msgcd"></div></td> 
	   	  </tr> 
	   	   <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">	  	    
			&nbsp;</td>
	  	    <td>&nbsp;</td>
	  	    <td>
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
				$sql = "SELECT itm_grp_cd, itm_grp_desc, modified_by, modified_on ";
				$sql .= " FROM item_group_master";
    		    $sql .= " ORDER BY itm_grp_cd";  
			    $rs_result = mysql_query($sql); 
        ?>
		<br/><br/>
        <form name="LstColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
		 <table>
		 <tr>
		   <td style="width: 750px; height: 38px;" align="right">
		      <?php
    	  	   include("../Setting/btnprint.php");
		       $msgdel = "Are You Sure Delete Item Group Code?";
    	  	   include("../Setting/btndelete.php"); 
		      ?>
		   </td>
		 </tr>
		 </table>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
      		<tr>
         	 <th></th>
         	 <th style="width: 113px">Item Group Code</th>
         	 <th>Description</th>
         	 <th></th>
         	 <th></th>
         	</tr>

         	<tr>
         	 <th class="tabheader" style="width: 27px">#</th>
         	 <th class="tabheader" style="width: 113px">Item Group Code</th>
         	 <th class="tabheader" style="width: 303px">&nbsp;Description</th>
         	 <th class="tabheader" style="width: 50px">Update</th>
         	 <th class="tabheader">Delete</th>
         	</tr>
         	</thead>
		 <tbody>
		 <?php 
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) { 
			
			$showdte = date('d-m-Y', strtotime($row['modified_on']));
			$urlpop = 'upd_itmgrp_mas.php';
			echo '<tr>';
            echo '<td>'.$numi.'</td>';
            echo '<td>'.$row['itm_grp_cd'].'</td>';
            echo '<td>'.$row['itm_grp_desc'].'</td>';
			
			if ($var_accupd == 0){
            echo '<td><a href="#">[EDIT]</a>';'</td>';
            }else{
            echo '<td><a href="'.$urlpop.'?grpcd='.$row['itm_grp_cd'].'&grpde='.$row['itm_grp_desc'].'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
            }
			
            if ($var_accdel == 0){
              echo '<td><input type="checkbox" DISABLED  name="itmgrpcd[]" value="'.$rowq['itm_grp_cd'].'" />'.'</td>';
            }else{
              echo '<td><input type="checkbox" name="itmgrpcd[]" value="'.$row['itm_grp_cd'].'" />'.'</td>';
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
