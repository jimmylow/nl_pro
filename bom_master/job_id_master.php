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
     $jobfdid = stripslashes(mysql_real_escape_string($_POST['jobfid']));
     $jobfdesc = stripslashes(mysql_real_escape_string($_POST['jobfiddesc']));
     $jobfrate = $_POST['jobfrate'];
         
     if ($jobfdid <> "" && $jobfrate <> "") {
     	if (is_numeric($jobfrate)){
     	
     	  	$var_sql = " SELECT count(*) as cnt from jobfile_master";
   		  	$var_sql .= " WHERE jobfile_id = '$jobfdid'";
   		 	$query_id = mysql_query($var_sql) or die ("Cant Check System Number");
   		  	$res_id = mysql_fetch_object($query_id);
	             
          	if ($res_id->cnt > 0){
	       		$backloc = "../bom_master/job_id_master.php?stat=3&menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
           		echo "</script>";        
	      	}else{
	       		$jobfdesc = str_replace("'", '^', $jobfdesc);
            	$vartoday = date("Y-m-d H:i:s");
	      		$sql = "INSERT INTO jobfile_master values 
	      		      ('$jobfdid', '$jobfdesc', '$jobfrate','$var_loginid', '$vartoday',
	      			     '$var_loginid', '$vartoday', 'A')";
	      		echo $sql;	     
	      		mysql_query($sql) or die(mysql_error()); 
	      
	        	$backloc = "../bom_master/job_id_master.php?stat=1&menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
           		echo "</script>";	        }	
       	}else{
       		$backloc = "../bom_master/job_id_master.php?stat=5&menucd=".$var_menucode;
           	echo "<script>";
           	echo 'location.replace("'.$backloc.'")';
           	echo "</script>";       	}	
     }else{
       $backloc = "../bom_master/job_id_master.php?stat=5&menucd=".$var_menucode;
       echo "<script>";
       echo 'location.replace("'.$backloc.'")';
       echo "</script>";
     }
    }
       
   if ($_POST['Submit'] == "Delete") {
     if(!empty($_POST['jobfid']) && is_array($_POST['jobfid'])) 
     {
           foreach($_POST['jobfid'] as $value ) {
		    $sql = "delete from jobfile_master where jobfile_id ='".$value."'";
        
		 	mysql_query($sql); 
		   }
		   $backloc = "../bom_master/job_id_master.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";       }      
    }
    
 
    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
    
        $fname = "job_id_master.rptdesign&__title=myReport"; 
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
		"bAutoWidth":false,
		"aoColumns": [
    					null,
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

function CheckDecimal(chkval)  
{  
   if (chkval != ""){
    if (((chkval / chkval) != 1) && (chkval != 0)) {
		alert('Job Rate Only Can Accept Decimal')
		document.InpJobFMas.jobfrate.focus()
	}else{  
    	document.InpJobFMas.jobfrate.value = (parseFloat(chkval)).toFixed(4); 
	}
   }	
}

function chk_jobID(jobidstr)
{
  var strURL="aja_chk_jobid.php?jobidf="+jobidstr;
  var req = getXMLHTTP();

  if (req)
  {
     req.onreadystatechange = function()
	{
		if (req.readyState == 4)
	    {
	     	if (req.status == 200)
			{
		    	
				 document.getElementById("msgcd").innerHTML= req.responseText;				 
			} 
		}
	}	 
   }
   req.open("GET", strURL, true);
   req.send(null);
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


function validateForm()
{
    var x=document.forms["InpJobFMas"]["jobfid"].value;
	if(!x.match(/\S/)){	
		alert("Job ID Cannot Not Be Blank");
		document.InpJobFMas.jobfid.focus();
		return false;
	}
	
	var x=document.forms["InpJobFMas"]["jobfrate"].value;
	if(!x.match(/\S/)){	
		alert("Job ID Rate Cannot Not Be Blank");
		document.InpJobFMas.jobfrate.focus();
		return false;
	}

}
</script>
</head>

  <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="document.InpJobFMas.jobfid.focus()">
  <?php include("../topbarm.php"); ?> 
  <div class="contentc">

	<fieldset name="Group1" style=" width: 831px;" class="style2">
	 <legend class="title">JOB FILE </legend>
	  <br>
	  <fieldset name="Group1" style="width: 810px; height: 170px">
	  <form name="InpJobFMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 200px; width: 808px;" onsubmit="return validateForm()">
		<table style="width: 797px; height: 118px">
		    <tr>
	   	    <td style="width: 5px"></td>
	  	    <td style="width: 135px">Job ID</td>
	  	    <td style="width: 12px">:</td>
	  	    <td>
			<input class="inputtxt" name="jobfid" id ="jobfidid" type="text" maxlength="5" style="width: 71px;" onchange ="upperCase(this.id)" onBlur="chk_jobID(this.value);"></td>
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	   <td></td>
	  	   <td></td>
	  	   <td><div id="msgcd"></div></td>
	  	  </tr>

	   	   <tr>
	   	    <td style="width: 5px"></td>
	  	    <td style="width: 135px">Job Description </td>
	  	    <td style="width: 12px">:</td>
	  	    <td>
			<input class="inputtxt" name="jobfiddesc" id ="jobfdescid" type="text" maxlength="80" style="width: 458px"></td>
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 135px;">Rate</td>
	  	   <td>:</td>
	  	   <td>
		   <input class="inputtxt" name="jobfrate" id ="jobfrateid" type="text" maxlength="12" style="width: 94px" onBlur="CheckDecimal(this.value);">
		   </td>
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td style="width: 135px"></td>
	  	   <td></td>
	  	   <td><div></div></td>
	  	  </tr>
	  	  <tr>
	  	   <td style="width: 5px"></td>
	  	   <td style="width: 135px"></td>
	  	   <td style="width: 12px"></td>
	  	   <td>
	  	   <?php
	  	   include("../Setting/btnsave.php");
	  	   ?>
	  	   </td>
	  	  </tr>
	  	   <tr>
	  	    <td></td>
	  	    <td></td>
	  	    <td style="width: 5px"></td>
	  	    <td style="height: 17px;" colspan="7"><span style="color:#FF0000">Message :</span>
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
				    echo("<span>Duplicated Job File ID Found!! Not Success.</span>");
  					break;
  				case 4:
				    echo("<span>Please Fill In The Data To Save</span>");
  					break;
  				case 5:
				    echo("<span>Rate Must Be In Decimal Point</span>");
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
	    <br>
        <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table>
		 <tr>
		   
           <td style="width: 842px; height: 38px;" align="right">
              <?php
    	  	   include("../Setting/btnprint.php");
		       $msgdel = "Are You Sure Delete Selected Job File Code?";
    	  	   include("../Setting/btndelete.php");
   		      ?>
           </td>
		 </tr>
		 </table>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" style="width: 100%">
         <thead>
         	<tr>
         	 <th style="width: 30px"></th>
         	 <th style="width: 61px">Job ID </th>
         	 <th style="width: 331px">Job Description</th>
         	 <th style="width: 50px">Rate</th>
         	 <th style="width: 50px">Status</th>
         	 <th style="width: 30px"></th>
         	 <th></th>
         	</tr>

         	<tr>
         	 <th class="tabheader" style="width: 30px">#</th>
         	 <th class="tabheader" style="width: 61px">Job ID </th>
         	 <th class="tabheader" style="width: 350px">Job Description</th>
         	 <th class="tabheader" style="width: 50px">Rate</th>
         	 <th class="tabheader" style="width: 50px">Status</th>
         	 <th class="tabheader" style="width: 30px">Update</th>
         	 <th class="tabheader" style="width: 30px">Delete</th>
         	</tr>
           </thead>
		 <tbody>
		
		 <?php 
		    $sql = "SELECT jobfile_id, jobfile_desc, jobfile_rate, actvty,";
		    $sql .= " modified_by, modified_on ";
		    $sql .= " FROM jobfile_master";
    		$sql .= " ORDER BY jobfile_id";  
			$rs_result = mysql_query($sql); 
		 
		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 
				$jode = $rowq['jobfile_desc'];
				$jode = str_replace("^", "'", $jode);
			 if($defaultcolor == "#fafaf7") 
                { $defaultcolor = "#F1f1EE"; }
              else
                { $defaultcolor = "#fafaf7"; }
                
			$urlpop = 'upd_jobfileid_mas.php';
			echo '<tr bgcolor='.$defaultcolor.'>';
            echo '<td>'.$numi.'</td>';
            echo '<td>'.$rowq['jobfile_id'].'</td>';
            echo '<td>'.$jode.'</td>';
            echo '<td>'.$rowq['jobfile_rate'].'</td>';
            echo '<td>'.$rowq['actvty'].'</td>';
            
            if ($var_accupd == 0){
            echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
            }else{
            echo '<td align="center"><a href="'.$urlpop.'?jobfid='.$rowq['jobfile_id'].'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
            }
           

            if ($var_accdel == 0){
              echo '<td align="center"><input type="checkbox" DISABLED  name="jobfid[]" value="'.$rowq['jobfile_id'].'" />'.'</td>';
            }else{
              echo '<td align="center"><input type="checkbox" name="jobfid[]" value="'.$rowq['jobfile_id'].'" />'.'</td>';
            }
            echo '</tr>';
           
            $numi = $numi + 1;
			}
		 ?>
		 </tbody>
		  <!--<tfoot>
		 	<tr>
         	 <th></th>
         	 <th>Job ID </th>
         	 <th>Job Description</th>
         	 <th>Rate</th>
         	 <th></th>
         	 <th>Modified By</th>
         	 <th>Modified On</th>
         	 <th></th>
         	 <th></th>
         	</tr>
		 </tfoot>-->
		 </table>
         </form>
	</fieldset>
    </div>
     <div class="spacer"></div>
</body>

</html>
