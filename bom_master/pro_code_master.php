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
     $prbuy = stripslashes(mysql_real_escape_string($_POST['procdbuy']));
     $prtyp = stripslashes(mysql_real_escape_string($_POST['procdtyp']));
     $prcdn = stripslashes(mysql_real_escape_string($_POST['procdnum']));
     $prcat = stripslashes(mysql_real_escape_string($_POST['procdcat']));
     $prsiz = stripslashes(mysql_real_escape_string($_POST['procdsiz']));
     $prcol = stripslashes(mysql_real_escape_string($_POST['procdcol']));
     $prdesc = stripslashes(mysql_real_escape_string($_POST['procddesc']));
     $pruom = stripslashes(mysql_real_escape_string($_POST['procduom']));
     $prrmk = stripslashes(mysql_real_escape_string($_POST['procdrmk']));
     $selcdPre = stripslashes(mysql_real_escape_string($_POST['procatnumpre']));
     
     $exfacpcs = stripslashes(mysql_real_escape_string($_POST['exfacpcs']));
     $exfacdoz = stripslashes(mysql_real_escape_string($_POST['exfacdoz']));
     $costpcs = stripslashes(mysql_real_escape_string($_POST['costpcs']));
     $costdoz = stripslashes(mysql_real_escape_string($_POST['costdoz']));
     $tax = stripslashes(mysql_real_escape_string($_POST['tax']));
	  $buyprocd = stripslashes(mysql_real_escape_string($_POST['buyprocd']));
          
     if ($prbuy <> "" && $prtyp <> "" && $prcat <> "" && $prsiz <> "" && $prcol <> "" && prcdn <> "" && $selcdPre <> ""){
            
			$prdesc = str_replace("'", '^', $prdesc);
			$prrmk = str_replace("'", '^', $prrmk);
		 			
		    $prcode = $selcdPre.$prcdn."-".$prcol."-".$prsiz; 
		    $vartoday = date("Y-m-d");	
		    
		    #----------------------Exe Picture Product Code-----------------------------------------------
		    if ($_FILES['uploadfile']['name'] <> "") {  
	          		$dir = '../bom_master/procdimg';
	          		$imgnm = htmlentities($prcode);
	          		include("../Setting/uploadFuc.php");
	        }
	        #---------------------------------------------------------------------------------------------  
	        
	        if ($exfacpcs =='' or $exfacpcs== ' ' or $exfacpcs==NULL){$exfacpcs= 0; }
	        if ($exfacdoz =='' or $exfacdoz == ' ' or $exfacdoz ==NULL){$exfacdoz = 0; }
	        if ($unitpcs =='' or $unitpcs== ' ' or $unitpcs==NULL){$unitpcs= 0; }
	        if ($unitpcs=='' or $unitpcs== ' ' or $unitpcs==NULL){$unitpcs= 0; }
		    	
		   	$sql = "INSERT INTO pro_cd_master values 
		    	   ('$prcode', '$prbuy', '$prtyp','$selcdPre','$prsiz','$prcol', '$prdesc',
		    	   	'$pruom','$prrmk','$imagename','$var_loginid', '$vartoday','$var_loginid', '$vartoday',
		    		'$prcat', 'A', '$prcdn', 0,0, 0,0, '$tax', '$buyprocd')";			
	        mysql_query($sql) or die(mysql_error());
          
	        echo "<script>";
	        echo 'alert("Product Code Assign :'.htmlentities($prcode).'")';
	        echo "</script>";

	        $backloc = "../bom_master/pro_code_master.php?stat=1&menucd=".$var_menucode;
            echo "<script>";
            echo 'location.replace("'.$backloc.'")';
            echo "</script>";
     }else{
       $backloc = "../bom_master/pro_code_master.php?stat=5&menucd=".$var_menucode;
       echo "<script>";
       echo 'location.replace("'.$backloc.'")';
       echo "</script>";    
     }
    }
    
    if ($_POST['Submit'] == "Deactive") {
     if(!empty($_POST['procd']) && is_array($_POST['procd'])) 
     {
           $moby= $var_loginid;
           $moon= date("Y-m-d");
           foreach($_POST['procd'] as $value ) {
		    $sql = "Update pro_cd_master set actvty ='D',";
            $sql .= " modified_by='$moby',";
            $sql .= " modified_on='$moon' WHERE prod_code ='".$value."'";
 
		 	mysql_query($sql) or die(mysql_error());

		   }
		   $backloc = "../bom_master/pro_code_master.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>"; 
       }      
    }
    
     if ($_POST['Submit'] == "Active") {
     	if(!empty($_POST['procd']) && is_array($_POST['procd'])) 
     	{
           $moby= $var_loginid;
           $moon= date("Y-m-d");
           foreach($_POST['procd'] as $value ) {
		    $sql = "Update pro_cd_master set actvty ='A',";
            $sql .= " modified_by='$moby',";
            $sql .= " modified_on='$moon' WHERE prod_code ='".$value."'";
 
		 	mysql_query($sql) or die(mysql_error());

		   }
		   $backloc = "../bom_master/pro_code_master.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";        
     	}      
    }
    
    if ($_POST['Submit'] == "Delete") {
     	if(!empty($_POST['procd']) && is_array($_POST['procd'])) 
     	{
           $moby= $var_loginid;
           $moon= date("Y-m-d");
           foreach($_POST['procd'] as $value ) {
           	
           	//delete from product code master
		    $sql = "DELETE FROM pro_cd_master ";
            $sql .= " WHERE prod_code ='".$value."'";
 
		 	//mysql_query($sql) or die(mysql_error());
		 	mysql_query($sql) or die("Error DELETE Product Code Master :".mysql_error(). ' Failed SQL is -->'. $sql);
		 	
		 	//delete from product jobrate
		    $sql = "DELETE FROM pro_jobmodel ";
            $sql .= " WHERE prod_code ='".$value."'";
 
		 	//mysql_query($sql) or die(mysql_error());
		 	mysql_query($sql) or die("Error DELETE Product Job Rate :".mysql_error(). ' Failed SQL is -->'. $sql);
		 	
		 	//delete from product costing - main
		    $sql = "DELETE FROM prod_matmain ";
            $sql .= " WHERE prod_code ='".$value."'";
 
		 	//mysql_query($sql) or die(mysql_error());
		 	mysql_query($sql) or die("Error DELETE Product Costing - Main :".mysql_error(). ' Failed SQL is -->'. $sql);
		 	
		 	
		 	//delete from product costing - details
		    $sql = "DELETE FROM prod_matlis ";
            $sql .= " WHERE prod_code ='".$value."'";
 
		 	//mysql_query($sql) or die(mysql_error());
		 	mysql_query($sql) or die("Error DELETE Product Costing - Details :".mysql_error(). ' Failed SQL is -->'. $sql);	 	
		 	
		 	//delete from product costing - approval
		    $sql = "DELETE FROM procos_appr  ";
            $sql .= " WHERE pro_code ='".$value."'";
 
		 	//mysql_query($sql) or die(mysql_error());
		 	mysql_query($sql) or die("Error DELETE Product Costing Approval :".mysql_error(). ' Failed SQL is -->'. $sql);
		   }
		   $backloc = "../bom_master/pro_code_master.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";        
     	}      
    }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<link rel="stylesheet" href="../css/lightbox.css" type="text/css" media="screen">	
<style media="all" type="text/css">
@import "../css/styles.css";
@import "../css/demo_table.css";
thead th input { width: 90% }

.style2 {
	margin-right: 28px;
}
.style3 {
	color: #FF0000;
}
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.nightly.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>
<script type="text/javascript" src="../js/lightbox.js"></script>

<script type="text/javascript" charset="utf-8"> 
$(document).ready(function() {
	$('#example').dataTable( {
		"bProcessing": true,
		"bServerSide": true,
		"aLengthMenu": [[10, 25, 50, -1], [10, 25, 50,"All"]],
		"bStateSave": true,
		"bFilter": true,
		"sDom": "Rlfrtip",
		"sAjaxSource": "m_procdpro.php",
		"sPaginationType": "full_numbers",
		"bAutoWidth":false,
		"aoColumns": [
    					{ "bSortable": false },
    					null,
    					null,
    					null,
    					null,
    					null,
    					{ "bSortable": false },
    					{ "bSortable": false },
    					{ "bSortable": false },
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
				     { type: "text" },
				     { type: "text" },
				     null,
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

function AjaxFunction(vprocdcol)
{
   
   var vprocdnum = document.getElementById("procdnumid").value; 
   var vprocdcat = document.getElementById("procdcat").value; 
   var vprocdsiz = document.getElementById("procdsizid").value;
   var vprocdpre = document.getElementById("procatnumpre").value;
   
   var strURL="aja_chk_procd.php?prosiz="+vprocdsiz+"&procol="+vprocdcol+"&cdnum="+vprocdnum+"&pre="+vprocdpre;

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

	      	if (req.responseText != 0){
	   			document.getElementById("msgchk").innerHTML = "<font color=red> This Product Code Is Created</font>";
	  	  	}else {
        		document.getElementById("msgchk").innerHTML = "<font color=green></font>";
      	  	} 
	    }else{
   	      alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
	    }
     }
   }
   req.open("GET", strURL, true);
   req.send(null);
 }
}

function readURL(input) {

	if (input.files && input.files[0]) {
       var reader = new FileReader();

       reader.onload = function (e) {
       $('#proimgpre')
          .attr('src', e.target.result)
          .width(130)
          .height(120);
       };
       reader.readAsDataURL(input.files[0]);
       
    }
}

function is_intAjax(value){

  if (value != ""){
  	if(value % 1 == 0){   
       
    }else {
       //alert ("Code Number Be In Integer"); 
       //document.InpProCDMas.procdnum.focus();	
       //return false;
    }
  }
  
  var probuyer   = document.forms["InpProCDMas"]["procdbuy"].value;
  var protype    = document.forms["InpProCDMas"]["procdtyp"].value;
  var procodenum = value;
  substr(procodenum , 0, 4); 

  var strURL="aja_chk_procdnum.php?propre="+probuyer+"&codenu="+procodenum+"&proty="+protype;
  

  var req = getXMLHTTP();

  if (req)
  {
     req.onreadystatechange = function()
	{
		if (req.readyState == 4)
	    {
	     	if (req.status == 200)
			{
				 if (req.responseText == 0){
	   				document.getElementById("msgcd").innerHTML = "<font color=green></font>";
	   			 }else {
        			document.getElementById("msgcd").innerHTML = "<font color=red>This Code Number Not Yet Create Category.</font>";
       			 } 

			  //document.getElementById("msgcd").innerHTML= req.responseText;
			
			} 
		}
	}	 
   }
   req.open("GET", strURL, true);
   req.send(null);

}

function validateForm()
{
    var x=document.forms["InpProCDMas"]["procdbuy"].value;
	if(!x.match(/\S/)){	
		alert("Product Buyer Cannot Not Be Blank");
		document.InpProCDMas.procdbuy.focus();
		return false;
	}
	
	var x=document.forms["InpProCDMas"]["procdtyp"].value;
	if(!x.match(/\S/)){	
		alert("Product Type Cannot Not Be Blank");
		document.InpProCDMas.procdtyp.focus();
		return false;
	}
	
	var x=document.forms["InpProCDMas"]["procdnum"].value;
	if(!x.match(/\S/)){	
		alert("Product Code Number Cannot Not Be Blank");
		document.InpProCDMas.procdnum.focus();
		return false;
	}
	
	//var x=document.forms["InpProCDMas"]["procdnum"].value;
   	//if(x % 1 != 0){   
    //   alert ("Code Number Must Be In Integer"); 
    //   document.InpProCDMas.procdnum.focus();	
    //   return false;
   // }

    var x=document.forms["InpProCDMas"]["procdcat"].value;
	if(!x.match(/\S/)){	
		alert("Product Category Cannot Not Be Blank");
		document.InpProCDMas.procdcat.focus();
		return false;
	}
	
	var x=document.forms["InpProCDMas"]["procdcat"].value;
	if(!x.match(/\S/)){	
		alert("Product Category Cannot Not Be Blank");
		document.InpProCDMas.procdcat.focus();
		return false;
	}
	
	var x=document.forms["InpProCDMas"]["procdsiz"].value;
	if(!x.match(/\S/)){	
		alert("Product Size Cannot Not Be Blank");
		document.InpProCDMas.procdsiz.focus();
		return false;
	}
	
	var x=document.forms["InpProCDMas"]["procdcol"].value;
	if(!x.match(/\S/)){	
		alert("Product Colour Cannot Not Be Blank");
		document.InpProCDMas.procdcol.focus();
		return false;
	}
	
	//Check the Code Number Is Valid--------------------------------------------------------
	var flgchk = 1;
	var probuyer   = document.forms["InpProCDMas"]["procdbuy"].value;
    var protype    = document.forms["InpProCDMas"]["procdtyp"].value;
    var procodenum = document.forms["InpProCDMas"]["procdnum"].value;
    var strURL="aja_chk_procdnum.php?propre="+probuyer+"&codenu="+procodenum+"&proty="+protype;
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
					if (req.responseText != 0)
					{
					  flgchk = 0;
					  document.InpProCDMas.procdnum.focus();
					  alert ('This Code Number Not Yet Create Category :'+procodenum);
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
	
	//Check the Product Code Is Exits--------------------------------------------------------
	var flgchk = 1;
  
    var vprocdnum = document.getElementById("procdnumid").value; 
    var vprocdcat = document.getElementById("procdcat").value; 
    var vprocdsiz = document.getElementById("procdsizid").value;
    var vprocdcol = document.getElementById("procdcol").value;
    var vprocdpre = document.getElementById("procatnumpre").value;
    
    var strURL="aja_chk_procd.php?prosiz="+vprocdsiz+"&procol="+vprocdcol+"&cdnum="+vprocdnum+"&pre="+vprocdpre;
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
					if (req.responseText != 0)
					{
					  flgchk = 0;
					  document.InpProCDMas.procdsiz.focus();
					  alert ('This Product Code Is Already Create');
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

function getPrefix(proBuyerCd)
{
  var strURL="aja_get_probuyprewd.php?probcode="+proBuyerCd;
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

function getrange()
{
	var buycd = document.getElementById("procdbuy").value;
	var typcd = document.getElementById("procdtyp").value;
	var precd = document.getElementById("procatnumpre").value;
	
	if (buycd != "" && typcd != "" && precd != ""){
		var strURL="aja_get_prorange.php?b="+buycd+"&t="+typcd+"&p="+precd;
  		var req = getXMLHTTP();

  		if (req)
  		{
     		req.onreadystatechange = function()
			{
				if (req.readyState == 4)
			    {
			     	if (req.status == 200)
					{
					 document.getElementById('disprange').innerHTML = req.responseText;
					} 
				}
			}	 
   		}
   		req.open("GET", strURL, true);
   		req.send(null);	
	}else{
		document.getElementById('disprange').innerHTML = "";
	}
}

</script>
</head>
 
  <!--<?php include("../sidebarm.php"); ?>-->
<body onload="document.InpProCDMas.procdbuy.focus()">
  <?php include("../topbarm.php"); ?>
  <div class="contentc">

	<fieldset name="Group1" class="style2" style="width: 1000px">
	 <legend class="title">PRODUCT CODE MASTER</legend>
	  <br>
	  <fieldset name="Group1" style="height: 550px; width: 980px;" >
	  <form name="InpProCDMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" enctype="multipart/form-data" style="height: 200px; width: 945px;" onsubmit="return validateForm()">
		
		<table style="width: 936px" >
	  	  <tr>
	  	    <td></td>
	  	    <td style="width: 187px;">Product Buyer<span class="style3">*</span></td>
	  	    <td>:</td>
	  	    <td style="width: 197px;">
			<select name="procdbuy" id="procdbuy" style="width: 156px" onchange="getPrefix(this.value)">
			 <?php
              $sql = "select distinct pro_buy_code, pro_buy_desc from pro_buy_master ORDER BY pro_buy_code ASC";
              $sql_result = mysql_query($sql);
              echo "<option size =30 selected></option>";
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['pro_buy_code'].'">'.$row['pro_buy_code'].' - '.$row['pro_buy_desc'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>

			</td>
			<td style="width: 31px"></td>
			<td style="width: 124px">&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td style="width: 187px"></td>
	  	    <td></td> 
            <td style="width: 197px" colspan="5"></td> 
	   	  </tr> 
	   	   <tr>
	   	    <td></td>
	  	    <td style="width: 187px">Product Type <span class="style3">*</span></td>
	  	    <td>:</td>
	  	    <td style="width: 197px" colspan="5">
			<select name="procdtyp" id="procdtyp" style="width: 326px">
			 <?php
              $sql = "select type_code, type_desc from protype_master where stat != 'D' ORDER BY type_code ASC";
              $sql_result = mysql_query($sql);
              echo "<option size =30 selected></option>";
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['type_code'].'">'.$row['type_code'].' - '.$row['type_desc'].'</option>';
			   } 
		      } 
	         ?>				   
	       </select>

			</td>
	  	  </tr>  
	  	  <tr><td></td></tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td>Prefix <span class="style3">*</span></td>
	  	  	<td>:</td>
	  	  	<td><div id="selcatpre"></div></td>
	  	  </tr>
	  	  <tr><td></td></tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td>Code Number <span class="style3">*</span></td>
	  	  	<td>:</td>
	  	  	<td>
			<input class="inputtxt" name="procdnum" id ="procdnumid" type="text" maxlength="8" onchange ="upperCase(this.id)" style="width: 59px"  onmousedown="getrange()">&nbsp;
	  	  	<label id="disprange"></label>
	  	  	</td>
	  	  </tr>
	  	  <tr>
	  	  	<td></td>
	  	  	<td></td>
	  	  	<td></td>
	  	  	<td><div id="msgcd"></div></td>
	  	  	<td></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 187px">Product Category<span class="style3">*</span></td>
	  	   <td>:</td>
	  	   <td style="width: 197px" colspan="5">  	   
			<input class="inputtxt" name="procdcat" id ="procdcat" type="text" maxlength="10" onchange ="upperCase(this.id)" style="width: 101px"></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 187px"></td>
	  	   <td></td>
	  	   <td style="width: 197px"></td>
	  	  </tr>
	  	  <tr>
	  	    <td></td>
	  	    <td style="width: 187px">Size<span class="style3">*</span></td>
	  	    <td>:</td>
	  	    <td style="width: 197px">
			<input class="inputtxt" name="procdsiz" id ="procdsizid" type="text" maxlength="10" onchange ="upperCase(this.id)"></td>
			<td style="width: 31px"></td>
			<td style="width: 124px">Colour<span class="style3">*</span></td>
			<td>:</td>
			<td>
			<select name="procdcol" id="procdcol" style="width: 187px" onchange="AjaxFunction(this.value);">
			 <?php
              $sql = "select clr_code, clr_desc from pro_clr_master ORDER BY clr_code ASC";
              $sql_result = mysql_query($sql);
              echo "<option size =30 selected></option>";
                       
			  if(mysql_num_rows($sql_result)) 
			  {
			   while($row = mysql_fetch_assoc($sql_result)) 
			   { 
				echo '<option value="'.$row['clr_code'].'">'.$row['clr_code']." - ".htmlentities($row['clr_desc']).'</option>';
			   } 
		      } 
	         ?>				   
	       </select>
			</td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 187px"></td>
	  	   <td></td>
	  	   <td style="width: 197px"><div id="msgchk"></div></td>
	  	  </tr>
	  	   <tr>
	  	   <td></td>
	  	   <td style="width: 187px">Description</td>
	  	   <td>:</td>
	  	   <td style="width: 197px">
   			<input class="inputtxt" name="procddesc" id ="procddescid" type="text" maxlength="60" style="width: 323px;"></td>
	  	   <td style="width: 31px"></td>
           <td style="width: 124px">UOM</td>
           <td>:</td>
           <td>
	  	   <select name="procduom" style="width: 134px">
			 <?php
              $sql = "select uom_code, uom_desc from prod_uommas ORDER BY uom_code";
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
	  	  </tr>
	  	   <tr>
	  	    <td></td>
	  	  </tr>
	  	   <tr>
	  	    <td></td>
	  	    <td style="width: 187px;;">Remark</td>
            <td>:</td>
            <td style="width: 197px;">
		   <input class="inputtxt" name="procdrmk" id ="procdrmkid" type="text" maxlength="60" style="width: 323px;" /></td>
	  	    <td style="width: 31px;"></td>
	  	    <td>Customer Item Code</td>
	  	    <td>:</td>
	  	    <td>
	  	    <input class="inputtxt" name="buyprocd" id ="buyprocd" type="text" maxlength="60" style="width: 150px;" />
	  	    </td>
	  	  </tr>
	  	    <tr>
	  	    <td></td>
	  	    <td style="width: 187px;;">&nbsp;</td>
            <td>&nbsp;</td>
            <td style="width: 197px;">
		    &nbsp;</td>
	  	    <td style="width: 31px;"></td>
	  	    <td style="width: 124px;"></td>
	  	    <td></td>
	  	    <td></td>
	  	    </tr>
			<tr>
	  	    <td style="height: 20px"></td>
	  	    <td style="width: 187px; height: 20px;"></td>
            <td style="height: 20px"></td>
            <td style="width: 197px; height: 20px;">
		   	 </td>
	  	    <td style="width: 31px; height: 20px;"></td>
	  	    <td style="width: 124px; height: 20px;"></td>
	  	    <td style="height: 20px"></td>
	  	    <td style="height: 20px"></td>
	  	    </tr>
			<tr>
	  	    <td></td>
	  	    <td style="width: 187px;;">Tax (%)</td>
            <td>: </td>
            <td style="width: 197px;">
		   <input class="inputtxt" name="tax" id ="tax" type="text" maxlength="10" style="width: 100px;" value="0.00"></td>
	  	    <td style="width: 31px;"></td>
	  	    <td style="width: 124px;"></td>
	  	    <td></td>
	  	    <td></td>
	  	    </tr>
	  	  <tr>
	  	    <td></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	   <td style="width: 187px">Product Image<br>(Max Image Size 2MB)</td>
	  	   <td>:</td>
	  	   <td>
		   <input name="uploadfile" style="width: 315px" type="file" onchange="readURL(this);"></td> 
	  	   <td style="width: 31px"></td>
	  	   <td colspan="3" rowspan="6">
	  	   <img id="proimgpre" height="120" width="130" src="#">
	  	   </td>  			
	  	  </tr>
    	  <tr>
	  	    <td></td>
	  	    <td style="width: 187px">(.jpg, .png, .gif only)</td>
	  	    <td></td>
	  	  </tr>
	  	  <tr>
	  	    <td></td>
	  	    <td style="width: 187px">&nbsp;</td>
	  	    <td>&nbsp;</td>
	  	    <td> 
		    &nbsp;</td>  
	  	  </tr>
	  	  <tr>
	  	    <td></td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
		   <td style="width: 187px"></td>
		   <td></td>
	  	   <td style="width: 197px">
	  	   <?php
	  	   include("../Setting/btnsave.php");
	  	   ?>
	  	  </tr>
	  	  <tr>
	  	    <td></td>
	  	  </tr>
	  	   <tr>
	  	    <td></td>
	  	    <td style="height: 17px;" colspan="7"><span style="color:#FF0000">
			Message :
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
				    echo("<span>Product Code Duplicated Found For Same Buyer, Type, Category, Size & Colour</span>");
  					break;
  				case 4:
				    echo("<span>Please Fill In The Data To Save (* Require Field)</span>");
  					break;
  				case 5:
				    echo("<span>This Product Category Code Range Is All Used</span>");
  					break;
  				case 6:
				    //echo("<span>End Code Number Cannot Be Empty</span>");
  					break;
  				case 7:
				    //echo("<span>End Code Must Larger Than Start Code</span>");
  					break;
				default:
  					echo "";
				}
			  }	
			?>
            &nbsp;</span></td>
	  	  </tr>

	  	</table>
	   </form>	
	   </fieldset>
	     <br><br>
        <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table>
		 <tr>
		   <td style="width: 985px; height: 38px;" align="right">
              <?php
               	 $locatr = "copy_procode.php?menucd=".$var_menucode;
 			   	 if ($var_accadd == 0){
   					echo '<input disabled="disabled" type=button name = "Submit" value="Copy" class="butsub" style="width: 60px; height: 32px">';
  			     }else{
   					echo '<input type="button" value="Copy" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
  			     }

    	  	   $msgdel = "Are You Sure Change The Status Of Selected Product Code?";
    	  	   include("../Setting/btnactive.php");
    	  	   include("../Setting/btndeactive.php");
    	  	   $msgdel = "Are You Sure Delete Selected Product Code?";
    	  	   include("../Setting/btndelete.php");  
		      ?>
           </td>
		 </tr>
		 </table>
		 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
         	<tr>
         	 <th></th>
         	 <th style="width: 106px">Product Code </th>
         	 <th>Buyer</th>
         	 <th>Type</th>
         	 <th>Description</th>
         	 <th></th>
         	 <th></th>
         	 <th></th>
         	 <th></th>
         	 <th></th>
         	 <th></th>
         	</tr>

         	<tr>
         	 <th class="tabheader" style="width: 10px;">#</th>
         	 <th class="tabheader" style="width: 106px;">Product Code</th>
         	 <th class="tabheader" style="width: 30px;">Buyer</th>
         	 <th class="tabheader" style="width: 30px;">Type</th>
         	 <th class="tabheader" style="width: 200px;">Description</th>
         	 <th class="tabheader" style="width: 20px;">Status</th>
         	 <th class="tabheader" style="width: 20px;">Detail</th>
         	 <th class="tabheader" style="width: 20px;">Update</th>
         	 <th class="tabheader" style="width: 20px;">Active/<br>Deactive</th>
         	 <th class="tabheader" style="width: 20px;">Delete</th>
         	 <th class="tabheader" style="width: 20px;">Replace</th>

         	</tr>
         	</thead>
		 	<tbody>
			<tr>
				<td colspan="12" class="dataTables_empty">Loading data from server</td>
			</tr>
		 </tbody>
		 </table>
         </form> 
	</fieldset>
    </div>
    <div class="spacer"></div>
</body>

</html>
