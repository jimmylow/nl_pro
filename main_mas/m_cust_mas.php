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
        
    if ($_POST['Submit'] == "Deactive") {
     if(!empty($_POST['custcd']) && is_array($_POST['custcd'])) 
     {
           $custmoby= $var_loginid;
           $custmoon= date("Y-m-d H:i:s");
           foreach($_POST['custcd'] as $value ) {
		    $sql = "Update customer_master set active_flag ='DEACTIVE',";
            $sql .= " modified_by='$custmoby',";
            $sql .= " modified_on='$custmoon' WHERE customer_code ='".$value."'";
 
		 	mysql_query($sql); 
		   }

		   $backloc = "../main_mas/m_cust_mas.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";
      }      
    }
    
     if ($_POST['Submit'] == "Active") {
     if(!empty($_POST['custcd']) && is_array($_POST['custcd'])) 
     {
           $custmoby= $var_loginid;
           $custmoon= date("Y-m-d H:i:s");
           foreach($_POST['custcd'] as $value ) {
		    $sql = "Update customer_master set active_flag ='ACTIVE',";
            $sql .= " modified_by='$custmoby',";
            $sql .= " modified_on='$custmoon' WHERE customer_code ='".$value."'";
 
		 	mysql_query($sql); 
		   }
		   $backloc = "../main_mas/m_cust_mas.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";
       }      
    }

	if ($_POST['Submit'] == "Delete") {
     if(!empty($_POST['custcd']) && is_array($_POST['custcd'])) 
     {
           foreach($_POST['custcd'] as $value ) {
		    $sql = "Delete From customer_master WHERE customer_code ='".$value."'";
 
		 	mysql_query($sql); 
		   }
		   $backloc = "../main_mas/m_cust_mas.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";
       }      
    }


    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {

       // Redirect browser
        $fname = "cust_rpt.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&dbsel=".$varrpturldb;
        $dest .= urlencode(realpath($fname));
        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1500,left=200,top=200');</script>";
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
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>

<script type="text/javascript" charset="utf-8"> 
$(document).ready(function() {
		$('#example').dataTable( {
			"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50,"All"]],
			"bStateSave": true,
			"bFilter": true,
			"bAutoWitdh": false,
			"sPaginationType": "full_numbers"
			
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
				     { type: "text" },
				     null,
				     null,
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

function AjaxFunctioncd(suppcd)
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
	
	var url="aja_chk_supp.php";
	
	url=url+"?suppcdg="+suppcd;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",url,true);
	httpxml.send(null);
}	

function AjaxFunction(email)
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
		document.getElementById("msg").innerHTML=httpxml.responseText;
	  }
    }
	
	var url="../Setting/email-ajax.php";
	
	url=url+"?email="+email;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",url,true);
	httpxml.send(null);
}	
</script>
</head>
<?php include("../topbarm.php"); ?> 
  <!--<?php include("../sidebarm.php"); ?>-->
<body>
  
  <div class="contentc">

	<fieldset name="Group1" style=" width: 1200px;" class="style2">
	 <legend class="title">CUSTOMER MASTER</legend>
	  <br>
	   
		 <form name="LstColMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>">
         <table>
		 <tr>
		      
           <td style="width: 1209px; height: 38px;" align="left">
			   <?php
			   $locatr = "cust_mas.php?menucd=".$var_menucode;
                if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Create" class="butsub" style="width: 60px; height: 32px">';
  				}else{
   					echo '<input type="button" value="Create" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  				}
    	  	   $msgdel = "Are You Sure Active Selected Customer Code?";
    	  	   include("../Setting/btnactive.php"); 
			   $msgdel = "Are You Sure Deactive Selected Customer Code?";
    	  	   include("../Setting/btndeactive.php"); 
    	  	   $msgdel = "Are You Sure Delete Selected Customer Code?";
    	  	   include("../Setting/btndelete.php"); 
    	  	   include("../Setting/btnprint.php");   	  	       
		       ?>
            </td>
		 </tr>
		 </table>
		 <br>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" style="width: 100%">
         <thead>
         	<tr>
         	 <th></th>
         	 <th>Customer<br>Code</th>
         	 <th>Name</th>
         	 <th>GST No</th>
         	 <th>Telephone</th>
         	 <th>Fax</th>
         	 <th>Contact <br>Person1</th>
         	 <th>Active</th>
         	 <th></th>
         	 <th></th>
         	 <th></th>
         	 <th></th>
         	</tr>

         	<tr>
         	 <th class="tabheader" style="width: 4px;">#</th>
         	 <th class="tabheader" style="width: 30px">Customer<br>Code</th>
         	 <th class="tabheader" style="width: 200px">Name</th>
         	 <th class="tabheader" style="width: 80px">GST No</th>
         	 <th class="tabheader" style="width: 80px">Telephone</th>
         	 <th class="tabheader" style="width: 80px">Fax</th>
         	 <th class="tabheader" style="width: 80px">Contact <br>Person1</th>
         	 <th class="tabheader" style="width: 8px">Active</th>
         	 <th class="tabheader" style="width: 8px">View <br>Detail</th>
         	 <th class="tabheader" style="width: 8px">Update</th>
         	 <th class="tabheader" style="width: 8px">Status</th>
         	 <th class="tabheader" style="width: 8px">Delete</th>
         	</tr>
         </thead>
		 <tbody>
		 <?php 
		 	$sql = "SELECT * ";
			$sql .= " FROM customer_master";
   		    $sql .= " ORDER BY modified_on";  
		    $rs_result = mysql_query($sql); 
		   			
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) { 
			
			$urlpop = 'upd_cust_mas.php';
			$urlvm = 'vm_cust_mas.php';
			$showdte = date('d-m-Y', strtotime($row['modified_on']));
			echo '<tr>';
            echo '<td>'.$numi.'</td>';
            echo '<td>'.$row['customer_code'].'</td>';
            echo '<td>'.$row['customer_desc'].'</td>';
            echo '<td>'.$row['gstno'].'</td>';
            echo '<td>'.$row['telephone'].'</td>';
            echo '<td>'.$row['fax'].'</td>';
            echo '<td>'.$row['contact_person'].'</td>';
            echo '<td>'.$row['active_flag'].'</td>';
			
			if ($var_accvie == 0){
            echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            }else{
            echo '<td align="center"><a href="'.$urlvm.'?custcd='.$row['customer_code'].'&menucd='.$var_menucode.'">[VIEW]</a>';'</td>';
            }
			
			if ($var_accupd == 0){
            echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
            }else{
            echo '<td align="center"><a href="'.$urlpop.'?custcd='.$row['customer_code'].'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
            }
			
			if ($var_accupd == 0){
			   echo '<td align="center"><input type="checkbox" DISABLED name="custcd[]" value="'.$row['customer_code'].'" />'.'</td>';	
            }else{
               echo '<td align="center"><input type="checkbox" name="custcd[]" value="'.$row['customer_code'].'" />'.'</td>';
            }  
            
			if ($var_accdel == 0){
			 echo '<td align="center"><input type="checkbox" DISABLED name="custcd[]" value="'.$row['customer_code'].'" />'.'</td>';
			}else{ 
             echo '<td align="center"><input type="checkbox" name="custcd[]" value="'.$row['customer_code'].'" />'.'</td>';
			}       
            
            echo '</tr>';
            $numi = $numi + 1;
			}
		 ?>
		 </tbody>
		 </table>
		 
		 <div class="spacer"></div>
         </form>
	 
	</fieldset>
	</div>
</body>

</html>
