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

	$datefrom = date("01-m-Y");
	$dateto = date("t-m-Y");

if (isset($_POST['Submit'])){
    if ($_POST['Submit'] == "Print") {
        $fdte = date("Y-m-d", strtotime($_POST['rptofdte']));
        $tdte = date("Y-m-d", strtotime($_POST['rptotdte']));
        $datefrom = date("d-m-Y", strtotime($_POST['rptofdte']));
        $dateto = date("d-m-Y", strtotime($_POST['rptotdte']));
        $fjid = $_POST['selfjid'];
        $tjid = $_POST['seltjid'];
        $show0Balance = $_POST['chkShowBalance'];
        $nodata = 0;
        
        #----------------Prepare Temp Table For Printing -----------------------------------
        $sql  = " Delete From tmpfgmove where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
        
        $prod_code_csv = "";
        if (empty($show0Balance)) {
            $sql  = "SELECT sum(totalqty), item_code";
            $sql .= " FROM fg_tran";
            $sql .= " where trxdate between '2001-01-01' and '$tdte'";
            $sql .= " and item_code between '$fjid' and '$tjid'";
            $sql .= " group by item_code having sum(totalqty) != 0";
            
            $rs_result = mysql_query($sql);
            while ($row = mysql_fetch_assoc($rs_result)) {
                if (!empty($prod_code_csv)) {
                    $prod_code_csv .= ",";
                }
                $prod_code_csv .= "'" .$row['item_code']. "'";
            }
            if (empty($prod_code_csv)) {
                /* echo "<script>";
                 echo "alert('No data for the report!');";
                 echo "</script>"; */
                $nodata = 1;
            }
        }
        
        $shardSize = 8000;
        $sqliq = "";
        $k = 0;
        if ($nodata==0) {
            $sql  = "SELECT sum(totalqty), item_code";
            $sql .= " FROM fg_tran";
            $sql .= " where trxdate < '$fdte'";
            $sql .= " and item_code between '$fjid' and '$tjid'";
            if (empty($show0Balance)) {
                $sql .= " and item_code in ($prod_code_csv)";
            }
            $sql .= " group by item_code";
            $rs_result = mysql_query($sql);
            
            while ($row = mysql_fetch_assoc($rs_result)) {
                $bfqty     = $row['sum(totalqty)'];
                $prod_code = $row['item_code'];
                if (empty($bfqty)){$bfqty = 0;}
                
                if ($bfqty <> 0){
                    if ($k % $shardSize == 0) {
                        if ($k != 0) {
                            mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
                        }
                        $sqliq = 'Insert Into tmpfgmove (usernm, prodcd, bfqty) Values ';
                    }
                    $sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$var_loginid', '$prod_code', '$bfqty')";
                    $k = $k + 1;
                }
            }
            if (!empty($sqliq)){
                mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
            }
            
            $shardSize = 8000;
            $sqliq = "";
            $k = 0;
            $sql  = "SELECT sum(totalqty), tran_type, item_code";
            $sql .= " FROM fg_tran";
            $sql .= " where trxdate between '$fdte' and '$tdte'";
            $sql .= " and item_code between '$fjid' and '$tjid'";
            if (empty($show0Balance)) {
                $sql .= " and item_code in (".$prod_code_csv.")";
            }
            $sql .= " group by tran_type, item_code";
            $rs_result = mysql_query($sql);
            
            while ($row = mysql_fetch_assoc($rs_result)) {
                $trqty     = $row['sum(totalqty)'];
                $trty      = $row['tran_type'];
                $prod_code = $row['item_code'];
                if (empty($trqty)){$trqty = 0;}
                
                $oqty = 0;   $iqty = 0;  $rqty = 0;  $aqty = 0; $invqty = 0;
                switch($trty){
                    case "ISS":
                        $oqty = 0;   $iqty = $trqty;  $rqty = 0;  $aqty = 0;
                        break;
                    case "OPB":
                        $oqty = $trqty;   $iqty = 0;  $rqty = 0;  $aqty = 0;
                        break;
                    case "REC":
                        $oqty = 0;   $iqty = 0;  $rqty = $trqty;  $aqty = 0;
                        break;
                    case "ADJ":
                        $oqty = 0;   $iqty = 0;  $rqty = 0;  $aqty = $trqty;
                        break;
                }
                
                if ($k % $shardSize == 0) {
                    if ($k != 0) {
                        mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
                    }
                    $sqliq = 'Insert Into tmpfgmove (usernm, prodcd, openqty, issqty, recqty, adjqty, invoiceqty) Values ';
                }
                $sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$var_loginid', '$prod_code', '$oqty', '$iqty',
   			                                                   '$rqty', '$aqty', '$invqty')";
                $k = $k + 1;
                
            }
            if (!empty($sqliq)){
                mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
            }
            
            // invoice qty
            $shardSize = 8000;
            $sqliq = "";
            $k = 0;
            $sql  = "select sum(si.qtys) as total_qty, si.sysprocd";
            $sql .= " from ship_invdet si";
            $sql .= " inner join ship_invmas s ON s.invno = si.invno";
            $sql .= " where sysprocd != ''";
            $sql .= " and s.invdte between '$fdte' and '$tdte'";
            $sql .= " and si.sysprocd between '$fjid' and '$tjid'";
            $sql .= " group by si.sysprocd";
            $rs_result = mysql_query($sql);
            
            $oqty = 0;   $iqty = 0;  $rqty = 0;  $aqty = 0;
            while ($row = mysql_fetch_assoc($rs_result)) {
                $trqty     = $row['total_qty'];
                $prod_code = $row['sysprocd'];
                if (empty($trqty)){$trqty = 0;}
                
                if ($k % $shardSize == 0) {
                    if ($k != 0) {
                        mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
                    }
                    $sqliq = 'Insert Into tmpfgmove (usernm, prodcd, openqty, issqty, recqty, adjqty, invoiceqty) Values ';
                }
                $sqliq .= (($k % $shardSize == 0) ? '' : ', ') . "('$var_loginid', '$prod_code', '$oqty', '$iqty',
   			                                                   '$rqty', '$aqty', '$trqty')";
                $k = $k + 1;
                
            }
            if (!empty($sqliq)){
                mysql_query($sqliq) or die("Unable Save In Temp Table ".mysql_error());
            }
            
        } // end if nodata=0
        
        $fname = "fg_moverpt.rptdesign&__title=myReport";
        $fname .= "&fd=".$fdte."&td=".$tdte;
        $fname .= "&fpr=".$fjid."&tpr=".$tjid;
        
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&usernm=".$var_loginid."&dbsel=".$varrpturldb;
        $dest .= urlencode(realpath($fname));
        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        //$backloc = "../prod_rpt/fg_moverpt.php?menucd=".$var_menucode;
        //echo "<script>";
        //echo 'location.replace("'.$backloc.'")';
        //echo "</script>";
        
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
		alert("Sewing From Date Cannot Be Blank");
		document.InpRawOpen.rptofdte.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["rptotdte"].value;
	if (x==null || x=="")
	{
		alert("Sewing To Date Cannot Be Blank");
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
        alert("To Date Must Larger Then From Date");
		document.InpRawOpen.rptfdte.focus();
		return false;
    }
}
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
	<div class="contentc">
	<fieldset name="Group1" style=" width: 808px;" class="style2">
	 <legend class="title">FG MOVEMENT REPORT</legend>
	  <br />
	  <form name="InpRawOpen" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 139px; width: 750px;" onSubmit= "return chkSubmit()">
		<table style="width: 807px;">
	  	    <tr>
	  	    	<td style="width: 6px"></td>
	  	    	<td style="width: 180px" class="tdlabel">From Transaction Date</td>
	  	    	<td>:</td> 
	  	    	<td style="width: 134px">
					<input class="inputtxt" name="rptofdte" id ="rptofdte" type="text" value="<?php  echo $datefrom; ?>" style="width: 100px" />
					<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptofdte','ddMMyyyy')" style="cursor:pointer" />
				</td>
				<td style="width: 27px"></td>
				<td style="width: 180px">To Transaction Date</td>
				<td>:</td>
				<td>
					<input class="inputtxt" name="rptotdte" id ="rptotdte" type="text" value="<?php  echo $dateto; ?>" style="width: 100px" />
					<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptotdte','ddMMyyyy')" style="cursor:pointer" />
				</td>
		    </tr>
		    <tr><td></td></tr>
		    <tr>
		    	<td></td>
		    	<td>From Product code</td>
		    	<td>:</td>
		    	<td>
		    		<select name="selfjid" id ="selfjid">
			   		<?php
                   		$sql = "select prod_code from pro_cd_master where actvty != 'D' ORDER BY prod_code";
                   		$sql_result = mysql_query($sql);                       
				   		if(mysql_num_rows($sql_result)) 
				   		{
				   			while($row = mysql_fetch_assoc($sql_result)) 
				   			{ 
				   			    $selected = "";
				   			    if ($fjid == $row['prod_code']) {
				   			        $selected = "selected";
				   			    }
				   			    echo '<option value="'.$row['prod_code'].'" '.$selected.' >'.$row['prod_code'].'</option>';
				 	 		} 
				   		}
	            	?>				   
			  		</select>
		    	</td>
		    	<td></td>
		    	<td>To Product code</td>
		    	<td>:</td>
		    	<td>
		    		<select name="seltjid" id ="seltjid">
			   		<?php
                   		$sql = "select prod_code from pro_cd_master where actvty != 'D' ORDER BY prod_code";
                   		$sql_result = mysql_query($sql);
                       
				   		if(mysql_num_rows($sql_result)) 
				   		{
				   			while($row = mysql_fetch_assoc($sql_result)) 
				   			{ 
				   			    $selected = "";
				   			    if ($tjid == $row['prod_code']) {
				   			        $selected = "selected";
				   			    }
					  			echo '<option value="'.$row['prod_code'].'" '.$selected.' >'.$row['prod_code'].'</option>';
				 	 		} 
				   		}
	            	?>				   
			  		</select>
		    	</td>
		    </tr>
			<tr>
				<td></td>
				<td>Show 0 Balance</td>
				<td>:</td>
				<td>					
					<input type="checkbox" name="chkShowBalance" value="1" <?php echo ($show0Balance==1?"checked":"") ?>/>
				</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr><td></td></tr>
	  	  	<tr>
	  	  	  	<td colspan="8" align="center">	  	   
				<?php
	  	   			include("../Setting/btnprint.php");
	  	   		?>
				</td>
		  </tr>
	  	</table>
	   </form>	
	</fieldset>
	 </div>
    <div class="spacer"></div>
</body>

</html>
