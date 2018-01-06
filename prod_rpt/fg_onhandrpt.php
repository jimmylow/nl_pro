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
        $tdte = date("Y-m-d", strtotime($_POST['rptotdte']));
        $dateto = date("d-m-Y", strtotime($_POST['rptotdte']));
        $fjid = $_POST['selfjid'];
        $tjid = $_POST['seltjid'];
        
        #----------------Prepare Temp Table For Printing -----------------------------------
        $sql  = " Delete From tmpbal_rpt where usernm = '$var_loginid'";
        mysql_query($sql) or die("Unable To Prepare Temp Table For Printing".mysql_error());
        
        $sql  = "SELECT sum(totalqty), item_code";
        $sql .= " FROM fg_tran";
        $sql .= " WHERE trxdate <= '$tdte'";
        $sql .= " AND item_code between '$fjid' AND '$tjid'";
        $sql .= " GROUP BY item_code HAVING sum(totalqty) > 0";
        
        $sql = "SELECT p.prod_code, p.prod_desc, p.prod_uom, p.prod_type, p.cost_pcsprice, t.type_desc, rpt.* FROM pro_cd_master p INNER JOIN (" .$sql. ") AS rpt";
        $sql .= " ON rpt.item_code = p.prod_code COLLATE utf8_unicode_ci";
        $sql .= " INNER JOIN protype_master t ON t.type_code = p.prod_type COLLATE utf8_unicode_ci";
        $rs_result = mysql_query($sql);
            
        while ($row = mysql_fetch_assoc($rs_result)) {
            $var_onhand = $row['sum(totalqty)'];
            $prod_code = $row['item_code'];
            $prod_desc = mysql_real_escape_string($row['prod_desc']);
            $prod_uom = mysql_real_escape_string($row['prod_uom']);
            $prod_type = mysql_real_escape_string($row['prod_type']);
            $type_desc = mysql_real_escape_string($row['type_desc']);
            $excost = $row['cost_pcsprice'];
            
            if (empty($var_onhand)){$var_onhand = 0;}
                            
            if ($var_onhand != 0){
                $balamt = $excost * $var_onhand;
                if (empty($excost)){$excost = 0;}
                $sqli  = " Insert Into tmpbal_rpt (subcode, description, cat, cat_desc, ";
                $sqli .= " balqty, itmuom, avgcst, balamt, usernm)";
                $sqli .= " Values ('$prod_code', '$prod_desc', '$prod_type', '$type_desc', '$var_onhand',";
                $sqli .= " '$prod_uom', '$excost', '$balamt','$var_loginid')";
                mysql_query($sqli) or die("Unable Save In Temp Table ".mysql_error());
            }           
        }           
                
        $fname = "fg_onhandrpt.rptdesign&__title=myReport";
        $fname .= "&td=".$tdte;
        $fname .= "&fpr=".$fjid."&tpr=".$tjid;
        
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&usernm=".$var_loginid."&dbsel=".$varrpturldb;
        $dest .= urlencode(realpath($fname));
        
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
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
	 <legend class="title">FG STOCK BALANCE REPORT</legend>
	  <br />
	  <form name="InpRawOpen" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 139px; width: 750px;" onSubmit= "return chkSubmit()">
		<table style="width: 807px;">
			<tr>
	  	    	<td style="width: 6px"></td>
	  	    	<td style="width: 180px" class="tdlabel">As At Date</td>
	  	    	<td>:</td> 
	  	    	<td style="width: 134px">
					<input class="inputtxt" name="rptotdte" id ="rptotdte" type="text" value="<?php  echo $dateto; ?>" style="width: 100px" />
					<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptotdte','ddMMyyyy')" style="cursor:pointer" />
				</td>
				<td style="width: 27px"></td>
				<td style="width: 180px"></td>
				<td></td>
				<td>
				</td>
		    </tr>	  	   
		  </tr>
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
