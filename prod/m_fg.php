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
		$vprocode = $_POST['prod_code'];
		$vprodesc = $_POST['promodesc'];
            
		if ($vprocode <> "") {
			
			$var_sql = " SELECT count(*) as cnt from pro_jobmodel";
	      	$var_sql .= " Where prod_code = '$vprocode'";

	      	$query_id = mysql_query($var_sql) or die ("Cant Check Product Job Code");
	      	$res_id = mysql_fetch_object($query_id);
             
	      	if ($res_id->cnt > 0 ){
				$backloc = "../bom_master/projob_rate1.php?stat=5&menucd=".$var_menucode;
           		echo "<script>";
           		echo 'location.replace("'.$backloc.'")';
           		echo "</script>";  			
            }else{
				$var_sql = " SELECT count(*) as cnt from pro_cd_master";
				$var_sql .= " Where prod_code = '$vprocode'";

				$query_id = mysql_query($var_sql) or die ("Cant Check Product Job Code");
				$res_id = mysql_fetch_object($query_id);
             
				if ($res_id->cnt == 0 ){
					$backloc = "../bom_master/projob_rate1.php?stat=7&menucd=".$var_menucode;
           			echo "<script>";
           			echo 'location.replace("'.$backloc.'")';
           			echo "</script>"; 
				}else{	
					if(!empty($_POST['jobid']) && is_array($_POST['jobid'])) 
					{	
						foreach($_POST['jobid'] as $value ) {
							if ($value <> ""){
								$arraypre[] = $value;	
							}			
						}
				
				        if (!empty($arraypre)){
							if(count(array_unique($arraypre))<count($arraypre))
							{
								// Array has duplicates
								$backloc = "../bom_master/projob_rate1.php?stat=6&menucd=".$var_menucode;
           						echo "<script>";
           						echo 'location.replace("'.$backloc.'")';
           						echo "</script>"; 
							}
							else
							{
								// Array does not have duplicates
								$j = 1;
								$vartoday = date("Y-m-d");
								foreach($_POST['jobid'] as $row=>$jobidr ) {
									$jobid   = mysql_real_escape_string($jobidr);
									
									$sqld = "select jobfile_desc from jobfile_master";
     		 						$sqld .= " where jobfile_id ='".$jobid."'";
     		 						$sql_resultd = mysql_query($sqld);
     		 						$rowd = mysql_fetch_array($sql_resultd);
     		 						$jobdesc = stripslashes(mysql_real_escape_string($rowd['jobfile_desc']));
									
									$jobrate = mysql_real_escape_string($_POST['promojrate'][$row]);
									$jobsec  = $_POST['prosec'][$row];
									$jobdte  = $_POST['prodteame'][$row];
									
									if (empty($jobdte)){
     		 							$jobdte = "";
     		 						}else{	
     		 							$jobdte = date('Y-m-d', strtotime($jobdte)); 
									}

									if ($jobid <> ""){
										$sql = "INSERT INTO pro_jobmodel values 
												('$vprocode', '$vprodesc', '$j','$jobid', '$jobdesc', '$jobrate', '$jobsec',
												 '$jobdte','$var_loginid', '$vartoday')";
										mysql_query($sql) or die(mysql_error());
										$j = $j + 1;
									}			
								}
								$backloc = "../bom_master/projob_rate1.php?stat=1&menucd=".$var_menucode;
           						echo "<script>";
           						echo 'location.replace("'.$backloc.'")';
           						echo "</script>"; 							
           					}
						}else{
								$backloc = "../bom_master/projob_rate1.php?stat=8&menucd=".$var_menucode;
           						echo "<script>";
           						echo 'location.replace("'.$backloc.'")';
           						echo "</script>"; 						
           				}	
					}
				}
			}		
		}else{
			$backloc = "../bom_master/projob_rate1.php?stat=4&menucd=".$var_menucode;
           	echo "<script>";
            echo 'location.replace("'.$backloc.'")';
            echo "</script>"; 
		}
    }
	
	 if ($_POST['Submit'] == "Delete") {

		if(!empty($_POST['procd']) && is_array($_POST['procd'])) 
		{
            foreach($_POST['procd'] as $value ) {
				$sql = "DELETE FROM pro_jobmodel WHERE prod_code ='".$value."'"; 
			
				mysql_query($sql); 
			}
		   $backloc = "../bom_master/projob_rate1.php?stat=1&menucd=".$var_menucode;
           echo "<script>";
           echo 'location.replace("'.$backloc.'")';
           echo "</script>";        
        }      
    }
    
     if ($_GET['p'] == "Print"){
   	 	$prcode = $_GET['pr'];
        $var_menucode  = $_GET['menucd'];

        $fname = "job_rate.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&pr=".$prcode."&dbsel=".$varrpturldb."&usernm=".$var_loginid;
        $dest .= urlencode(realpath($fname));
        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../bom_master/projob_rate1.php?menucd=".$var_menucode;
       	echo "<script>";
       	echo 'location.replace("'.$backloc.'")';
        echo "</script>"; 
      	
		
   	 }	
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">

<style media="all" type="text/css">
@import "../css/multitable/themes/smoothness/jquery-ui-1.8.4.custom.css";
@import "../css/styles.css";
@import "../css/demo_table.css";
thead th input { width: 90% }

.style2 {
	margin-right: 0px;
}
</style>

<script type="text/javascript" language="javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" language="javascript" src="../js/jquery-ui.min.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.nightly.js"></script>
<script type="text/javascript" src="../media/js/jquery.dataTables.columnFilter.js"></script>

<script type="text/javascript"> 

$(document).ready(function(){
	var ac_config = {
		source: "autocomscrpro1.php",
		select: function(event, ui){
			$("#prod_code").val(ui.item.prod_code);
			$("#promodesc").val(ui.item.prod_desc);
	
		},
		minLength:1
		
	};
	$("#prod_code").autocomplete(ac_config);
});

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


function getState(vjobid, rowid)
{
	var strURL="aja_get_jobiddesc.php?verjobid="+vjobid;
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
				    var rowidde = 'promojdescid'+rowid;
				    var rowidre = 'promojrateid'+rowid;
				    var rowiddte = 'prodteame'+rowid;
					var strrep = req.responseText;
			
					if (strrep != ""){
						var array1 = strrep.split('|');
                
						if (array1[0] != ""){
							array1[0] = array1[0].replace('^',"'");
							array1[0] = array1[0].replace('^',"'");
						  document.getElementById(rowidde).value = array1[0];
						}
						if (array1[2] != ""){	
						  document.getElementById(rowidre).value = array1[2];
						}
						
						var now = new Date();
						var cdate = now.getDate();
						var cmonth = now.getMonth()+1;
						cmonth = ('0' + cmonth).slice(-2);
						var cyear = now.getFullYear();
                		document.getElementById(rowiddte).value = cdate + "-" + cmonth + "-" + cyear;
						
						var table = document.getElementById('dataTable');
						var rowCount = table.rows.length; 
			
						var totjob = 0;
						for(var i = 1; i < rowCount; i++) { 
						    var rowidre = 'promojrateid'+i;
							var coljob = document.getElementById(rowidre).value;
						
							if (!isNaN(coljob) && (coljob != "")){
						
								totjob = parseFloat(totjob) + parseFloat(coljob);		
							   //totjob += parseFloat(coljob).toFixed(4);
						 
							}
						}
						document.InpJobFMas.totaljob.value = parseFloat(totjob).toFixed(4);
							
					}else{
				
						document.getElementById(rowidde).value = "";
						document.getElementById(rowidre).value = "";
						document.getElementById(rowiddte).value = "";
						
						var table = document.getElementById('dataTable');
						var rowCount = table.rows.length; 
			
						var totjob = 0;
						for(var i = 1; i < rowCount; i++) { 
							var rowidre = 'promojrateid'+i;
							var coljob =  document.getElementById(rowidre).value;
							
							if (!isNaN(coljob) && (coljob != "")){
								totjob = parseFloat(totjob) + parseFloat(coljob);		
							}
						}
						document.InpJobFMas.totaljob.value = parseFloat(totjob).toFixed(4);
					}
	
				} else {
					alert("There was a problem while using XMLHTTP:\n" + req.statusText+req.status);
				}
			}
		}
		req.open("GET", strURL, true);
		req.send(null);
	}
}

function addRow(tableID) {

	var table = document.getElementById(tableID);
	var rowCount = table.rows.length; 
	var row = table.insertRow(rowCount);             
	var colCount = table.rows[0].cells.length;             
	
	for(var i = 0; i < colCount; i++) {                 
		var newcell = row.insertCell(i);                 
		newcell.innerHTML = table.rows[rowCount-1].cells[i].innerHTML;
		newcell.id = rowCount;

		switch(newcell.childNodes[0].type) {
	
		//case "hidden":                            
			//newcell.childNodes[0].value = "";
           // newcell.childNodes[0].id = rowCount;				
			//break; 
		case "text": 
		    switch(i){
		     case 0:
		        newcell.childNodes[0].value = "";
                newcell.childNodes[0].id = 'prosec'+rowCount;	
               
               	break;
		    case 2:
		        newcell.childNodes[0].value = "";
                newcell.childNodes[0].id = 'promojdescid'+rowCount;	
               
               	break;
		    case 3:
		      	newcell.childNodes[0].value = "";
                newcell.childNodes[0].id = 'promojrateid'+rowCount;	
                break;
             case 4:
		      	newcell.childNodes[0].value = "";
                newcell.childNodes[0].id = 'prodteame'+rowCount;	
                break;     
		    }    	             
		case "checkbox":                            
			newcell.childNodes[0].checked = false; 
            //newcell.childNodes[0].id = rowCount;			
			break;                    
		case "select-one":                            
			newcell.childNodes[0].selectedIndex = 0; 
            newcell.childNodes[0].id = rowCount;			
			break; 
       	}            
	}
 
}

function deleteRow(tableID) {
			try {
			var table = document.getElementById(tableID);
			var rowCount = table.rows.length;

         
           if (rowCount > 2){
             table.deleteRow(rowCount - 1);
            }else{
              alert ("No More Row To Remove");
            }
            
			var table = document.getElementById('dataTable');
			var rowCount = table.rows.length; 
			
			var totjob = 0;
			for(var i = 1; i < rowCount; i++) { 
			   var rowidre = 'promojrateid'+i;
			   var coljob = document.getElementById(rowidre).value;
						
			   if (!isNaN(coljob) && (coljob != "")){
					totjob = parseFloat(totjob) + parseFloat(coljob);		
					}
				}
				document.InpJobFMas.totaljob.value = parseFloat(totjob).toFixed(4);
			}catch(e) {
				alert(e);
			}
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
    var flgchk = 1;
	var x=document.forms["InpJobFMas"]["prod_code"].value;
	if (x==null || x=="")
	{
	alert("Product Code Must Not Be Blank");
	document.InpJobFMas.prod_code.focus()
	return false;
	}
	
	var strURL="aja_chk_procode.php?procode="+x;
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
					  alert ('This Product Code Not Found');
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
		document.InpJobFMas.prod_code.focus()
	   return false;
	}
	
	var table = document.getElementById('dataTable');
	var rowCount = table.rows.length; 
		
	var mylist = new Array();	
	for(var i = 1; i < rowCount; i++) { 
	                
		var e = document.getElementById(i);
        var vprocdbuy = e.options[e.selectedIndex].value;
       
		if (vprocdbuy != ""){
			    mylist.push(vprocdbuy); 
		}   
	}
	
	mylist.sort();
	var last = mylist[0];
	
	for (var i=1; i < mylist.length; i++) {
		if (mylist[i] == last){ 
			alert ("Duplicate Job ID Found; Job ID " + last);
			 return false;
		}	
		last = mylist[i];
	}
	
}

function get_desc(itemcode)
{
	var strURL="aja_get_prodesc.php?procode="+itemcode;
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
				  
					   document.InpJobFMas.promodesc.value = req.responseText;
		
				} 
			}
		}	 
	}
	req.open("GET", strURL, true);
	req.send(null);

}

function calcAmt(vid)
{
    var vproqty = "promojrateid"+vid;
	
    var col1 = document.getElementById(vproqty).value;	
	if (col1 != ""){
		if(isNaN(col1)) {
    	   alert('Please Enter a valid number for Rate :' + col1);
    	   col1 = 0;
    	}
    	document.getElementById(vproqty).value = parseFloat(col1).toFixed(4);
    }else{
    	col1 = 0;
    	document.getElementById(vproqty).value = parseFloat(col1).toFixed(4);

    }
    
    var table = document.getElementById('dataTable');
	var rowCount = table.rows.length; 

    var totjob = 0;
	for(var i = 1; i < rowCount; i++) { 
		var rowidre = 'promojrateid'+i;
		var coljob = document.getElementById(rowidre).value;
						
		if (!isNaN(coljob) && (coljob != "")){
			totjob = parseFloat(totjob) + parseFloat(coljob);		
		}
	}
	document.InpJobFMas.totaljob.value = parseFloat(totjob).toFixed(4);
}

</script>
</head>
<?php 
	$sql = "DELETE FROM tmpfgstk ";
	$sql.= " WHERE username = '$var_loginid' ";
	    
	mysql_query($sql) or die("Failed to DELETE Temp Table tmpfgstk : ".mysql_error());
	
	$sql = "SELECT DISTINCT prod_code, prod_desc, modified_on, xfac_pcsprice ";
	$sql .= " FROM pro_cd_master";
	//$sql .= " WHERE prod_code in ('E8085-WT-105C', 'M457-XL-MIX', 'M458-FR-MIX')";
	//$sql .= " AND prod_code  = 'E8085-WT-105C' ";
	$sql .= " ORDER BY modified_on ";  
	$rs_result = mysql_query($sql); 
	
	$numi = 1;
	while ($rowq = mysql_fetch_assoc($rs_result)) {
	
		$unitprice = $rowq['xfac_pcsprice'];
		//echo 'kkk-'. $unitprice; 


		$sql1 = "select prod_desc ";
		$sql1 .= " from pro_cd_master";
		$sql1 .= " where prod_code ='".htmlentities($rowq['prod_code'])."'";
		$sql_result1 = mysql_query($sql1);
		$row1 = mysql_fetch_array($sql_result1);
		$proddesc = htmlentities($row1[0]);
		
		
		$sqlo = "select sum(totalqty) from fg_tran ";
	    $sqlo .= " where item_code ='".$rowq['prod_code']."'";
	    $sqlo .= " and tran_type in ('REC', 'ADJ', 'RTN', 'ISS', 'OPB', 'REJ')";
	    $sql_resulto = mysql_query($sqlo);
	    $row_bal = mysql_fetch_array($sql_resulto);   
	    //echo $sqlo;     
	    if ($row_bal[0] == "" or $row_bal[0] == null){ 
	       $row_bal[0]  = 0.00;
	    }
	    $onhandbal = $row_bal[0];
	    
	    if ($unitprice[0] == "" or $unitprice[0] == null){ 
		    $sql = "select sum(totamt)/12 from prod_matmain ";
		    $sql .= " where prod_code ='".$rowq['prod_code']."'";
		    $sql_result = mysql_query($sql);
		    $row_price  = mysql_fetch_array($sql_result);   
		    if ($row_price[0] == "" or $row_price[0] == null){ 
		       $row_price[0]  = 0.00;
		    }
		    $unitprice = $row_price[0];
	    }
	    
	    $unitprice  = number_format((float)$unitprice , 2, '.', '');
	    
	    $amt = 0;
	    $amt = $onhandbal * $unitprice ;
	    
	    //insert into temp table
	    $sql = "INSERT INTO tmpfgstk ";
	    $sql.= " VALUES ('".$rowq['prod_code']."', '$proddesc', '$onhandbal', '$unitprice', '$amt', '$var_loginid')";
	    
	    mysql_query($sql) or die("Failed to insert Temp Table tmpfgstk : ".mysql_error());
	
	
	}
?>

 
  <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="document.InpJobFMas.prod_code.focus()">
 <?php include("../topbarm.php"); ?> 
  <div class="contentc">
  	<fieldset name="Group1" style=" width: 718px;" class="style2">
	 <legend class="title">Finish Goods Stock&nbsp; </legend>
	 <table style="width: 100%; height: 66px;">
	   <?php  
	   	$sql = "select sum(onhand), sum(onhand * uprice)  from tmpfgstk where username = '$var_loginid'";
	    $sql_result = mysql_query($sql);

	    $row= mysql_fetch_array($sql_result);   
	    if ($row[0] == "" or $row[0] == null){ 
	       $row[0]  = 0;
	    }
	    if ($row[1] == "" or $row[1] == null){ 
	       $row[1]  = 0;
	    }

	    $totonhand = $row[0];
	    $unitprice = $row[1];
	    $unitprice  = number_format((float)$unitprice , 2, '.', '');
	   ?>
		  <tr>
			  <td style="width: 244px">Total Onhand Balance</td>
			  <td style="width: 11px">:</td>
			  <td>
			<input name="openingno" id="openingnoid" type="text" maxlength="10" style="width: 191px;" readonly="readonly" value="<?php echo $totonhand; ?>" class="textnoentry1">PCS</td>
		  </tr>
		  <tr>
			  <td style="width: 244px">Total Amount</td>
			  <td style="width: 11px">:</td>
			  <td>
			<input name="amt" id="openingnoid1" type="text" maxlength="10" style="width: 191px;" readonly="readonly" value="<?php echo $unitprice; ?>" class="textnoentry1">MYR</td>
		  </tr>
		  <tr>
			  <td style="width: 244px">&nbsp;</td>
			  <td style="width: 11px">&nbsp;</td>
			  <td>
			  &nbsp;</td>
		  </tr>
		  </table>

	  <form name="LstCatMas" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode;; ?>">
		 <table cellpadding="0" cellspacing="0" id="example" class="display" width="100%">
         <thead>
         	<tr>
         	 <th>
         	 </th>
         	 <th style="width: 331px">Product Code </th>
         	 <th style="width: 526px">Product Description</th>
         	 <th style="width: 237px">Onhand Balance</th>
         	 <th style="width: 245px">Unit Price</th>
         	 <th style="width: 209px">Amount</th>
         	</tr>

         	<tr>
         	 <th class="tabheader" style="width: 20px">#</th>
         	 <th class="tabheader" style="width: 331px">Product Code</th>
         	 <th class="tabheader" style="width: 526px">Product Description</th>
         	 <th class="tabheader" style="width: 237px">Onhand Balance</th>
         	 <th class="tabheader" style="width: 245px">Unit Price</th>
         	 <th class="tabheader" style="width: 209px">Amount</th>
         	 <th class="tabheader" style="width: 20px">Detail</th>
         	</tr>
         	</thead>
		 <tbody>
		 <?php 
        	$sql = "select * from tmpfgstk where username = '$var_loginid'";
			//$sql .= " where onhand > 0 ";
					    
			$rs_result = mysql_query($sql); 
	
		    $numi = 1;
			while ($rowq = mysql_fetch_assoc($rs_result)) { 
				
				$productcode = htmlentities($rowq[productcode]);
        		$productdesc = htmlentities($rowq[productdesc]);
        		$onhand = htmlentities($rowq[onhand]);
        		$unitprice = htmlentities($rowq[uprice]);
        		$amt = htmlentities($rowq[amt]);
        		$amt = number_format((float)$amt , 2, '.', '');
		

				$showdte = date('d-m-Y', strtotime($rowq['modified_on']));
				$urlvie = 'vm_fg_stock.php';
				echo '<tr>';
            	echo '<td>'.$numi.'</td>';
            	echo '<td>'.$productcode.'</td>';
            	echo '<td>'.$productdesc.'</td>';
            	echo '<td align="right">'.$onhand.'</td>';
            	echo '<td align="right">'.$unitprice.'</td>';
            	echo '<td align="right">'.$amt.'</td>';

            
            	if ($var_accvie == 0){
            		echo '<td align="center"><a href="#">[VIEW]</a>';'</td>';
            	}else{
            		echo '<td align="center"><a href="'.$urlvie.'?procd='.$productcode.'&menucd='.$var_menucode.' &qty='.$onhand.' &uprice='.$unitprice.'">[VIEW]</a>';'</td>';
            	}
            
            echo '</tr>';
           
            $numi = $numi + 1;			}
		 ?>

		 </tbody>
		 </table>
		</form>
	</fieldset>
    </div>
      <div class="spacer"></div>
</body>

</html>
