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
     $catcd     = mysql_real_escape_string($_POST['catcd']);
     $catdesc   = mysql_real_escape_string($_POST['catde']);
     $catitmgrp = mysql_real_escape_string($_POST['selitmgrp']);
     $catmark   = $_POST['catmark'];
     $catcut    = $_POST['catcut'];
     $catspread = $_POST['catspread'];
     $catbundle = $_POST['catbundle'];

     if ($catcd <> "") {

      $var_sql = " SELECT count(*) as cnt from cat_master ";
      $var_sql .= " WHERE cat_code = '$catcd'";

      $query_id = mysql_query($var_sql) or die ("Cant Check Category");
      $res_id = mysql_fetch_object($query_id);

      if ($res_id->cnt > 0 ) {
	     $backloc = "../stck_mas/catmas.php?stat=3&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";   
      }else {
		
		 if ($catmark == ""){$catmark = 0;}	
		 if ($catcut == ""){$catcut = 0;}	
		 if ($catspread == ""){$catspread = 0;}	
		 if ($catbundle == ""){$catbundle = 0;}	
         $sql = "INSERT INTO cat_master values 
                ('$catcd', '$catdesc', '$catmark', '$catcut', '$catspread',  '$catbundle', '$var_loginid', CURDATE(), '$var_loginid', CURDATE(), '$catitmgrp')";
     	 mysql_query($sql) or mysql_error(); 
     	 $backloc = "../stck_mas/catmas.php?stat=1&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";         
	  } 
     }else{
         $backloc = "../stck_mas/catmas.php?stat=4&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";     
     }
    }
       
    if ($_POST['Submit'] == "Delete") {
     if(!empty($_POST['catcd']) && is_array($_POST['catcd'])) 
     {   
           foreach($_POST['catcd'] as $value ) {
		    	$sql = "DELETE FROM cat_master WHERE cat_code ='".$value."'"; 
		 		mysql_query($sql); 
		   }
		   $backloc = "../stck_mas/catmas.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";          
      }      
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
       	
       // Redirect browser 
        $fname = "cat_rpt.rptdesign&__title=myReport"; 
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
				     { type: "text" },
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

   
function AjaxFunction(catcd)
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
	
	var url="aja_chk_cat.php";
	
	url=url+"?catcd="+catcd;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",encodeURI(url),true);
	httpxml.send(null);
}

function chkValue(vid)
{
    var col2 = document.getElementById(vid).value;
	
   	if (col2 !== ""){

		if(isNaN(col2)) {	
    	   alert('Please Enter a valid number:' + col2);
    	   document.getElementById(vid).focus();
    	   col2 = 0;
    	}
    	document.getElementById(vid).value = parseFloat(col2).toFixed(2);
    }
}

function validateForm()
{
    var x=document.forms["InpColMas"]["catcdid"].value;
	if (x==null || x=="")
	{
	alert("Category Code Cannot Be Blank");
	document.InpColMas.catcdid.focus();
	return false;
	}
}		
</script>
</head>
<body onload="document.InpColMas.catcdid.focus()">
  <?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?> -->

  <div class="contentc" style="width: 813px">
	<fieldset name="Group1" style="width: 750px;" class="style2">
	 <legend class="title">CATEGORY MASTER</legend>
	  <br>
	  <fieldset name="Group1" style="width: 718px; height: 290px">
	  <form name="InpColMas" method="POST" onsubmit="return validateForm()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 696px;">
		<table>
	  	  <tr>
	  	    	<td></td>
	  	    	<td style="width: 196px" class="tdlabel">Category Code</td>
	  	    	<td>:</td>
	  	    	<td>
				<input class="inputtxt" name="catcd" id ="catcdid" type="text" maxlength="20" onchange ="upperCase(this.id)" onBlur="AjaxFunction(this.value);">
				</td>
	  	  </tr>
	  	  <tr>
	  	    	<td></td> 
	  	    	<td style="width: 196px" class="tdlabel"></td>
	  	     	<td></td> 
	  	     	<td><div id="msgcd"></div></td> 
	   	  </tr> 
	   	  <tr>
	   	    	<td></td>
	  	    	<td style="width: 196px" class="tdlabel">Category Description</td>
	  	    	<td>:</td>
	  	    	<td>
				<input class="inputtxt" name="catde" id ="catdeid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 354px">
				</td>
	  	  </tr>
	  	  <tr><td></td></tr>
	  	   <tr>
	   	  		<td></td>
	   	  		<td>Item Group</td>
	   	  		<td>:</td>
	   	  		<td>
	   	  			<select name="selitmgrp" style="width: 165px">
					<option></option>
			    	<?php
                   		$sql = "select itm_grp_cd, itm_grp_desc from item_group_master ORDER BY itm_grp_cd ASC";
                   		$sql_result = mysql_query($sql);
                                         
				   		if(mysql_num_rows($sql_result)) 
				   		{
				   			 while($row = mysql_fetch_assoc($sql_result)) 
				   			  { 
							  echo '<option value="'.$row['itm_grp_cd'].'">'.$row['itm_grp_cd'].' | '.$row['itm_grp_desc'].'</option>';
				 			 } 
				   		} 
	            	?>				   
			  		</select>
	   	  		</td>
	   	  </tr>
	   	  <tr><td></td></tr>  
	  	  <tr>
	   	    	<td></td>
	  	    	<td style="width: 196px" class="tdlabel">Mark</td>
	  	    	<td>:</td>
	  	    	<td>
				<input class="inputtxt" name="catmark" id ="catmarkid" type="text" maxlength="10" onBlur="chkValue(this.id);">
				</td>
	  	  </tr> 
	  	  <tr>
	   	    	<td></td>
	  	    	<td style="width: 196px" class="tdlabel">Cut</td>
	  	    	<td>:</td>
	  	    	<td>
				<input class="inputtxt" name="catcut" id ="catcutid" type="text" maxlength="10" onBlur="chkValue(this.id);"></td>
	  	  </tr> 
	  	  <tr>
	   	    	<td></td>
	  	    	<td style="width: 196px" class="tdlabel">Spread</td>
	  	    	<td>:</td>
	  	    	<td>
				<input class="inputtxt" name="catspread" id ="catspreadid" type="text" maxlength="10" onBlur="chkValue(this.id);"></td>
	  	  </tr> 
	  	  <tr>
	   	    	<td></td>
	  	    	<td style="width: 196px" class="tdlabel">Bundle</td>
	  	    	<td>:</td>
	  	    	<td>
				<input class="inputtxt" name="catbundle" id ="catbundleid" type="text" maxlength="10" onBlur="chkValue(this.id);"></td>
	  	  </tr>
	  	  <tr> 
	  	   		<td></td>
	  	   		<td style="width: 196px"></td>
	  	   		<td></td>
	  	   		<td>
	  	    		<?php
	  	   				include("../Setting/btnsave.php");
	  	   			?>
	  	   		</td>
	  	  </tr>
	  	  <tr>
	  	   		<td></td>
	  	   		<td style="width: 196px"></td>
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
        <form name="LstColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
		 <table>
		 <tr>
		   <td style="width: 919px; height: 38px;" align="right">
		      <?php
		        include("../Setting/btnprint.php");
			    $msgdel = "Are You Sure Delete Selected Category Code?";
			    include("../Setting/btndelete.php");
		      ?>
		   </td>
		 </tr>
		 </table>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
       		<tr>
         	 <th></th>
         	 <th>Category Code</th>
         	 <th>Category Description</th>
         	 <th>Mark</th>
         	 <th>Cut</th>
         	 <th>Spread</th>
         	 <th>Bundle</th>
         	 <th></th>
         	 <th></th>
         	</tr>

         	<tr>
         	 <th class="tabheader" style="width: 20px">#</th>
         	 <th class="tabheader" style="width: 100px">Category Code</th>
         	 <th class="tabheader" style="width: 250px">Category Description</th>
         	 <th class="tabheader" style="width: 100px">Mark</th>
         	 <th class="tabheader" style="width: 100px">Cut</th>
         	 <th class="tabheader" style="width: 100px">Spread</th>
         	 <th class="tabheader" style="width: 100px">Bundle</th>
         	 <th class="tabheader" style="width: 30px">Update</th>
         	 <th class="tabheader">Delete</th>
         	</tr>
         	</thead>
         <tbody>
		 <?php 
		 	$sql = "SELECT cat_code, cat_desc, mark, cut, spread, bundle, upd_by, upd_on ";
			$sql .= " FROM cat_master";
    		$sql .= " ORDER BY cat_code";  
			$rs_result = mysql_query($sql); 
			    
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) { 
			
			$showdte = date('d-m-Y', strtotime($row['upd_on']));
			$urlpop = 'upd_cat_mas.php';
			echo '<tr>';
            echo '<td>'.$numi.'</td>';
            echo '<td>'.$row['cat_code'].'</td>';
            echo '<td>'.$row['cat_desc'].'</td>';
            echo '<td>'.$row['mark'].'</td>';
            echo '<td>'.$row['cut'].'</td>';
            echo '<td>'.$row['spread'].'</td>';
            echo '<td>'.$row['bundle'].'</td>';

            
            if ($var_accupd == 0){
            	echo '<td><a href="#">[EDIT]</a>';'</td>';
            }else{
            	echo '<td><a href="'.$urlpop.'?catcd='.$row['cat_code'].'&catde='.$row['cat_desc'].'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
            }
            
            if ($var_accdel == 0){
            echo '<td align="center"><input type="checkbox" DISABLED name="catcd[]" value="'.$row['cat_code'].'" />'.'</td>';
            }else{
            echo '<td align="center"><input type="checkbox" name="catcd[]" value="'.$row['cat_code'].'" />'.'</td>';
            }
            echo '</tr>';
            $numi = $numi + 1;
			}
		 ?></tbody>
		 </table>
         </form>
	 
	</fieldset>
</div>
</body>

</html>

