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
     
     	$frdte = date("Y-m-d", strtotime($_POST['rptofdte']));
     	$qyr   = date("Y", strtotime($_POST['rptofdte']));

     	
     	#----------------Prepare Temp Table For Printing -----------------------------------
     	$sql  = " Delete From tmpagitab where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
        #----------------Prepare Temp Table For Printing -----------------------------------
                                
        $sql = "SELECT rm_code, main_code, description, active_flag ";
		$sql .= " FROM rawmat_subcode";
    	$sql .= " where active_flag = 'ACTIVE'";
        //$sql .= " and rm_code in ('AA002600398')";	
    	$sql .= " Order BY main_code, rm_code";  
    	
		$rs_result = mysql_query($sql); 

		while ($row = mysql_fetch_assoc($rs_result)) { 
		    $itmcd  = mysql_real_escape_string($row['rm_code']);
		    $mitmcd = mysql_real_escape_string($row['main_code']);
		    $itmdes = mysql_real_escape_string($row['description']);
		    
		    
		    $sqlm  = "select category, uom";
		    $sqlm .= " from rawmat_master";
        	$sqlm .= " where rm_code ='$mitmcd'";
        	$sql_resultm = mysql_query($sqlm);
        	$rowm = mysql_fetch_array($sql_resultm);
        	$catcd  = $rowm['category'];
        	$itmuom = $rowm['uom'];
        	
        	$sqlc  = "select cat_desc, itm_grd_cd";
		    $sqlc .= " from cat_master";
        	$sqlc .= " where cat_code ='$catcd'";
        	$sql_resultc = mysql_query($sqlc);
        	$rowc = mysql_fetch_array($sql_resultc);
        	$catdes = $rowc['cat_desc'];
        	$itmgrcd = $rowc['itm_grd_cd'];
        	
        	$sqlc  = "select itm_grp_desc";
		    $sqlc .= " from item_group_master";
        	$sqlc .= " where itm_grp_cd ='$itmgrcd'";
        	$sql_resultc = mysql_query($sqlc);
        	$rowc = mysql_fetch_array($sql_resultc);
        	$grddes = $rowc['itm_grp_desc'];
        	
        	#------------OnHand QTy-----------------------------
			$onhandbal = 0;	
			$sqlo = "select sum(totalqty) from rawmat_tran ";
        	$sqlo .= " where item_code ='$itmcd' ";
        	$sqlo .= " and tran_type in ('REC', 'ADJ', 'RTN', 'ISS', 'OPB', 'REJ')";
        	$sqlo .= " and grndate <= '$frdte'";
        	$sql_resulto = mysql_query($sqlo);
        	$rowo = mysql_fetch_array($sql_resulto);        
        	if ($rowo[0] == "" or $rowo[0] == null){ 
        	  $rowo[0]  = 0.00;
        	}
        	$onhandbal = $rowo[0];

        	if ($onhandbal != 0){
        		#------------AVg Cost-------------------------------
        		$sqlv  = "select sum(y.totalqty * y.myr_unit_cost) / sum(y.totalqty)";
        		$sqlv .= " from rawmat_receive x, rawmat_receive_tran y";
        		$sqlv .= " where y.item_code ='$itmcd'";
				$sqlv .= " and   x.grndate  <= '$frdte'";
				$sqlv .= " and   x.rm_receive_id = y.rm_receive_id";
				$sql_resultv = mysql_query($sqlv);
        		$rowv = mysql_fetch_array($sql_resultv);
        		if ($rowv[0] == "" or $rowv[0] == null){ 
        		  $rowv[0]  = 0.00;
        		}
        		$avgcst = $rowv[0];
				#---------------------------------------------------
				
				if ($avgcst == 0){
					$sqlv1  = "select cost_price";
					$sqlv1 .= " from rawmat_subcode";
					$sqlv1 .= " where rm_code ='$itmcd'";
					$sql_resultv1 = mysql_query($sqlv1);
					$rowv1 = mysql_fetch_array($sql_resultv1);
					if ($rowv1[0] == "" or $rowv1[0] == null){ 
						$rowv1[0]  = 0.00;
					}
					$avgcst = $rowv1[0];
				}
				
				#-------------------------Aging Qty & Amount -------------------------------
				$qty5 = 0;
				$qty4 = 0;
				$qty3 = 0;
				$qty2 = 0;
				$qty1 = 0;
				$bqty = $onhandbal;
				$qyr   = date("Y", strtotime($_POST['rptofdte']));
    			for ($i=5; $i >= 1; $i--){
    				
    				$pdte = $qyr."-01-01";
    				$lpdte = $qyr."-12-31";
      				//echo $pdte.' '.$lpdte."<br>";
					if ($i == 5){		
						$sqlo = "select sum(totalqty) from rawmat_tran ";
        				$sqlo .= " where item_code ='$itmcd' ";
        				$sqlo .= " and tran_type in ('REC','OPB', 'ADJ')";
        				$sqlo .= " and totalqty <> 0";
        				$sqlo .= " and grndate between '$pdte' and '$frdte'";
        			}else{
        				$sqlo = "select sum(totalqty) from rawmat_tran ";
        				$sqlo .= " where item_code ='$itmcd' ";
        				$sqlo .= " and tran_type in ('REC','OPB', 'ADJ')";
        				$sqlo .= " and totalqty <> 0";
        				$sqlo .= " and grndate between '$pdte' and '$lpdte'";
        			}
        			
					$sql_resulto = mysql_query($sqlo);
        			$rowo = mysql_fetch_array($sql_resulto);
        			if ($rowo[0] == "" or $rowo[0] == null){ 
        		  		$rowo[0]  = 0.00;
        			}
        			$tqty = $rowo[0];
        			switch($i){
        			case 5:
		   				if (($bqty - $tqty) > 0){
            				$qty5 = $tqty;
           					$bqty = $bqty - $tqty;
          				}else{
            				$qty5 = $bqty;
            				$bqty = 0;
          				}
          				break;
        			case 4:
          				if (($bqty - $tqty) > 0){
            				$qty4 = $tqty;
            				$bqty = $bqty - $tqty;
          				}else{
            				$qty4 = $bqty;
            				$bqty = 0;
          				}
          				break;
        			case 3:
          				if (($bqty - $tqty) > 0){
            				$qty3 = $tqty;
            				$bqty = $bqty - $tqty;
          				}else{
            				$qty3 = $bqty;
            				$bqty = 0;
          				}
          				break;
					case 2:
          				if (($bqty - $tqty) > 0){
            				$qty2 = $tqty;
            				$bqty = $bqty - $tqty;
          				}else{
            				$qty2 = $bqty;
            				$bqty = 0;
          				}
          				break;
        			case 1;
          					$qty1 = $bqty;
          					$bqty = 0;
          					break;
      				}
      				
      				if ($bqty <= 0){ break;}
  					$qyr = $qyr - 1;
  					
				}
				#--------------------------------------------------------------------------- 
				$balamt = 0;
				$amt1   =0;	
				$amt2   =0;
				$amt3   =0;
				$amt4   =0;
				$amt5   =0;
				$amt6   =0;
        		$balamt = $avgcst * $onhandbal;
        		$amt1   = $avgcst * $qty1;
        		$amt2   = $avgcst * $qty2; 
				$amt3   = $avgcst * $qty3; 
				$amt4   = $avgcst * $qty4; 
				$amt5   = $avgcst * $qty5; 
				$amt6   = $avgcst * $qty6; 

        		if ($qty1 == "" or $qty1 == null){$qty1 = 0;}
        		if ($amt1 == "" or $amt1 == null){$amt1 = 0;}
        		if ($qty2 == "" or $qty2 == null){$qty2 = 0;}
        		if ($amt2 == "" or $amt2 == null){$amt2 = 0;}
        		if ($qty3 == "" or $qty3 == null){$qty3 = 0;}
        		if ($amt3 == "" or $amt3 == null){$amt3 = 0;}
        		if ($qty4 == "" or $qty4 == null){$qty4 = 0;}
        		if ($amt4 == "" or $amt4 == null){$amt4 = 0;}
        		if ($qty5 == "" or $qty5 == null){$qty5 = 0;}
        		if ($amt5 == "" or $amt5 == null){$amt5 = 0;}
        		
				$sqli  = " Insert Into tmpagitab (itmcat, itmcatdesc, itmgrp, itmgrpdesc, itmcode, itmcddesc, avgcst, qtybal, amtbal, ";
        		$sqli .= "  qty1, amt1, qty2, amt2, qty3, amt3, qty4, amt4, qty5, amt5, usernm)";
        		$sqli .= " Values ('$catcd', '$catdes', '$itmgrcd', '$grddes', '$itmcd', '$itmdes', '$avgcst', '$onhandbal', '$balamt', ";
        		$sqli .= "   '$qty1', '$amt1', '$qty2', '$amt2', '$qty3','$amt3', '$qty4', '$amt4', '$qty5', '$amt5','$var_loginid')";
        
        		mysql_query($sqli) or die("Unable Save In Temp Table 2 ".mysql_error());
			}
		}
     	#-----------------------------------------------------------------------------------
     
     	
      	$var_sql = " SELECT count(*) as cnt from tmpagitab ";
      	$query_id = mysql_query($var_sql) or die ("Cant Check Temp Table");
      	$res_id = mysql_fetch_object($query_id);

      	if ($res_id->cnt > 0 ) {

			// Redirect browser
        	$fname = "stkagi_rpts.rptdesign&__title=myReport"; 
        	$dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&fd=".$frdte."&usernm=".$var_loginid."&dbsel=".$varrpturldb;
        	$dest .= urlencode(realpath($fname));

        	//header("Location: $dest" );
        	echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
         	
         	$backloc = "../stck_report/stck_agi_rpts.php?menucd=".$var_menucode;
 			echo "<script>";
        	echo 'location.replace("'.$backloc.'")';
        	echo "</script>";

        }else{
        	echo "<script>";   
      		echo "alert('No Data Found!');"; 
      		echo "</script>";
        }
        	
     }
    } 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />

	
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
	<fieldset name="Group1" style=" width: 560px; " class="style2">
	 <legend class="title">STOCK AGING REPORT (SUMMARY REPORT)</legend>
	  <br>
	  <form name="InpRawOpen" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 134px; width: 527px;">
		<table style="width: 500px">
	  	  <tr>
	  	    <td style="width: 6px"></td>
	  	    <td style="width: 78px" class="tdlabel">As At Date</td>
	  	    <td style="width: 19px">:</td> 
	  	    <td style="width: 304px">
				<input class="inputtxt" name="rptofdte" id ="rptofdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptofdte','ddMMyyyy')" style="cursor:pointer">
			</td>
		  </tr>
	  	  <tr>
	  	    <td style="width: 6px"></td> 
	  	    <td style="width: 78px" class="tdlabel">&nbsp;</td>
	  	    <td style="width: 19px"></td> 
            <td style="width: 304px"></td> 
	   	  </tr> 
	   	
	  	  <tr>
	  	   <td style="width: 6px">
	  	   <td style="width: 78px"></td>
	  	   <td></td>
	  	   <td style="width: 304px">
	  	   <?php
	  	   		include("../Setting/btnprint.php");
	  	   ?>
	  	   </td>
	  	  </tr>
	  	   <tr><td style="width: 6px"></td></tr>

	  	</table>
	   </form>	
	</fieldset>
	 </div>
    <div class="spacer"></div>
</body>

</html>
