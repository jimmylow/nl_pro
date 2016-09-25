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
     $rawmat_category = $_POST['selcategory'];
     $rawmat_stat = $_POST['selactive'];
     $rawmat_curr = $_POST['selcurr'];
     $rawmat_uom = $_POST['seluom'];
     //$rawmat_content = $_POST['rawmat_content'];
     //$rawmat_remark = $_POST['rawmat_remark'];
     //$rawmat_description = $_POST['rawmat_description'];
     //$rawmat_stock_group = $_POST['selstock_group'];
     $rawmat_stock_group = '';
     $rawmat_number = '';
     
     
     $rawmat_description =htmlentities(mysql_real_escape_string($_POST['rawmat_description']));
     $rawmat_remark =htmlentities(mysql_real_escape_string($_POST['rawmat_remark']));
     $rawmat_content =htmlentities(mysql_real_escape_string($_POST['rawmat_content']));

          
     $sqlchk = " select sysno from rm_sysno ";
     $sqlchk.= " where category = '$rawmat_category'";
     
     $dumsysno= mysql_query($sqlchk) or die(mysql_error());
     while($row = mysql_fetch_array($dumsysno))
     {
     	$sysno = $row['sysno'];        
     }
     if ($sysno ==NULL)
     {
     	$sysno = '1';
     	
     	if ($rawmat_category != NULL){
     		$sysno_sql = "INSERT INTO rm_sysno values 
       	         ('$rawmat_category', '$sysno')";
        }

     	mysql_query($sysno_sql);

     }
     $newsysno = $sysno + 1;
     $rawmat_number = str_pad($sysno , 3, '0', STR_PAD_LEFT); //assign running number
     $rawmatcd   =  $rawmat_category . $rawmat_number;

         
     if ($rawmat_category <> "") {
 
      $var_sql = " SELECT count(*) as cnt from rawmat_master ";
      $var_sql .= " WHERE rm_code = '$rawmatcd'";

      $query_id = mysql_query($var_sql) or die ("Cant Check Raw Material Code");
      $res_id = mysql_fetch_object($query_id);

      if ($res_id->cnt > 0 ) {
	      $backloc = "../stck_mas/rawmat_mas.php?stat=3&menucd=".$var_menucode;
          echo "<script>";
          echo 'location.replace("'.$backloc.'")';
          echo "</script>";   
      }else {

         $vartoday = date("Y-m-d H:i:s");
         $sql = "INSERT INTO rawmat_master values 
                ('$rawmatcd', '$rawmat_category', '$rawmat_number',
                 '$rawmat_uom','$rawmat_curr','$rawmat_remark', '$rawmat_description', 
                 '$rawmat_content', '$rawmat_stock_group',
                 '$var_loginid', '$vartoday','$var_loginid', '$vartoday','$rawmat_stat')";

     	 mysql_query($sql); 
     	 
     	 $updsysno_sql = "UPDATE rm_sysno SET sysno = '$newsysno' WHERE category = '$rawmat_category'";

     	 mysql_query($updsysno_sql);
     	 //$backloc = "../stck_mas/rawmat_mas.php?stat=1&menucd=".$var_menucode;
         //echo "<script>";
         //echo 'location.replace("'.$backloc.'")';
         //echo "</script>";      
      } 
     }else{
       $backloc = "../stck_mas/rawmat_mas.php?stat=4&menucd=".$var_menucode;
       echo "<script>";
       echo 'location.replace("'.$backloc.'")';
       echo "</script>";
     }
    }
       
    if ($_POST['Submit'] == "Deactive") {
     if(!empty($_POST['rawmatcd']) && is_array($_POST['rawmatcd'])) 
     {
           $suppmoby= $var_loginid;
           $suppmoon= date("Y-m-d H:i:s");
           foreach($_POST['suppcd'] as $value ) {
		    $sql = "Update rawmat_master set active_flag ='DEACTIVE',";
            $sql .= " modified_by='$suppmoby',";
            $sql .= " modified_on='$suppmoon' WHERE rm_code ='".$value."'";
 
		 	mysql_query($sql); 
		   }
		   $backloc = "../stck_mas/rawmat_mas.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";
       }      
    }
    
     if ($_POST['Submit'] == "Active") {
     if(!empty($_POST['rawmatcd']) && is_array($_POST['rawmatcd'])) 
     {
           $suppmoby= $var_loginid;
           $suppmoon= date("Y-m-d H:i:s");
           foreach($_POST['suppcd'] as $value ) {
		    $sql = "Update rawmat_master set active_flag ='ACTIVE',";
            $sql .= " modified_by='$suppmoby',";
            $sql .= " modified_on='$suppmoon' WHERE rm_code ='".$value."'";
 
		 	mysql_query($sql); 
		   }
		   $backloc = "../stck_mas/rawmat_mas.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";      
     }      
    }
	
	if ($_POST['Submit'] == "Add Color/Size") {
       
        $backloc = "../stck_mas/rawmat_subcode.php?menucd=".$var_menucode;
        echo "<script>";
        echo 'location.replace("'.$backloc.'")';
        echo "</script>";
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
            
        $fname = "raw_mas.rptdesign&__title=myReport"; 
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
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>

<script type="text/javascript" charset="utf-8"> 
$(document).ready(function() {
	$('#example').dataTable( {
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50,"All"]],
		"bStateSave": true,
		"bFilter": true,
		"sPaginationType": "full_numbers"

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
	
	var url="email-ajax.php";
	
	url=url+"?email="+email;
	url=url+"&sid="+Math.random();
	httpxml.onreadystatechange=stateck;
	
	httpxml.open("GET",url,true);
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
   
</script>
</head>
<body OnLoad="document.InpSuppMas.selcategory.focus();">
  <?php include("../topbarm.php"); ?> 
 <!-- <?php include("../sidebarm.php"); ?> -->

  <div class="contentc">

	<fieldset name="Group1" style=" width: 1007px;" class="style2">
	 <legend class="title">RAW MATERIAL MASTER MAIN CODE</legend>
	  <br>
	  <fieldset name="Group1" style="width: 993px; height: 284px">
	  <form name="InpSuppMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 270px; width: 970px;">
		<table style="width: 980px; height: 268px;">
	  	  <tr>
	  	    <td style="width: 2px;"></td>
	  	    <td style="width: 114px;">Category</td>
	  	    <td style="width: 4px;">:</td>
	  	    <td style="width: 274px;">
			<select name="selcategory" style="width: 68px">
			    <?php
                   $sql = "select cat_code, cat_desc from cat_master ORDER BY cat_desc ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['cat_code'].'">'.$row['cat_desc'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select></td>
			<td style="width: 227px">
			<div id="txtCategory" align="center"></div>
			</td>
		    <td style="width: 105px;">Status</td>
	  	    <td style="width: 6px;">:</td>
	  	    <td style="width: 108px;">
			   <select name="selactive" style="width: 125px">
			    <option>ACTIVE</option>
			    <option>DEACTIVATE</option>
			   </select>
			</td>
	  	  </tr>
	   	   <tr>
	   	    <td style="width: 2px;">
	  	    </td>
	  	    <td style="width: 114px;">UOM</td>
	  	    <td style="width: 4px;">:</td>
	  	    <td style="width: 274px;">
			<select name="seluom" style="width: 68px">
			    <?php
                   $sql = "select uom_code, uom_desc from uom_master ORDER BY uom_code ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['uom_code'].'">'.$row['uom_desc'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select></td>
			<td style="height: 38px; width: 227px">
			</td>
		    <td style="width: 105px;">Currency Code</td>
	  	    <td style="width: 6px;">:</td>
	  	    <td style="width: 108px;">
			   <select name="selcurr" style="width: 68px">
			    <?php
                   $sql = "select currency_code, currency_desc from currency_master ORDER BY currency_code ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['currency_code'].'">'.$row['currency_code'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select>
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 114px">Description</td>
	  	    <td style="width: 4px">:</td>
	  	    <td colspan="2">
			<input class="inputtxt" name="rawmat_description" id ="suppcityid" type="text" maxlength="200" onchange ="upperCase(this.id)" style="width: 500px">
			</td>
		  </tr>
		  <tr>
	  	    <td style="width: 2px;">
	  	    </td>
	  	    <td style="width: 114px;">Content</td>
	  	    <td style="width: 4px;">:</td>
	  	    <td style="width: 274px;">
			<input class="inputtxt" name="rawmat_content" id ="supppostcdid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 290px;">
			</td>
			<td style="height: 41px; width: 227px">
			</td>
			<td style="width: 105px;">&nbsp;</td>
            <td style="width: 6px;">&nbsp;</td>
            <td style="width: 108px;">
			   &nbsp;</td>
		  </tr>

		  <tr>
	  	    <td style="width: 2px">
	  	    </td>
	  	    <td style="width: 114px">Remark</td>
	  	    <td style="width: 4px">:</td>
	  	    <td style="width: 274px;">
			<input class="inputtxt" name="rawmat_remark" id ="suppcityid" type="text" maxlength="50" onchange ="upperCase(this.id)" style="width: 290px">
			</td>
			<td style="width: 227px">
			</td>
		  </tr>



          <tr><td colspan="8">
	  	   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  	    <?php
	  	   include("../Setting/btnsave.php");
	  	   ?>
	  	   </td>
	  	  </tr>
		   <tr>
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
		   <td style="width: 992px;" align="right">
           <input type="submit" name="Submit" value="Add Color/Size" class="butsub" style="width: 139px; height: 32px">
			  <?php
		        include("../Setting/btnprint.php");
			    ?>
		   </td>
		 </tr>
		 </table>
		 <table cellpadding="0" cellspacing="0" id="example" style="width: 993px" class="display" width="100%">
         <thead>
         	<tr>
         	 <th></th>
         	 <th>Raw Material Code</th>
         	 <th>Description</th>
         	 <th></th>
         	 <th></th>
         	 <th></th>
            </tr>
         	<tr>
         	 <th class="tabheader" style="width: 20px">#</th>
         	 <th class="tabheader" style="width: 80px">Raw Material Code</th>
         	 <th class="tabheader" style="width: 299px">Description</th>
         	 <th class="tabheader" style="width: 30px">Status</th>
         	 <th class="tabheader" style="width: 30px">View Detail</th>
         	 <th class="tabheader" style="width: 30px">Update</th>
            </tr>
           </thead>
		 <tbody>
		 <?php 
		 
		 	$sql = "SELECT rm_code, description, create_by, creation_time, active_flag ";
			$sql .= " FROM rawmat_master";
   		    $sql .= " ORDER BY rm_code";  
   		    
		    $rs_result = mysql_query($sql); 
		   			
		    $numi = 1;
			while ($row = mysql_fetch_assoc($rs_result)) { 
				
				$showdte = date('d-m-Y', strtotime($row['creation_time']));
				$urlpop = "upd_rm_mas.php?rawmatcd=".$row['rm_code']."&menucd=".$var_menucode;
				$urlvm = "vm_rm_mas.php?rawmatcd=".$row['rm_code']."&menucd=".$var_menucode;
			
				if ($row['active_flag']=='ACTIVE')
				{
					$row['active_flag'] = 'A';
				}else{
					$row['active_flag'] = 'D';
				}
				
				echo '<tr>';
            	echo '<td>'.$numi.'</td>';
            	echo '<td>'.$row['rm_code'].'</td>';
           	 	echo '<td>'.$row['description'].'</td>';
            	echo '<td>'.$row['active_flag'].'</td>';
            
				if ($var_accvie == 0){
              		echo '<td align="center"><a href="#">[VIEW]</a></td>';
            	}else{
              		echo '<td align="center"><a href="'.htmlentities($urlvm).'">[VIEW]</a></td>';
            	}
         
		    	if ($var_accupd == 0){
					echo '<td align="center"><a href="#">[EDIT]</a></td>';
				}else{
            		echo '<td align="center"><a href="'.htmlentities($urlpop).'">[EDIT]</a>';'</td>';
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
