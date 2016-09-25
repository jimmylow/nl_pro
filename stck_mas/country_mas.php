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
     $countcd   = $_POST['countcd'];
     $countdesc = $_POST['countde'];
     if ($countcd <> "") {

      $var_sql = " SELECT count(*) as cnt from country_master ";
      $var_sql .= " WHERE country_code = '$countcd'";

      $query_id = mysql_query($var_sql) or die($var_stat = mysql_error());
      $res_id = mysql_fetch_object($query_id);

      if ($res_id->cnt > 0 ) {
	     $backloc = "../main_mas/country_mas.php?stat=3&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";      
      }else {
          $vartoday = date("Y-m-d H:i:s");
          $sql = "INSERT INTO country_master values 
                ('$countcd', '$countdesc', '$var_loginid', '$vartoday', '$var_loginid', '$vartoday')";

     	 mysql_query($sql); 
     	 $backloc = "../main_mas/country_mas.php?stat=1&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";  
      } 
     }else{
       $backloc = "../main_mas/country_mas.php?stat=4&menucd=".$var_menucode;
       echo "<script>";
       echo 'location.replace("'.$backloc.'")';
       echo "</script>";
     }
    }
       
    if ($_POST['Submit'] == "Delete") {
     if(!empty($_POST['countcd']) && is_array($_POST['countcd'])) 
     {
        
           foreach($_POST['countcd'] as $value ) {
		    $sql = "DELETE FROM country_master WHERE country_code ='".$value."'"; 
		 	mysql_query($sql); 
		   }
		   $backloc = "../main_mas/country_mas.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";       }      
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
    
   		$here = getcwd();
       // Redirect browser
        $fname = "country_rpt.rptdesign&__title=myReport"; 
        $dest = "http://".$var_server.":8080/birt-viewer/frameset?__report=country_rpt.rptdesign";
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
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>

<script type="text/javascript" charset="utf-8"> 
$(document).ready(function() {
				$('#example').dataTable( {
					"sPaginationType": "full_numbers"
				} );
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

function AjaxFunction(countcd)
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
	
	var url="aja_chk_count.php";
	
	url=url+"?countcd="+countcd;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",encodeURI(url),true);
	httpxml.send(null);
}	

	function chkSubmit (getdata) {
 		if (document.getElementById("countid").value == "") {
      	alert ("Please fill in the Country Code to Continue");
      	return false;
     	}
     	
 		if (document.getElementById("countdeid").value == "") {
      	alert ("Please fill in the Country Description to Continue");
      	return false;
     	}

 	}	

</script>
</head>

<body>
  <?php include("../topbarm.php"); ?> 
  <?php include("../sidebarm.php"); ?> 
  <div style="position:absolute; top:120px; left:320px;">
	<fieldset name="Group1" style=" width: 725px;" class="style2">
	 <legend class="title">COUNTRY MASTER</legend>
	  <br>
	  <fieldset name="Group1" style="width: 707px; height: 128px">
	  <form name="InpCurrMas" method="POST" onSubmit= "return chkSubmit(this)" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 696px;">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Country</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="countcd" id ="countid" type="text" maxlength="10" onchange ="upperCase(this.id)" style="width: 71px" onBlur="AjaxFunction(this.value);">
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td>
	  	    </td> 
	  	    <td style="width: 138px" class="tdlabel"></td>
	  	     <td>
	  	    </td> 
	  	     <td><div id="msgcd"></div>
	  	    </td> 
	   	  </tr> 
	   	   <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Description</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="countde" id ="countdeid" type="text" maxlength="100" onchange ="upperCase(this.id)" style="width: 515px">
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
	  	<?php
				$sql = "SELECT country_code, country_desc, update_by, update_on ";
				$sql .= " FROM country_master";
    		    $sql .= " ORDER BY country_code";  
			    $rs_result = mysql_query($sql); 
        ?>
		<br/><br/>
        <form name="LstCountMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
		 <table>
		 <tr>
		    <td style="width: 750px; height: 38px;" align="right">
            <?php
    	  	   include("../Setting/btnprint.php");
		       $msgdel = "Are You Sure Delete Selected Country Code??";
    	  	   include("../Setting/btndelete.php"); 
		      ?>
		   </td>
		 </tr>
		 </table>
		 <table cellpadding="0" cellspacing="0" border id="example" class="display" width="100%">
         <thead><tr>
          <th class="tabheader" style="width: 27px">#</th>
          <th class="tabheader" style="width: 138px">Country</th>
          <th class="tabheader" style="width: 303px">Description</th>
          <th class="tabheader" style="width: 81px">Modified By</th>
          <th class="tabheader" style="width: 80px">Modified On</th>
          <th class="tabheader" style="width: 50px">Update</th>
          <th class="tabheader">Delete</th>
         </tr></thead>
		 <tbody>
		 <?php 
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) { 
			
			$showdte = date('Y-m-d', strtotime($row['update_on']));
			$urlpop = 'upd_country_mas.php';
			echo '<tr>';
            echo '<td>'.$numi.'</td>';
            echo '<td>'.$row['country_code'].'</td>';
            echo '<td>'.$row['country_desc'].'</td>';
            echo '<td>'.$row['update_by'].'</td>';
            echo '<td>'.$showdte.'</td>';
            
            if ($var_accupd == 0){
            echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
            }else{
            echo '<td align="center"><a href="'.$urlpop.'?countcd='.$row['country_code'].'&countde='.$row['country_desc'].'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
            }
            if ($var_accdel == 0){
              echo '<td align="center"><input type="checkbox" DISABLED  name="countcd[]" value="'.$rowq['country_code'].'" />'.'</td>';
            }else{
              echo '<td align="center"><input type="checkbox" name="countcd[]" value="'.$row['country_code'].'" />'.'</td>';
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
