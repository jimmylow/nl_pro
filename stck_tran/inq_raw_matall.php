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
      $var_trantype = $_GET['t'];
      include("../Setting/ChqAuth.php");
    }

    switch ($var_trantype) {
      case "R" :  $fname = "rm_receive_rpt.rptdesign&__title=myReport"; 
                  $var_title = "Receive";  break;
      case "I" :  $fname = "rm_issue_rpt.rptdesign&__title=myReport"; 
                  $var_title = "Issue";  break; 
      case "A" :  $fname = "rm_adj_rpt.rptdesign&__title=myReport"; 
                  $var_title = "Adjustment";  break;   
      case "N" :  $fname = "rm_return_rpt.rptdesign&__title=myReport"; 
                  $var_title = "Return";  break; 
      case "E" :  $fname = "rm_reject_rpt.rptdesign&__title=myReport"; 
                  $var_title = "Reject";  break;                                                                      
    }
       
    if (isset($_POST['Submit'])){ 
     if ($_POST['Submit'] == "Print") {
     
     	$frdte = date("Y-m-d", strtotime($_POST['rptofdte']));
     	$todte = date("Y-m-d", strtotime($_POST['rptotdte']));
      
      
      //-------------- get data --------------//

    switch ($var_trantype) {
      
     case "I" : 
      $sql = "delete from tmp_issue_rawmat where username = '$var_loginid'";
      mysql_query($sql) or die ("Cant delete issue rawmat : ".mysql_error());
      
      $sql = " select x.rm_issue_id, x.issuedate, y.item_code, y.oum, y.description, y.totalqty";
      $sql .= " from rawmat_issue x, rawmat_issue_tran y";
      $sql .= " where x.issuedate between '$frdte' and '$todte'";
      $sql .= " and  x.rm_issue_id = y.rm_issue_id";
      
      $tmp = mysql_query($sql) or die("Cant get data for rm : ".mysql_error());
      
      if (mysql_numrows($tmp) >0) {
      
        while ($row = mysql_fetch_array($tmp)) {
      
          $sql2 = "select sum(x.totalqty * x.myr_unit_cost) / sum(x.totalqty) as avgcost ";
          $sql2 .= " from rawmat_receive_tran x, rawmat_receive y";
          $sql2 .= " where x.item_code = '".$row['item_code']."'";
          $sql2 .= " and y.grndate <= '$todte'";
          $sql2 .= " and y.rm_receive_id = x.rm_receive_id";

          //echo "<br>".$sql2; 
          $tmp2 = mysql_query($sql2) or die ("Cant get avg iss : ".mysql_error());
          
          if(mysql_numrows($tmp2) >0) {
          
            $rst2 = mysql_fetch_object($tmp2);
            $var_avgcost = $rst2->avgcost;
            if(is_null($var_avgcost)) { $var_avgcost = 0;  }
            } else { $var_avgcost = 0; }
			
			if ($var_avgcost == 0){
				$sqlv1  = "select cost_price";
				$sqlv1 .= " from rawmat_subcode";
				$sqlv1 .= " where rm_code ='".$row['item_code']."'";
				$sql_resultv1 = mysql_query($sqlv1);
				$rowv1 = mysql_fetch_array($sql_resultv1);
				if ($rowv1[0] == "" or $rowv1[0] == null){ 
					$rowv1[0]  = 0.00;
				}
				$var_avgcost = $rowv1[0];
			}

            $sql2 = "select description from rawmat_subcode ";
            $sql2 .= " where rm_code = '".$row['item_code']."'";
            
            $tmp2 = mysql_query($sql2) or die ("Cant get desc iss : ".mysql_error());
          
            if(mysql_numrows($tmp2) >0) {

              $rst2 = mysql_fetch_object($tmp2);
              $var_desc = mysql_real_escape_string($rst2->description);               
             } else { $var_desc = ""; }
           
            $sql2 = "insert into tmp_issue_rawmat";
            $sql2 .= " values('".$var_loginid."', '".$row['rm_issue_id']."','".mysql_real_escape_string($row['item_code'])."','".$var_desc;
            $sql2 .= "','".$row['oum']."','".$row['totalqty']."','$var_avgcost')";
            
            //echo "<br>".$sql2;
            $tmp2 = mysql_query($sql2) or die ("Cant insert : ".mysql_error());
            
          } 
        }
        break;

     case "N" : 
      $sql = "delete from tmp_return_rawmat where username = '$var_loginid'";
      mysql_query($sql) or die ("Cant delete return rawmat : ".mysql_error());
      
      $sql = " select x.rm_return_id, x.returndate, y.item_code, y.oum, y.totalqty";
      $sql .= " from rawmat_return x, rawmat_return_tran y";
      $sql .= " where x.returndate between '$frdte' and '$todte'";
      $sql .= " and  x.rm_return_id = y.rm_return_id";
      
      $tmp = mysql_query($sql) or die("Cant get data return for rm : ".mysql_error());
      
      if (mysql_numrows($tmp) >0) {
      
        while ($row = mysql_fetch_array($tmp)) {
      
          $sql2 = "select sum(x.totalqty * x.myr_unit_cost) / sum(x.totalqty) as avgcost ";
          $sql2 .= " from rawmat_receive_tran x, rawmat_receive y";
          $sql2 .= " where x.item_code = '".$row['item_code']."'";
          $sql2 .= " and y.grndate <= '$todte'";
          $sql2 .= " and y.rm_receive_id = x.rm_receive_id";

          //echo "<br>".$sql2; 
          $tmp2 = mysql_query($sql2) or die ("Cant get avg ret : ".mysql_error());
          
          if(mysql_numrows($tmp2) >0) {
          
            $rst2 = mysql_fetch_object($tmp2);
            $var_avgcost = $rst2->avgcost;
            if(is_null($var_avgcost)) { $var_avgcost = 0;  }
            } else { $var_avgcost = 0; }
            
            if ($var_avgcost == 0){
				$sqlv1  = "select cost_price";
				$sqlv1 .= " from rawmat_subcode";
				$sqlv1 .= " where rm_code ='".$row['item_code']."'";
				$sql_resultv1 = mysql_query($sqlv1);
				$rowv1 = mysql_fetch_array($sql_resultv1);
				if ($rowv1[0] == "" or $rowv1[0] == null){ 
					$rowv1[0]  = 0.00;
				}
				$var_avgcost = $rowv1[0];
			}
            
            $sql2 = "select description from rawmat_subcode ";
            $sql2 .= " where rm_code = '".$row['item_code']."'";
            
            $tmp2 = mysql_query($sql2) or die ("Cant get desc ret : ".mysql_error());
          
            if(mysql_numrows($tmp2) >0) {

              $rst2 = mysql_fetch_object($tmp2);
              $var_desc = $rst2->description;               
             } else { $var_desc = ""; }
                      
            $sql2 = "insert into tmp_return_rawmat";
            $sql2 .= " values('".$var_loginid."', '".$row['rm_return_id']."','".$row['item_code']."','".$var_desc;
            $sql2 .= "','".$row['oum']."','".$row['totalqty']."','$var_avgcost')";
            
            //echo "<br>".$sql2;
            $tmp2 = mysql_query($sql2) or die ("Cant insert : ".mysql_error());
            
          } 
        }
        break;

     case "A" : 
      $sql = "delete from tmp_adj_rawmat where username = '$var_loginid'";
      mysql_query($sql) or die ("Cant delete adj rawmat : ".mysql_error());
      
      $sql = " select x.rm_adj_id, x.adjdate, y.item_code, y.oum, y.adjqty";
      $sql .= " from rawmat_adj x, rawmat_adj_tran y";
      $sql .= " where x.adjdate between '$frdte' and '$todte'";
      $sql .= " and  x.rm_adj_id = y.rm_adj_id";
      
      $tmp = mysql_query($sql) or die("Cant get data adj for rm : ".mysql_error());
      
      if (mysql_numrows($tmp) >0) {
      
        while ($row = mysql_fetch_array($tmp)) {
      
          $sql2 = "select sum(x.totalqty * x.myr_unit_cost) / sum(x.totalqty) as avgcost ";
          $sql2 .= " from rawmat_receive_tran x, rawmat_receive y";
          $sql2 .= " where x.item_code = '".$row['item_code']."'";
          $sql2 .= " and y.grndate <= '$todte'";
          $sql2 .= " and y.rm_receive_id = x.rm_receive_id";

          //echo "<br>".$sql2; 
          $tmp2 = mysql_query($sql2) or die ("Cant get avg adj : ".mysql_error());
          
          if(mysql_numrows($tmp2) >0) {
          
            $rst2 = mysql_fetch_object($tmp2);
            $var_avgcost = $rst2->avgcost;
            if(is_null($var_avgcost)) { $var_avgcost = 0;  }
            } else { $var_avgcost = 0; }

            if ($var_avgcost == 0){
				$sqlv1  = "select cost_price";
				$sqlv1 .= " from rawmat_subcode";
				$sqlv1 .= " where rm_code ='".$row['item_code']."'";
				$sql_resultv1 = mysql_query($sqlv1);
				$rowv1 = mysql_fetch_array($sql_resultv1);
				if ($rowv1[0] == "" or $rowv1[0] == null){ 
					$rowv1[0]  = 0.00;
				}
				$var_avgcost = $rowv1[0];
			}

            
            $sql2 = "select description from rawmat_subcode ";
            $sql2 .= " where rm_code = '".$row['item_code']."'";
            
            $tmp2 = mysql_query($sql2) or die ("Cant get desc adj : ".mysql_error());
          
            if(mysql_numrows($tmp2) >0) {

              $rst2 = mysql_fetch_object($tmp2);
              $var_desc = $rst2->description;               
             } else { $var_desc = ""; }
                      
            $sql2 = "insert into tmp_adj_rawmat";
            $sql2 .= " values('".$var_loginid."', '".$row['rm_adj_id']."','".$row['item_code']."','".$var_desc;
            $sql2 .= "','".$row['oum']."','".$row['adjqty']."','$var_avgcost')";
            
            //echo "<br>".$sql2;
            $tmp2 = mysql_query($sql2) or die ("Cant insert adj : ".mysql_error());
            
          } 
        }
        break;

     case "E" : 
      $sql = "delete from tmp_reject_rawmat where username = '$var_loginid'";
      mysql_query($sql) or die ("Cant delete reject rawmat : ".mysql_error());
      
      $sql = " select x.rm_reject_id, x.rejectdate, y.item_code, y.oum, y.totalqty";
      $sql .= " from rawmat_reject x, rawmat_reject_tran y";
      $sql .= " where x.rejectdate between '$frdte' and '$todte'";
      $sql .= " and  x.rm_reject_id = y.rm_reject_id";
      
      $tmp = mysql_query($sql) or die("Cant get data reject for rm : ".mysql_error());
      
      if (mysql_numrows($tmp) >0) {
      
        while ($row = mysql_fetch_array($tmp)) {
      
          $sql2 = "select sum(x.totalqty * x.myr_unit_cost) / sum(x.totalqty) as avgcost ";
          $sql2 .= " from rawmat_receive_tran x, rawmat_receive y";
          $sql2 .= " where x.item_code = '".$row['item_code']."'";
          $sql2 .= " and y.grndate <= '$todte'";
          $sql2 .= " and y.rm_receive_id = x.rm_receive_id";

          //echo "<br>".$sql2; 
          $tmp2 = mysql_query($sql2) or die ("Cant get avg reject : ".mysql_error());
          
          if(mysql_numrows($tmp2) >0) {
          
            $rst2 = mysql_fetch_object($tmp2);
            $var_avgcost = $rst2->avgcost;
            if(is_null($var_avgcost)) { $var_avgcost = 0;  }
            } else { $var_avgcost = 0; }
            if ($var_avgcost == 0){
				$sqlv1  = "select cost_price";
				$sqlv1 .= " from rawmat_subcode";
				$sqlv1 .= " where rm_code ='".$row['item_code']."'";
				$sql_resultv1 = mysql_query($sqlv1);
				$rowv1 = mysql_fetch_array($sql_resultv1);
				if ($rowv1[0] == "" or $rowv1[0] == null){ 
					$rowv1[0]  = 0.00;
				}
				$var_avgcost = $rowv1[0];
			}

            
            $sql2 = "select description from rawmat_subcode ";
            $sql2 .= " where rm_code = '".$row['item_code']."'";
            
            $tmp2 = mysql_query($sql2) or die ("Cant get desc reject : ".mysql_error());
          
            if(mysql_numrows($tmp2) >0) {

              $rst2 = mysql_fetch_object($tmp2);
              $var_desc = $rst2->description;               
             } else { $var_desc = ""; }
                      
            $sql2 = "insert into tmp_reject_rawmat";
            $sql2 .= " values('".$var_loginid."', '".$row['rm_reject_id']."','".$row['item_code']."','".$var_desc;
            $sql2 .= "','".$row['oum']."','".$row['totalqty']."','$var_avgcost')";
            
            //echo "<br>".$sql2;
            $tmp2 = mysql_query($sql2) or die ("Cant insert reject : ".mysql_error());
            
          } 
        }
        break;
        
       } //switch 
      //-------------- get data -------------//
      
     
		// Redirect browser
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&fd=".$frdte."&td=".$todte."&dbsel=".$varrpturldb."&usernm=".$var_loginid;
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
		alert("From Date Must Not Be Blank");
		document.InpRawOpen.rptofdte.focus();
		return false;
	}
	
	var x=document.forms["InpRawOpen"]["rptotdte"].value;
	if (x==null || x=="")
	{
		alert("To Date Must Not Be Blank");
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
		document.InpRawOpen.rptofdte.focus();
		return false;
    }
    
    
    //Check the list of opening got date from date & to date-------------------------------
	var flgchk = 1;	
	var x=document.forms["InpRawOpen"]["rptofdte"].value;
	var y=document.forms["InpRawOpen"]["rptotdte"].value;
  var strURL="aja_chk_matalldt.php?fd="+x+"&td="+y+"&t=<?php echo $var_trantype; ?>"; 

	
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
}	
</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
	<div class="contentc">
	<fieldset name="Group1" style=" width: 673px; height: 150px;" class="style2">
	 <legend class="title"><?php echo strtoupper($var_title); ?> REPORT</legend>
	  <br>
	  <form name="InpRawOpen" method="POST" onSubmit= "return chkSubmit()" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode.'&t='.$var_trantype; ?>" style="height: 134px; width: 662px;">
		<table>
	  	  <tr>
	  	    <td></td>
	  	    <td style="width: 155px" class="tdlabel">From <?php echo $var_title; ?> Date</td>
	  	    <td>:</td> 
	  	    <td style="width: 134px">
				<input class="inputtxt" name="rptofdte" id ="rptofdte" type="text" value="<?php  echo date("d-m-Y"); ?>" style="width: 100px">
				<img alt="Date Selection" src="../images/cal.gif" onclick="javascript:NewCssCal('rptofdte','ddMMyyyy')" style="cursor:pointer">
			</td>
			<td style="width: 27px"></td>
			<td style="width: 161px">To <?php echo $var_title; ?> Date</td>
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
	  	   <td style="width: 181px" colspan="8" align="center">
	  	   
	  	   <?php
            switch ($var_trantype) {
              case "R" : $locatr = "m_rm_receive.php?menucd=".$var_menucode; break;
              case "I" : $locatr = "m_rm_issue.php?menucd=".$var_menucode; break;
              case "A" : $locatr = "m_rm_adj.php?menucd=".$var_menucode; break;
              case "N" : $locatr = "m_rm_return.php?menucd=".$var_menucode; break;
              case "E" : $locatr = "m_rm_reject.php?menucd=".$var_menucode; break;
             }
             			
				echo '<input type="button" value="Back" class="butsub" style="width: 60px; height: 32px" onclick="location.href=\''.$locatr.'\'" >';
	  	   		include("../Setting/btnprint.php");
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
