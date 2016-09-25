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
         $fbat = $_POST['frbat'];
     	 $tbat = $_POST['tobat'];
     	 $selc = $_POST['selection'];
		   
     	 $prnpath = "sew_reportrpt.php?f=".$fbat."&t=".$tbat."&sel=".$selc;
     	 echo "<script>";
         echo "window.open('".$prnpath."',window,'resizable=yes,scrollbars=yes,width=950,height=700');";
    	 echo "if (msgWindow.opener == null) {msgWindow.opener = self;}";
		 echo "</script>";
        	
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

	document.InpRawOpen.frbat.focus();
								
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

function getSupp(sel)
{
   
   var strURL="aja_get_sel.php?sel="+sel;
   //alert(strURL);

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
	    document.getElementById('statedivx0').innerHTML=req.responseText;
	 } else {
   	   alert("There was a problem while using XMLHTTP:\n" + req.statusText);
	 }
       }
      }
   req.open("GET", strURL, true);
   req.send(null);
   }
}


</script>
</head>

 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
	<div class="contentc">
	<fieldset name="Group1" style=" width: 758px; height: 205px;" class="style2">
	 <legend class="title">SEWING GARMENT LISTING</legend>
	  <br>
	  <form name="InpRawOpen" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 139px; width: 796px;">
		<table style="width: 807px; height: 102px;">
	  	    <tr>
	  	    <td style="width: 6px"></td>
	  	    <td style="width: 172px" class="tdlabel">Select By</td>
	  	    <td style="width: 19px">:</td> 
	  	    <td style="width: 266px">
	  	    	<select name="selection" id="selection" style="width: 226px" onchange="getSupp(this.value)">
				  <option value="0">--Please Select--</option>
				  <option value="1">Worker ID</option>
				  <option value="2">Ticket No.</option>
				  <option value="3">Product Code</option>
				 </select>
  			</td>
			<td></td>
			<td style="width: 159px" class="tdlabel">&nbsp;</td>
			<td style="width: 19px">&nbsp;</td> 
			 <td style="width: 304px">
				&nbsp;</td>

		    </tr>
	  	  <tr>
	  	    <td style="width: 6px"></td>
	  	    <td colspan="7">
			<p id="statedivx0" style="width: 779px; height: 314px"></p></td>

		  </tr>
	  	  <tr>
	  	    <td style="width: 6px"></td> 
	  	    <td colspan="3">&nbsp;</td>
	   	  </tr> 
	   	
	  	  <tr>
	  	   <td colspan="8" align="center">
	  	   <?php
	  	   		//include("../Setting/btnprint.php");
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
