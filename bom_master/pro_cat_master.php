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
     $pcatbuy = $_POST['procatbuy'];
     $pcattyp = $_POST['procattyp'];
     $pcatpre = $_POST['procatnumpre'];
     $pcatfnum = $_POST['procatfrnum'];
     $pcattnum = $_POST['procattonum'];
     
     if ($pcatpre <> "") {
      if ($pcatfnum <> ""){
        if ($pcattnum <> ""){
          if ($pcatfnum > $pcattnum){
            $backloc = "../bom_master/pro_cat_master.php?stat=7&menucd=".$var_menucode;
            echo "<script>";
            echo 'location.replace("'.$backloc.'")';
            echo "</script>";
          }else{  
            $chkflg = '1';
            for($i = $pcatfnum; $i <= $pcattnum; $i++){
	      		$var_sql = " SELECT count(*) as cnt from pro_cat_master ";
    			$var_sql .= " WHERE pro_cat_prefix = '$pcatpre'";
    			$var_sql .= " And pro_type_cd= '$pcattyp'";
    			$var_sql .= " and '$i' between pro_cat_frnum and pro_cat_tonum";
				$var_sql .= " and pro_buy_cd = '$pcatbuy'";
    
	      		$query_id = mysql_query($var_sql) or die ("Cant Check Product Category Code");
	      		$res_id = mysql_fetch_object($query_id);

	      		if ($res_id->cnt > 0 ){
	      		     $chkflg = '0';
		  	   		 $backloc = "../bom_master/pro_cat_master.php?stat=3&menucd=".$var_menucode;
            		 echo "<script>";
            		 echo 'location.replace("'.$backloc.'")';
            		 echo "</script>";
		  	   	}
		  	}   		
	        if ($chkflg == '1'){ 
	      	   $vartoday = date("Y-m-d H:i:s");
	      	   $sql = "INSERT INTO pro_cat_master values 
	      	          ('$pcatbuy', '$pcattyp', '$pcatfnum','$pcattnum','$var_loginid', '$vartoday',
	      	           '$var_loginid', '$vartoday','$pcatpre')";

	           mysql_query($sql); 
	     	   $backloc = "../bom_master/pro_cat_master.php?stat=1&menucd=".$var_menucode;
               echo "<script>";
               echo 'location.replace("'.$backloc.'")';
               echo "</script>";
	      	}
	      }	
	    }else{
	      $backloc = "../bom_master/pro_cat_master.php?stat=6&menucd=".$var_menucode;
          echo "<script>";
          echo 'location.replace("'.$backloc.'")';
          echo "</script>";
	    }   
	   }else{
	     $backloc = "../bom_master/pro_cat_master.php?stat=5&menucd=".$var_menucode;
         echo "<script>";
         echo 'location.replace("'.$backloc.'")';
         echo "</script>";
	   }   
     }else{
       $backloc = "../bom_master/pro_cat_master.php?stat=4&menucd=".$var_menucode;
       echo "<script>";
       echo 'location.replace("'.$backloc.'")';
       echo "</script>";
     }
    }
       
    if ($_POST['Submit'] == "Delete") {
     if(!empty($_POST['procatccd']) && is_array($_POST['procatccd'])) 
     {
           foreach($_POST['procatccd'] as $key) {
           
             $defarr = explode(",", $key);
			
             $var_propre = $defarr[2];
             $var_protyp = $defarr[0];
             $var_procsnum = $defarr[3];
             $var_procenum = $defarr[4];
			 $pcatbuy = $defarr[1];
            
		     $sql = "DELETE FROM pro_cat_master "; 
		     $sql .= "WHERE pro_type_cd ='".$var_protyp."' And pro_cat_prefix = '".$var_propre."' "; 
		     $sql .= "And pro_cat_frnum ='".$var_procsnum."' And pro_cat_tonum = '".$var_procenum."'";
			 $sql .= " and pro_buy_cd = '$pcatbuy'";
	
		 	 mysql_query($sql) or die("Query Delete :".mysql_error()); 
		   }
		   $backloc = "../bom_master/pro_cat_master.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
          echo 'location.replace("'.$backloc.'")';
           echo "</script>";
       }      
    }
    
    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
    
        $fname = "pro_cat_master.rptdesign&__title=myReport"; 
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
				     { type: "text" },
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

function is_int(value){

  if (value != ""){
  	 	if (value % 1 == 0){
        	return true;
    	}else{
    		alert ("Code Number Start Must Be In Integer"); 
    		document.InpProCatMas.procatfrnum.focus();
        	return false;	
  		} 
  }		 
}

function is_intAjax(value){

  if (value != ""){
  	if(value % 1 == 0){   
       
    }else {
       alert ("Code Number End Must Be In Integer"); 
       document.InpProCatMas.procattonum.focus();	
       return false;
    }
  }
  
  var probuy    = document.forms["InpProCatMas"]["procatbuy"].value;
  var probuypre = document.forms["InpProCatMas"]["procatnumpre"].value;
  var protype   = document.forms["InpProCatMas"]["procattyp"].value;
  var procatst  = document.forms["InpProCatMas"]["procatfrnum"].value;
  var procatto  = value;
 
  var strURL="aja_chk_procatcd.php?probuypre="+probuypre+"&protyp="+protype+"&procatst="+procatst+"&procatto="+procatto+"&bu="+probuy;
  

  var req = getXMLHTTP();

  if (req)
  {
     req.onreadystatechange = function()
	{
		if (req.readyState == 4)
	    {
	     	if (req.status == 200)
			{
			    if (req.responseText > 0){
					document.getElementById("msgcd").innerHTML= "<font color=red> This Product Category Code Has Been Use Or Fall In Same Range</font>";
			    }else{
					document.getElementById("msgcd").innerHTML= "<font color=green>This Product Category Code Is Valid</font>";
			    }		
			} 
		}
	}	 
   }
   req.open("GET", strURL, true);
   req.send(null);

}

function getPrefix(proBuyerCd)
{
  var strURL="aja_get_probuypre.php?probcode="+proBuyerCd;
  var req = getXMLHTTP();

  if (req)
  {
     req.onreadystatechange = function()
	{
		if (req.readyState == 4)
	    {
	     	if (req.status == 200)
			{
				 document.getElementById("selcatpre").innerHTML = req.responseText;
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
    var x=document.forms["InpProCatMas"]["procatbuy"].value;
	if(!x.match(/\S/)){	
		alert("Product Buyer Cannot Not Be Blank");
		document.InpProCatMas.procatbuy.focus();
		return false;
	}
	
	var x=document.forms["InpProCatMas"]["procattyp"].value;
	if(!x.match(/\S/)){	
		alert("Product Type Cannot Not Be Blank");
		document.InpProCatMas.procattyp.focus();
		return false;
	}
	
	var x=document.forms["InpProCatMas"]["procatfrnum"].value;
	if(!x.match(/\S/)){	
		alert("Product Category Start Number Cannot Not Be Blank");
		document.InpProCatMas.procatfrnum.focus();
		return false;
	}

    var x=document.forms["InpProCatMas"]["procattonum"].value;
	if(!x.match(/\S/)){	
		alert("Product Category End Number Cannot Not Be Blank");
		document.InpProCatMas.procattonum.focus();
		return false;
	}

	var x=document.forms["InpProCatMas"]["procatfrnum"].value;
	var y=document.forms["InpProCatMas"]["procattonum"].value;
   	if(x % 1 != 0){   
       alert ("Code Number Start Must Be In Integer"); 
       document.InpProCatMas.procatfrnum.focus();	
       return false;
    }
 
   	if(y % 1 != 0){   
       alert ("Code Number End Must Be In Integer"); 
       document.InpProCatMas.procattonum.focus();	
       return false;
    }
 
    if (parseInt(x) > parseInt(y)){
        alert ("End Number Must Larger Then Start Number");
        document.InpProCatMas.procatfrnum.focus();
        return false;
    }
	
	//Check the Duplicate Prefix, Start Number & End Number Valid--------------------------------------------------------
	var flgchk = 1;
	var b = document.forms["InpProCatMas"]["procatbuy"].value;
	var k = document.forms["InpProCatMas"]["procattyp"].value;
	var x = document.forms["InpProCatMas"]["procatnumpre"].value;
	var y = document.forms["InpProCatMas"]["procatfrnum"].value;
	var z = document.forms["InpProCatMas"]["procattonum"].value;
	var strURL="aja_chk_procatcd.php?probuypre="+x+"&protyp="+k+"&procatst="+y+"&procatto="+z+"&bu="+b;
	
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
				
					if (req.responseText > 0)
					{
					
					  flgchk = 0;
					  document.InpProCatMas.procatfrnum.focus();
					  alert ('This Product Category Code Has Been Use Or Fall In Same Range:'+x+y+"-"+z);
					  return false;
					}
				} else {
					//alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
					return false;
				}
			}
		}	 
	}
	req.open("GET", strURL, false);
	req.send(null);
    if (flgchk == 0){
	   return false;
	}
	//---------------------------------------------------------------------------------------------------
}
</script>
</head>
 
  <!--<?php include("../sidebarm.php"); ?>-->
<body onload="document.InpProCatMas.procatbuy.focus()">

 <?php include("../topbarm.php"); ?> 
  <div class="contentc">
	<fieldset name="Group1" style=" width: 807px;" class="style2">
	 <legend class="title">PRODUCT PREFIX RANGE</legend>
	  <br>
	  <fieldset name="Group1" style="width: 781px; height: 180px">
	  <form name="InpProCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 200px; width: 740px;" onsubmit="return validateForm()">
		<table style="width: 766px; height: 118px">
	  	  <tr>
	  	    <td style="width: 5px"></td>
	  	    <td style="width: 123px">Product Buyer </td>
	  	    <td style="width: 12px">:</td>
	  	    <td style="width: 631px">
			<select name="procatbuy" style="width: 179px" onchange="getPrefix(this.value)">
			 <?php
              $sql = "select distinct pro_buy_code, pro_buy_desc from pro_buy_master ORDER BY pro_buy_code ASC";
              $sql_result = mysql_query($sql);
              echo "<option size =30 selected></option>";
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['pro_buy_code'].'">'.$row['pro_buy_code']." - ".$row['pro_buy_desc'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td style="width: 5px"></td> 
	  	    <td style="width: 123px"></td>
	  	    <td style="width: 12px"></td> 
            <td style="width: 631px"></td> 
	   	  </tr> 
	   	   <tr>
	   	    <td style="width: 5px"></td>
	  	    <td style="width: 123px">Product Type </td>
	  	    <td style="width: 12px">:</td>
	  	    <td style="width: 631px">
			<select name="procattyp" style="width: 346px">
			 <?php
              $sql = "select type_code, type_desc from protype_master where stat != 'D' ORDER BY type_code ASC";
              $sql_result = mysql_query($sql);
              echo "<option size =30 selected></option>";
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['type_code'].'">'.$row['type_code']." - ".$row['type_desc']." (".$row['type_code'].")".'</option>';
			   } 
		      } 
	         ?>				   
	       </select>

			</td>
	  	  </tr>  
	  	  <tr>
	  	   <td></td>
	  	   <td></td>
	  	   <td></td>
	  	   <td style="width: 631px"></td>
	  	  </tr>
	  	  <tr>
	  	    <td></td>
	  	    <td>Code Number</td>
	  	    <td>:</td>
	  	    <td>
	  	    <strong>Prefix</strong>&nbsp; <span id="selcatpre"></span><strong>&nbsp;
			&nbsp;&nbsp;
		   Start&nbsp; </strong>&nbsp;<input class="inputtxt" name="procatfrnum" id ="procatfrnumid" type="text" maxlength="6" onchange ="upperCase(this.id)" style="width: 59px" onBlur="is_int(this.value);">
			<strong>&nbsp;
            End&nbsp;&nbsp; </strong>
   		   <input class="inputtxt" name="procattonum" id ="procattonumid" type="text" maxlength="6" onchange ="upperCase(this.id)" style="width: 65px" onBlur="is_intAjax(this.value);">

	  	    </td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td></td>
	  	   <td></td>
	  	   <td style="width: 631px"><div id="msgcd"></div></td>
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td></td>
	  	   <td></td>
	  	   <td style="width: 631px"></td>
	  	  </tr>
	  	  <tr>
	  	   <td style="width: 5px"></td>
	  	   <td style="width: 123px"></td>
	  	   <td style="width: 12px"></td>
	  	   <td style="width: 631px">
	  	   <?php
	  	   include("../Setting/btnsave.php");
	  	   ?>
	  	   </td>
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td></td>
	  	   <td></td>
	  	   <td></td>
	  	  </tr>
	  	   <tr>
	  	    <td style="width: 5px"></td>
	  	    <td></td>
	  	    <td></td>
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
				    echo("<span>Duplicated Found Or Code Number Fall In Same Range</span>");
  					break;
  				case 4:
				    echo("<span>Please Fill In The Data To Save</span>");
  					break;
  				case 5:
				    echo("<span>Start Code Number Cannot Be Empty</span>");
  					break;
  				case 6:
				    echo("<span>End Code Number Cannot Be Empty</span>");
  					break;
  				case 7:
				    echo("<span>End Code Must Larger Than Start Code</span>");
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
		   <td style="width: 788px; height: 38px;" align="right">
              <?php
    	  	   include("../Setting/btnprint.php");
		       $msgdel = "Are You Sure Delete Selected Product Category Code?";
    	  	   include("../Setting/btndelete.php"); 
		      ?>
           </td>
		 </tr>
		 </table>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" style="width: 98%">
         <thead>
         	<tr>
         	 <th></th>
         	 <th style="width: 12px">Type</th>
         	 <th>Buyer</th>
         	 <th>Prefix</th>
         	 <th>Start Number</th>
         	 <th>End Number</th>
         	 <th></th>
         	</tr>
         	<tr>
         	 <th class="tabheader" style="width: 10px">#</th>
         	 <th class="tabheader" style="width: 12px">Type</th>
         	 <th class="tabheader" style="width: 55px">Buyer</th>
         	 <th class="tabheader" style="width: 34px">Prefix</th>
         	 <th class="tabheader" style="width: 44px">Start Number</th>
         	 <th class="tabheader" style="width: 47px">End Number</th>         	
         	 <th class="tabheader" style="width: 30px">Delete</th>
         	</tr>
           </thead>
		 <tbody>
		 <?php 
		    $sql = "SELECT pro_type_cd, pro_buy_cd, pro_cat_prefix, pro_cat_frnum, pro_cat_tonum ";
		    $sql .= " FROM pro_cat_master";
    		$sql .= " ORDER BY pro_cat_prefix, pro_cat_frnum";  
			$rs_result = mysql_query($sql); 
		 
		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 
			
			$urlpop = 'upd_procat_mas.php';
			echo '<tr bgcolor='.$defaultcolor.'>';
            echo '<td>'.$numi.'</td>';
            echo '<td>'.$rowq['pro_type_cd'].'</td>';
            echo '<td>'.$rowq['pro_buy_cd'].'</td>';
            echo '<td>'.$rowq['pro_cat_prefix'].'</td>';
            echo '<td>'.$rowq['pro_cat_frnum'].'</td>';
            echo '<td>'.$rowq['pro_cat_tonum'].'</td>';       
            
            //if ($var_accupd == 0){
            //echo '<td align="center"><a href="#">[EDIT]</a>';'</td>';
            //}else{
            //echo '<td align="center"><a href="'.$urlpop.'?procattyp='.$rowq['pro_type_cd'].'&propref='.$rowq['pro_cat_prefix'].'&prosnum='.$rowq['pro_cat_frnum'].'&proenum='.$rowq['pro_cat_tonum'].'&menucd='.$var_menucode.'">[EDIT]</a>';'</td>';
            //}
            
            if ($var_accdel == 0){
              echo '<td align="center"><input type="checkbox" DISABLED  name="procatccd[]" value="'.$rowq['pro_buy_code'].'" />'.'</td>';
            }else{
              $values = implode(',', $rowq);
              echo '<td align="center"><input type="checkbox" name="procatccd[]" value="'.$values.'" />'.'</td>';
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
