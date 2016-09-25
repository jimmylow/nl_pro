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
     if ($_POST['Submit'] == "Print") {
     
     	$itmcat = $_POST['selcategory'];
     	$todte  = date("Y-m-d", strtotime($_POST['rptofdte']));
     	
     	if ($itmcat <> "" and $todte <> ""){
     	
     		#----------------Prepare Temp Table For Printing -----------------------------------
     		$sql  = " Delete From tmpstklvl where usernm = '$var_loginid'";
        	mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
        	
        	if ($itmcat == 'ALL'){
        		$itmcat = "";
        	}	
        	
        	$sql = "SELECT rm_code, uom, category";
			$sql .= " FROM rawmat_master";
    		$sql .= " where active_flag = 'ACTIVE' and category like '".$itmcat."%'";
    		$sql .= " Order BY rm_code"; 
			$rs_result = mysql_query($sql);

			while ($row = mysql_fetch_assoc($rs_result)) { 
		    	$mitmcd = mysql_real_escape_string($row['rm_code']);
		    	$itmuom = $row['uom'];
		    	$itmcat = $row['category'];
		    	
		    	$sqlc  = "select cat_desc";
		    	$sqlc .= " from cat_master";
        		$sqlc .= " where cat_code ='$itmcat'";
        		$sql_resultc = mysql_query($sqlc);
        		$rowc = mysql_fetch_array($sql_resultc);
        		$catdes = $rowc['cat_desc'];


				$sqlm  = "select rm_code, minqty, maxqty, description, cost_price";
		    	$sqlm .= " from rawmat_subcode";
        		$sqlm .= " where main_code ='$mitmcd'";
        		$sql_resultm = mysql_query($sqlm);
        		
        		while ($rowm = mysql_fetch_assoc($sql_resultm)) { 
        			$subcode = mysql_real_escape_string($rowm['rm_code']);
        			$miqty = $rowm['minqty'];
        			$maqty = $rowm['maxqty'];
        			$subcde = mysql_real_escape_string($rowm['description']);
        			$subcdpri = $rowm['cost_price'];
        			if ($subcdpri == "" or $subcdpri == null){$subcdpri  = 0.00;}
        	
        			if ($miqty != 0 or $maqty != 0){
        			        

        				#------------OnHand QTy From Date-----------------------------			
						$sqlo = "select sum(totalqty) from rawmat_tran ";
        				$sqlo .= " where item_code ='$subcode' ";
        				$sqlo .= " and tran_type in ('REC', 'ADJ', 'RTN', 'ISS', 'OPB', 'REJ')";
        				$sqlo .= " and grndate <= '$todte'";
        				$sql_resulto = mysql_query($sqlo);
        				$rowo = mysql_fetch_array($sql_resulto);        
        				if ($rowo[0] == "" or $rowo[0] == null){ 
        		  			$rowo[0]  = 0.00;
        				}
        				$onhandbal = $rowo[0];
        				#-------------------------------------------------------------
        	
						if ($onhandbal <= $miqty or $onhandbal >= $maqty){	
							$sqlv  = "select sum(y.totalqty * y.myr_unit_cost) / sum(y.totalqty)";
        					$sqlv .= " from rawmat_receive x, rawmat_receive_tran y";
        					$sqlv .= " where y.item_code ='$subcode'";
							$sqlv .= " and   x.grndate  <= '$todte'";
							$sqlv .= " and   x.rm_receive_id = y.rm_receive_id";
							$sql_resultv = mysql_query($sqlv);
        					$rowv = mysql_fetch_array($sql_resultv);
        					if ($rowv[0] == "" or $rowv[0] == null){ 
        					  $rowv[0]  = 0.00;
        					}
        					$avgcst = $rowv[0];
        				
        					if ($avgcst == 0){
								$avgcst = $subcdpri;
							}

        					$cbalamt = $avgcst * $onhandbal;
							$sqliq  = " Insert Into tmpstklvl (itmcat, itmcatde, mainstkcd, substkcd, ";
        					$sqliq .= "  maxqty, minqty, subcddesc, onhndbal, avgcst, usernm, itmuom, onhbal) ";
        					$sqliq .= " Values ('$itmcat', '$catdes', '$mitmcd', '$subcode', '$maqty',";
        					$sqliq .= "   '$miqty', '$subcde', '$onhandbal', '$avgcst', '$var_loginid', '$itmuom', '$cbalamt')";
        					mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
						}
					}	
				}	
     		}
     		#-----------------------------------------------------------------------------------
     		
     		$var_sql = " SELECT count(*) as cnt from tmpstklvl ";
      		$var_sql .= " WHERE usernm = '$var_loginid'";
      		$query_id = mysql_query($var_sql) or die ("Cant Check Temp Table");
      		$res_id = mysql_fetch_object($query_id);

      		if ($res_id->cnt == 0 ) {
	     		 echo "<script>";   
      			 echo "alert('No Data Found');"; 
      			 echo "</script>"; 
      		}else{

				// Redirect browser
				if ($itmcat == ""){$itmcat = "ALL";}
        		$fname = "stk_level_rpt.rptdesign&__title=myReport"; 
        		$dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&cat=".$itmcat."&td=".$todte."&usernm=".$var_loginid."&dbsel=".$varrpturldb;
        		$dest .= urlencode(realpath($fname));

        		//header("Location: $dest" );
        		echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        		$backloc = "../stck_report/stck_lvl_rpt.php?menucd=".$var_menucode;
       			echo "<script>";
       			echo 'location.replace("'.$backloc.'")';
       			echo "</script>";
        	}
        }
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

	document.InpRawOpen.selcategory.focus();
						
 	//Set up the date parsers
    var dateParser = new DateParser("dd-MM-yyyy");
      
	//Set up the DateMasks
	var errorMessage = "Invalid date: ${value}. Expected format: ${mask}";
	var dateMask1 = new DateMask("dd-MM-yyyy", "rptofdte");
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
	var x=document.forms["InpRawOpen"]["selcategory"].value;
	if (x==null || x=="")
	{
		alert("Item Category Code Cannot Be Blank");
		document.InpRawOpen.selcategory.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["rptofdte"].value;
	
	var myDate = new Date();
	var then = myDate.getDate()+'-'+(myDate.getMonth()+1)+'-'+myDate.getFullYear(); 
	
    var fromdate = x.split('-');
        from_date = new Date();
        from_date.setFullYear(fromdate[2],fromdate[1]-1,fromdate[0]); 
    
    var todate = then.split('-');
        to_date = new Date();
        to_date.setFullYear(todate[2],todate[1]-1,todate[0]);
    if (from_date > to_date ) 
    {
       alert("As At Date Cannot Larger Than To Date");
	   document.InpRawOpen.rptofdte.focus();
		return false;
    }
}	
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
	<div class="contentc">
	<fieldset name="Group1" style=" width: 598px; height: 160px;" class="style2">
	 <legend class="title">STOCK LEVEL REPORT</legend>
	  <br>
	  <form name="InpRawOpen" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 583px;">
		<table style="width: 573px">
		  <tr>
		  	<td style="width: 15px"></td>
		  	<td style="width: 96px">Category Code</td>
		  	<td style="width: 2px">:</td>
		  	<td style="width: 134px">
		  		<select name="selcategory" style="width: 278px">
			    <?php
                   $sql = "select cat_code, cat_desc from cat_master ORDER BY cat_code ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected></option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['cat_code'].'">'.$row['cat_code']." | ".$row['cat_desc'].'</option>';
				 	 } 
				   }
				   echo '<option value="ALL" selected>ALL | All Stock Category</option>'; 
	            ?>				   
			  </select>
		  	</td>
		  </tr>
		  <tr><td style="width: 15px"></td></tr>	
	  	  <tr>
	  	    <td style="width: 15px"></td>
	  	    <td style="width: 96px" class="tdlabel">As At</td>
	  	    <td style="width: 2px">:</td> 
	  	    <td style="width: 134px">
				<input class="inputtxt" name="rptofdte" id ="rptofdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptofdte','ddMMyyyy')" style="cursor:pointer">
			</td>	
	  	  </tr>
	  	  <tr>
	  	    <td style="width: 15px"></td> 
	  	    <td style="width: 96px" class="tdlabel">&nbsp;</td>
	  	    <td style="width: 2px"></td> 
            <td style="width: 134px"></td> 
	   	  </tr> 
	   	
	  	  <tr>
	  	   <td colspan="8" align="center">
	  	   
	  	   <?php
	  	   		include("../Setting/btnprint.php");
	  	   ?>
	  	   </td>
	  	  </tr>
	  	   <tr><td style="width: 15px">&nbsp;</td></tr>
		    <tr><td style="width: 15px">&nbsp;</td></tr>	
	  	</table>
	   </form>	
	</fieldset>
	 </div>
    <div class="spacer"></div>
</body>

</html>
