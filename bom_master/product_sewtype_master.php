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
     $typecd   = htmlentities(mysql_real_escape_string($_POST['typecd']));
     $sewtypecd   = htmlentities(mysql_real_escape_string($_POST['sewtypecd']));
     $typedesc = htmlentities(mysql_real_escape_string($_POST['typede']));
     if ($typecd <> "") {

      $var_sql = " SELECT count(*) as cnt from prosewtype_master ";
      $var_sql .= " WHERE sewtype_code = '$typecd'";

      $query_id = mysql_query($var_sql) or die ("Cant Check Type Code");
      $res_id = mysql_fetch_object($query_id);

      if ($res_id->cnt > 0 ) {
	     $backloc = "../bom_master/product_sewtype_master.php?stat=3&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";  
      }else {
         $sql = "INSERT INTO prosewtype_master values 
          ('$typecd','$typedesc', '$var_loginid', CURDATE(), '$var_loginid', CURDATE(), 'A')";

     	 mysql_query($sql) or die("Unable to Insert Type ".mysql_error()); 
     	 $backloc = "../bom_master/product_sewtype_master.php?stat=1&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
      } 
     }else{
         $backloc = "../bom_master/product_sewtype_master.php?stat=4&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";     }
    }
       
    if ($_POST['Submit'] == "Delete") {
     if(!empty($_POST['typecd']) && is_array($_POST['typecd'])) 
     {
        
           foreach($_POST['typecd'] as $value ) {
		    $sql = "DELETE FROM prosewtype_master WHERE sewtype_code ='".mysql_real_escape_string($value)."'"; 
		 	mysql_query($sql); 
		   }
		   $backloc = "../bom_master/product_sewtype_master.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";
       }      
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
       
        $fname = "prosewtype_rpt.rptdesign&__title=myReport"; 
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
    					{ "bSortable": false },
    					{ "bSortable": false }
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

function AjaxFunction(typecd)
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
	
	var url="aja_chk_type.php";
	
	url=url+"?typecd="+typecd;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",encodeURI(url),true);
	httpxml.send(null);
}	

function AjaxFunction2(typecd)
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

function stateck2()
{
		if(httpxml.readyState==4)
		{
			document.getElementById("msgcd2").innerHTML=httpxml.responseText;
		}
}
	
	var url="aja_chk_type2.php";
	
	url=url+"?typecd="+typecd;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck2;
	
	httpxml.open("GET",encodeURI(url),true);
	httpxml.send(null);
}	

</script>
</head>
  
  <!--<?php include("../sidebarm.php"); ?> -->

<body onload="document.InpTypeMas.typecdid.focus()">
<?php include("../topbarm.php"); ?> 
  <div class="contentc">

	<fieldset name="Group1" style=" width: 714px;" class="style2">
	 <legend class="title">PRODUCT SEWING TYPE MASTER</legend>
	  <br>
	  <fieldset name="Group1" style="width: 686px; height: 204px">
	  <form name="InpTypeMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 696px;">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Product Sew Type</td>
	  	    <td>:</td>
	  	    <td style="width: 505px">
			<input class="inputtxt" name="typecd" id ="typecdid" type="text" maxlength="10" onchange ="upperCase(this.id)" onBlur="AjaxFunction2(this.value);" style="width: 61px">
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td style="width: 138px" class="tdlabel"></td>
	  	    <td></td> 
            <td style="width: 505px"><div id="msgcd"></div></td> 
	   	  </tr> 
			<tr>
	  	    <td></td> 
	  	    <td style="width: 138px" class="tdlabel"></td>
	  	    <td></td> 
            <td style="width: 505px"><div id="msgcd2"></div></td> 
	   	    </tr>
	   	   <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Description</td>
	  	    <td>:</td>
	  	    <td style="width: 505px">
			<input class="inputtxt" name="typede" id ="typedeid" type="text" maxlength="60" style="width: 354px">
			</td>
	  	  </tr>  
	  	  <tr>
	  	   <td>
	  	   </td>
	  	   <td>
	  	   </td>
	  	   <td>
	  	   </td>
	  	   <td style="width: 505px">
	  	    <?php
	  	   include("../Setting/btnsave.php");
	  	   ?>

	  	   </td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
           <td></td>
           <td></td>
           <td style="width: 505px"><span style="color:#FF0000">Message :</span>
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
        <form name="LstTypeMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
		 <table>
		 <tr>
		   <td style="width: 704px; height: 38px;" align="right">
             <?php
    	  	   include("../Setting/btnprint.php");
		       $msgdel = "Are You Sure Delete Selected Type Code??";
    	  	   include("../Setting/btndelete.php"); 
		      ?>
		   </td>
		 </tr>
		 </table>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" style="width: 99%">
         <thead>
         	<tr>
         	 <th></th>
         	 <th>Product Sew Type</th>
         	 <th style="width: 303px">Description</th>
         	 <th style="width: 40px"></th>
         	 <th style="width: 50px"></th>
         	 <th style="width: 50px"></th>
         	</tr>

         	<tr>
         	 <th class="tabheader" style="width: 27px">#</th>
         	 <th class="tabheader" style="width: 138px">Product Sew Type</th>
         	 <th class="tabheader" style="width: 303px">Description</th>
         	 <th class="tabheader" style="width: 40px">Status</th>
         	 <th class="tabheader" style="width: 50px">Update</th>
         	 <th class="tabheader" style="width: 50px">Delete</th>
         	</tr>
         </thead>
		 <tbody>
		 <?php 
				$sql = "SELECT sewtype_code,  sewtype_desc, stat ";
				$sql .= " FROM prosewtype_master";
    		    $sql .= " ORDER BY stat, sewtype_code";  
			    $rs_result = mysql_query($sql); 
		 
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) {
			
			if ($row['stat'] == "A"){
				$statdesc = 'ACTIVE';
			}else{
				$statdesc = 'DELETE';
			}	 
			
			$urlpop = 'upd_sewtype_mas.php';
			echo '<tr>';
            echo '<td>'.$numi.'</td>';
            echo '<td>'.$row['sewtype_code'].'</td>';
            echo '<td>'.$row['sewtype_desc'].'</td>';
            echo '<td title="'.$statdesc.'">'.$row['stat'].'</td>';
            
            if ($var_accupd == 0){
            echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
            }else{
            echo '<td align="center"><a href="'.$urlpop.'?typecd='.htmlentities($row['sewtype_code']).'&typede='.htmlentities($row['sewtype_desc']).'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
            }
            if ($var_accdel == 0){
              echo '<td align="center"><input type="checkbox" DISABLED  name="typecd[]" value="'.$rowq['sewtype_code'].'" />'.'</td>';
            }else{
              echo '<td align="center"><input type="checkbox" name="typecd[]" value="'.htmlentities($row['sewtype_code']).'" />'.'</td>';
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
