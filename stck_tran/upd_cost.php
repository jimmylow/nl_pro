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
      $var_menucode = $_GET['menucd'];
      include("../Setting/ChqAuth.php");
    }

    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Update") {
 
        $selfdte = date("Y-m-d", strtotime($_POST['rptofdte']));
     	$seltdte = date("Y-m-d", strtotime($_POST['rptotdte']));

		$sql = "select x.item_code, currency_code, unit_cost, grndate ";
		$sql .= " from rawmat_receive_tran x, rawmat_receive y, rawmat_subcode z, rawmat_master a ";
		$sql .= " where x.rm_receive_id = y.rm_receive_id ";
		$sql .= " and z.main_code = a.rm_code ";
		$sql .= " and z.rm_code = x.item_code ";

		
echo $sql. "</br>";
		$rs_result = mysql_query($sql) or die("Cant Query Opening Table ".mysql_error()); 
		   			
		while ($row = mysql_fetch_assoc($rs_result)){
			
			$curr      = $row['currency_code'];
			$grndate   = $row['grndate'];
			$itemcode  = mysql_real_escape_string($row['item_code']); 
			$unit_cost = $row['unit_cost'];
				
			if ($itemcode != ""){
			
				#--------------select currency, descritpion for the item code from rawmat master-------
				//	$sql1 = "select main_code, description from rawmat_subcode  ";
        		//	$sql1 .= " where rm_code ='".$itemcode."'";
        		//	$sql_result1 = mysql_query($sql1) or die("Cant Query Sub Code Table ".mysql_error());;
        		//	$row1 = mysql_fetch_array($sql_result1);
        		//	$macode = htmlspecialchars($row1[0]);
        		//	$masitmdesc = mysql_real_escape_string($row1[1]);
        			
        		//	$sql2 = "select currency_code, description from rawmat_master ";
        		//	$sql2 .= " where rm_code ='".$macode."'";
        		//	$sql_result2 = mysql_query($sql2) or die("Cant Query Curr From Main Code Table ".mysql_error());;
        		//	$row2 = mysql_fetch_array($sql_result2);
        		//	$curr       = $row2[0];
        		//	if ($masitmdesc == ""){
				//		$masitmdesc = mysql_real_escape_string($row2[1]);
				//	}
				#----------------------------------------------------------
				
				#--------------if opening cost is blank or zero then get it from rawmat price control id = 1 -----
				#--------------table based on sub code follow rawmat opening transaction--------------------------
				//if ($unit_cost == 0){
				//	$sql3 = "select price from rawmat_price_trans ";
       			//	$sql3 .= " where rm_code ='".$itemcode."' and id='1' ";
       			//	$sql_result3 = mysql_query($sql3) or die("Cant Query Price Control Table ".mysql_error());;
       			//	$row3 = mysql_fetch_array($sql_result3);
       			//	$unit_cost = $row3[0];
				//}
				#-------------------------------------------------------------------------------------------------
				
				#-------------Begin convert price to based currency buy rate--------------------------------------
			 	$exhmth = date("n",strtotime($grndate)); 
			 	$exhyr  = date("Y",strtotime($grndate));
				 	
			 	if ($curr == "MYR"){
			 		$brate = 1;
			 	}else{	
			 		$sql4 = "select buyrate from curr_xrate ";
   					$sql4 .= " where xmth ='$exhmth' and xyr ='$exhyr' ";
   					$sql4 .= " and curr_code = '$curr'";
   					$sql_result4 = mysql_query($sql4) or die("Cant Echange Rate Table ".mysql_error());;
   					$row4 = mysql_fetch_array($sql_result4);
   					$brate = $row4[0];
   				}	

				$myr_unit_cost= $unit_cost * $brate;
				#-------------------------------------------------------------------------------------------------
				
				#-------------Begin Update To Opening Tran table in based Currecnt Field--------------------------
				//	if ($unit_cost == ""){$unit_cost = 0;}
				//	if ($basecst == ""){$basecst = 0;}
				//	$sql5  = "Update rawmat_opening_tran set opening_cost  ='$unit_cost', ";
         		//	$sql5 .= "                               open_bas_cost ='$basecst'";
         		//	$sql5 .= " WHERE rm_opening_id = '$openid' and item_code = '$itemcode'";
         		//	mysql_query($sql5) or die("Cant Update Table :".mysql_error());
				#-------------------------------------------------------------------------------------------------
				
				#-------------Begin Update To Opening Tran table Description & Sub Code Table-------------------------
				//if ($itmdesc == ""){
				//	$sql6  = "Update rawmat_opening_tran set description  ='".$masitmdesc."'";
         		//	$sql6 .= " WHERE rm_opening_id = '$openid' and item_code = '$itemcode'";
					//$sql6 .= " And   (description = '' or description is null)";
					//echo $sql6."<br>";
         		//	mysql_query($sql6) or die("Cant Update Opening Desc Table :".mysql_error());
				//}
					
				$sql7  = "Update  rawmat_receive_tran set  myr_unit_cost ='".$myr_unit_cost."'";
         		$sql7 .= " WHERE item_code = '$itemcode' ";
				//$sql7 .= " And   (description  ='' or description is null)";
				//echo $sql7." ".$openid."<br>";
         		mysql_query($sql7) or die("Cant Update Sub Code Desc Table :".mysql_error());  		
				#-------------------------------------------------------------------------------------------------
			}
		}
		echo "<script>";   
        echo "alert('Update Complete.....');"; 
        echo "</script>";
        
        $backloc = "../stck_tran/m_rm_opening.php?menucd=".$var_menucode;	
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

	
<style media="all" type="text/css">
@import "../css/styles.css";
@import "../css/demo_table.css";

.style2 {
	margin-right: 8px;
}
</style>
<script type="text/javascript" src="../js/datetimepicker_css.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery-1.4.4.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.nightly.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>
<script type="text/javascript" src="../js/JavaScriptUtil.js"></script>
<script type="text/javascript" src="../js/Parsers.js"></script>
<script type="text/javascript" src="../js/InputMask.js"></script>

<script type="text/javascript" charset="utf-8"> 

function setup() {

	document.InpRawOpen.rptofdte.focus();
						
 	//Set up the date parsers
    var dateParser = new DateParser("dd-MM-yyyy");
      
	//Set up the DateMasks
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var dateMask1 = new DateMask("dd-MM-yyyy", "rptofdte");
	dateMask1.validationMessage = errorMessage;		
		
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var dateMask1 = new DateMask("dd-MM-yyyy", "rptotdte");
	dateMask1.validationMessage = errorMessage;	
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

function chkSubmit()
{
	var x=document.forms["InpRawOpen"]["rptofdte"].value;
	if (x==null || x=="")
	{
		alert("Opening From Date Must Not Be Blank");
		document.InpRawOpen.rptofdte.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["rptotdte"].value;
	if (x==null || x=="")
	{
		alert("Opening To Date Must Not Be Blank");
		document.InpRawOpen.rptotdte.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["rptofdte"].value;
	var y=document.forms["InpRawOpen"]["rptotdte"].value;
	
    var fromdate = x.split('-');
        from_date = new Date();
        from_date.setFullYear(fromdate[2],fromdate[1]-1,fromdate[0]); 
    
    var todate = y.split('-');
        to_date = new Date();
        to_date.setFullYear(todate[2],todate[1]-1,todate[0]);
    if (from_date > to_date ) 
    {
        alert("Opening To Date Must Larger Then From Date");
		document.InpRawOpen.rptofdte.focus();
		return false;
    }
    
 /*   
    //Check the list of opening got date from date & to date-------------------------------
	var flgchk = 1;	
	var x=document.forms["InpRawOpen"]["rptofdte"].value;
	var y=document.forms["InpRawOpen"]["rptotdte"].value;
    var strURL="aja_chk_opendt.php?fd="+x+"&td="+y;
	
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
					if (req.responseText == 0)
					{
					   	flgchk = 0;
					   	alert ('No Data Exist For The Select From Date : '+x+ ' To Date '+y);
						return false;
					}else{
						//alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
						return false;
					}
				}
			}	 
		}
		
	req.open("GET", strURL, false);
	req.send(null);    	  
    }
    if (flgchk == 0){
	   return false;
	}
    //---------------------------------------------------------------------------------------------------
    *
}	
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
	<div class="contentc">
	<fieldset name="Group1" style=" width: 669px; height: 204px;" class="style2">
	 <legend class="title">UPDATE OPENING COSTING</legend>
	  <br>
	  <form name="InpRawOpen" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 662px;">
		<table>
	  	  <tr>
	  	    <td></td>
	  	    <td style="width: 155px" class="tdlabel">From Opening Date</td>
	  	    <td>:</td> 
	  	    <td style="width: 134px">
				<input class="inputtxt" name="rptofdte" id ="rptofdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptofdte','ddMMyyyy')" style="cursor:pointer">
			</td>
			<td style="width: 27px"></td>
			<td style="width: 161px">To Opening Date</td>
			<td>:</td>
			<td>
				<input class="inputtxt" name="rptotdte" id ="rptotdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptotdte','ddMMyyyy')" style="cursor:pointer">
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td style="width: 155px" class="tdlabel">&nbsp;</td>
	  	    <td></td> 
            <td style="width: 134px"></td> 
	   	  </tr> 
	   	  <tr>
	   	  	<td></td>
	   	  	<td colspan="7">Remark : Convert All Opening Item Cost To Based 
			Currency On Selected Opening Date </td>
	   	  </tr>	
	   	  <tr>
	   	  	<td></td>
	   	  	<td>&nbsp;</td>
	   	  </tr>	
	  	  <tr>
	  	   <td style="width: 181px" colspan="8" align="center">
	  	   
	  	   <?php
	  	   		$locatr = "m_rm_opening.php?menucd=".$var_menucode;			
				echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
	  	   		include("../Setting/btnupdate.php");
	  	   ?>
	  	   </td>
	  	  </tr>
	  	   <tr><td></td></tr>

	  	</table>
	   </form>	
	  
	  
	
	
	</fieldset>
	 </div>
    <div class="spacer"></div>
</body>

</html>
