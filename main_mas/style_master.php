<?php

	include("../Setting/Configifx.php");
	$db_link  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
   	mysql_query("SET character_set_results=utf8", $db_link) or die(mysql_error());
	mb_language('uni');
	mb_internal_encoding('UTF-8');
	mysql_select_db("$var_db_name", $db_link);
	mysql_query("set names 'utf8'",$db_link);
	$var_loginid = $_SESSION['sid'];
	$var_loginid = 'admin';
    
    if($var_loginid == "") { 
      echo "<script>";   
      echo "alert('Not Log In to the system');"; 
      echo "</script>"; 

      echo "<script>";
      echo 'top.location.href = "../index.htm"';
      echo "</script>";
    } else {
      $var_stat = $_GET['stat'];
    }
    
   
    if ($_POST['Submit'] == "Save") {
     $typecd   = $_POST['typecd'];
     $typedesc = $_POST['typede'];
     if ($typecd <> "") {

      $var_sql = " SELECT count(*) as cnt from type_master ";
      $var_sql .= " WHERE type_code = '$typecd'";

      $query_id = mysql_query($var_sql) or die ("Cant Check Type Code");
      $res_id = mysql_fetch_object($query_id);

      if ($res_id->cnt > 0 ) {
	     $var_stat = 3;
      }else {
       mysql_query("SET character_set_client=utf8", $db_link);
	   mysql_query("SET character_set_connection=utf8", $db_link);

         $sql = "INSERT INTO type_master values 
                ('$typecd', '$typedesc', '$var_loginid', CURDATE(), '$var_loginid', CURDATE())";

     	 mysql_query($sql); 
     	 $var_stat = 1;
      } 
     }else{
       $var_stat = 4;
     }
    }
       
    if ($_POST['Submit'] == "Delete") {
     if(!empty($_POST['typecd']) && is_array($_POST['typecd'])) 
     {
        
           foreach($_POST['typecd'] as $value ) {
		    $sql = "DELETE FROM type_master WHERE type_code ='".$value."'"; 
		 	mysql_query($sql); 
		   }
		   $var_stat = 1;
       }      
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
    
   		$here = getcwd();
       // Redirect browser
        $fname = "clr_rpt.rptdesign&__title=myReport"; 
        $dest = "http://".$var_server.":8080/birt-viewer/frameset?__report=clr_rpt.rptdesign";
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
<title>COLOR CODE MASTER</title>
<style media="all" type="text/css">@import "../css/styles.css";
.style2 {
	margin-right: 0px;
}
.style3 {
	margin-bottom: 33px;
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


</script>
</head>

<body>
	<fieldset name="Group1" style=" width: 718px;" class="style2">
	 <legend class="title">STYLE MASTER</legend>
	  <br>
	  <fieldset name="Group1" style="width: 707px; height: 317px" class="style3">
	  <form name="InpStyleMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="height: 134px; width: 696px;">
		<table>
	  	  <tr>
	  	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Style Code</td>
	  	    <td>:</td>
	  	    <td style="width: 505px">
			<input class="inputtxt" name="stycd" id ="stycdid" type="text" maxlength="10" onchange ="upperCase(this.id)" onBlur="AjaxFunction(this.value);">
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td style="width: 138px" class="tdlabel"></td>
	  	    <td></td> 
            <td style="width: 505px"><div id="msgcd"></div></td> 
	   	  </tr> 
	   	   <tr>
	   	    <td>
	  	    </td>
	  	    <td style="width: 138px" class="tdlabel">Style Description</td>
	  	    <td>:</td>
	  	    <td style="width: 505px">
			<input class="inputtxt" name="styde" id ="stydeid" type="text" maxlength="60" style="width: 354px">
			</td>
	  	  </tr>  
	  	   <tr>
	   	    <td>
	  	    </td>
	  	    <td></td>
	  	    <td></td>
	  	    <td style="width: 505px"></td>
	  	  </tr>  
 			<tr>
	   	    <td style="height: 17px">
	  	    </td>
	  	    <td style="height: 17px">Type</td>
	  	    <td style="height: 17px">:</td>
	  	    <td style="width: 505px; height: 17px;">
	  	    <select name="seltype" style="width: 122px">
			 <?php
                   $sql = "select type_code, type_desc from type_master ORDER BY type_desc ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['type_code'].'">'.$row['type_desc'].'</option>';
				 	 } 
				   } 
	         ?>				   
	       </select>

	  	    
	  	    </td>
	  	  </tr>  
 <tr>
	   	    <td>
	  	    </td>
	  	    <td></td>
	  	    <td></td>
	  	    <td style="width: 505px"></td>
	  	  </tr>  
 <tr>
	   	    <td>
	  	    </td>
	  	    <td>Style Image</td>
	  	    <td>:</td>
	  	    <td style="width: 505px"><img height="148" src="" width="170"><input name="File1" style="width: 285px" type="file">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </td>
	  	  </tr>  
 <tr>
	   	    <td>
	  	    </td>
	  	    <td></td>
	  	    <td></td>
	  	    <td style="width: 505px"></td>
	  	  </tr>  

	  	  <tr>
	  	   <td>
	  	   </td>
	  	   <td>
	  	   </td>
	  	   <td>
	  	   </td>
	  	   <td style="width: 505px">
	  	   <input type=submit name = "Submit" value="Save" class="butsub" style="width: 60px; height: 32px" >
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
	  
        <form name="LstTypeMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		 <table>
		 <tr>
		   <td style="width: 897px; height: 38px;">
		   </td>
           <td style="width: 635px; height: 38px;"></td>
           <td style="width: 695px; height: 38px;"></td>
           <td style="width: 236px; height: 38px;"></td>
          
           <td style="width: 750px; height: 38px;">
              <input type=submit name = "Submit" value="Print" class="butsub" style="width: 60px; height: 32px">
		      <input type=submit name = "Submit" value="Delete" class="butsub" style="width: 60px; height: 32px" onclick="return confirm('Are You Sure Delete Selected Colour Code?')">
		   </td>
		 </tr>
		 </table>
		 <table cellpadding="0" cellspacing="0" border id="example">
         <thead><tr>
          <th class="tabheader" style="width: 27px">#</th>
          <th class="tabheader" style="width: 138px">Type Code</th>
          <th class="tabheader" style="width: 303px">&nbsp;Description</th>
          <th class="tabheader" style="width: 81px">Modified By</th>
          <th class="tabheader" style="width: 80px">Modified On</th>
          <th class="tabheader" style="width: 50px">Update</th>
          <th class="tabheader">Delete</th>
         </tr></thead>
		 <tbody>
		 <?php 
				$sql = "SELECT type_code, type_desc, modified_by, modified_on ";
				$sql .= " FROM type_master";
    		    $sql .= " ORDER BY type_code";  
			    $rs_result = mysql_query($sql); 
		 
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) { 
			
			$showdte = date('Y-m-d', strtotime($row['modified_on']));
			$urlpop = 'upd_type_mas.php';
			echo '<tr>';
            echo '<td>'.$numi.'</td>';
            echo '<td>'.$row['type_code'].'</td>';
            echo '<td>'.$row['type_desc'].'</td>';
            echo '<td>'.$row['modified_by'].'</td>';
            echo '<td>'.$showdte.'</td>';
            echo '<td><a target="frame1" href="'.$urlpop.'?typecd='.$row['type_code'].'&typede='.$row['type_desc'].'">[EDIT]</a>';'</td>';
            echo '<td><input type="checkbox" name="typecd[]" value="'.$row['type_code'].'" />'.'</td>';
            echo '</tr>';
            $numi = $numi + 1;
			}
		 ?>
		 </tbody>
		 </table>
		 
		 <div class="spacer"></div>
         </form>
	 
	</fieldset>

</body>

</html>
