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
     $areacd   = $_POST['areacd'];
     $areadesc = $_POST['areade'];
     if ($areacd <> "") {

      $var_sql = " SELECT count(*) as cnt from area_master ";
      $var_sql .= " WHERE area_code = '$areacd'";

      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);

      if ($res_id->cnt > 0 ) {
	     $backloc = "../main_mas/area_mas.php?stat=3&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
      }else {
         $vartoday = date("Y-m-d H:i:s");
         $sql = "INSERT INTO area_master values 
                ('$areacd', '$areadesc', '$var_loginid', '$vartoday', '$var_loginid', '$vartoday')";
     	 mysql_query($sql); 
     	 
     	 $backloc = "../main_mas/area_mas.php?stat=1&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
       } 
     }else{
        $backloc = "../main_mas/area_mas.php?stat=4&menucd=".$var_menucode;
        echo "<script>";
        echo 'location.replace("'.$backloc.'")';
        echo "</script>";     }
    }
       
    if ($_POST['Submit'] == "Delete") {
     if(!empty($_POST['areacd']) && is_array($_POST['areacd'])) 
     {
        
           foreach($_POST['areacd'] as $value ) {
		    $sql = "DELETE FROM area_master WHERE area_code ='".$value."'"; 
		 	mysql_query($sql); 
		   }
		   $backloc = "../main_mas/area_mas.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";
       }      
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
            
        $fname = "area_rpt.rptdesign&__title=myReport"; 
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

function AjaxFunction(areacd)
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
	
	var url="aja_chk_area.php";
	
	url=url+"?areacd="+areacd;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",encodeURI(url),true);
	httpxml.send(null);
}	
</script>
</head>
 
  <!--<?php include("../sidebarm.php"); ?>-->
<body onload="document.InpAreaMas.areacd.focus()">
   <?php include("../topbarm.php"); ?> 
  <div class="contentc">

	<fieldset name="Group1" style=" width: 700px;">
	 <legend class="title">AREA MASTER</legend>
	  <br>
	  <fieldset name="Group1" style="width: 700px; height: 128px">
	  <form name="InpAreaMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>" style="height: 134px; width: 690px;">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Area Code</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="areacd" id ="areaid" type="text" maxlength="5" onchange ="upperCase(this.id)" style="width: 44px" onBlur="AjaxFunction(this.value);">
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
			<input class="inputtxt" name="areade" id ="areadeid" type="text" maxlength="60" onchange ="upperCase(this.id)" style="width: 486px">
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
		  <tr>
	  	    <td></td>
	  	    <td></td>
			<td></td>
            <td style="width: 505px" colspan="7"><span style="color:#FF0000">Message :</span>
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
	    <br/><br/>
	  	<?php
				$sql = "SELECT area_code, area_desc, modified_by, modified_on ";
				$sql .= " FROM area_master";
    		    $sql .= " ORDER BY area_code";  
			    $rs_result = mysql_query($sql); 
			    //echo $sql;
        ?>
        <form name="LstAreaMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
		 <table>
		 <tr>
		   <td style="width: 920px; height: 38px;" align="right">
		       <?php
    	  	   include("../Setting/btnprint.php");
		       $msgdel = "Are You Sure Delete Selected Area Code?";
    	  	   include("../Setting/btndelete.php"); 
		      ?>
           </td>
		 </tr>
		 </table>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
         	<tr>
          		<th></th>
          		<th>Area Code</th>
          		<th>Description</th>
          		<th></th>
          		<th></th>
         	</tr>
         	<tr>
          		<th class="tabheader" style="width: 27px">#</th>
          		<th class="tabheader" style="width: 138px">Area Code</th>
          		<th class="tabheader" style="width: 303px">Description</th>
          		<th class="tabheader" style="width: 50px">Update</th>
          		<th class="tabheader">Delete</th>
         	</tr>
         </thead>
		 <tbody>
		 <?php 
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) { 
			
			$showdte = date('d-m-Y', strtotime($row['modified_on']));
			$urlpop = 'upd_area_mas.php';
			echo '<tr>';
            echo '<td>'.$numi.'</td>';
            echo '<td>'.$row['area_code'].'</td>';
            echo '<td>'.$row['area_desc'].'</td>';
			
			if ($var_accupd == 0){
            echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
            }else{
            echo '<td align="center"><a href="'.$urlpop.'?areacd='.$row['area_code'].'&areade='.$row['area_desc'].'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
            }
            if ($var_accdel == 0){
              echo '<td align="center"><input type="checkbox" DISABLED  name="areacd[]" value="'.$rowq['area_code'].'" />'.'</td>';
            }else{
              echo '<td align="center"><input type="checkbox" name="areacd[]" value="'.$row['area_code'].'" />'.'</td>';
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
