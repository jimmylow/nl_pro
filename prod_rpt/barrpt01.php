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
         $fbat = $_POST['frbar'];
     	 $tbat = $_POST['tobar'];
		   
     	$fname = "sew_barentry3.rptdesign&__title=myReport"; 
        $dest = "http://".$var_prtserver.":8080/birt-viewer/frameset?__report=".$fname."&ftic=".$fbat."&ttic=".$tbat."&menuc=".$var_menucode."&dbsel=".$varrpturldb;
        $dest .= urlencode(realpath($fname));
        
        //header("Location: $dest" );
        echo "<script language=\"javascript\">window.open('".$dest."','name','height=1000,width=1000,left=200,top=200');</script>";
        $backloc = "../prod_rpt/barrpt01.php?menucd=".$var_menucode;
       	echo "<script>";
       	echo 'location.replace("'.$backloc.'")';
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

	document.InpRawOpen.frbar.focus();
								
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

</script>
</head>
<?php
	 $fromDate = date("Y-m-d", strtotime("-6 months")); 

?>
 <!--<?php include("../sidebarm.php"); ?>--> 
<body onload="setup()">
   <?php include("../topbarm.php"); ?> 
	<div class="contentc">
	<fieldset name="Group1" style=" width: 758px; height: 205px;" class="style2">
	 <legend class="title">Barcode From Ticket To Ticket</legend>
	  <br>
	  <form name="InpRawOpen" method="POST" action="<?php echo $_SERVER['PHP_SELF'].'?menucd='.$var_menucode; ?>" style="height: 139px; width: 796px;">
		<table style="width: 807px; height: 102px;">
	  	  <tr>
	  	    <td style="width: 6px"></td>
	  	    <td style="width: 172px" class="tdlabel">From Ticket No</td>
	  	    <td style="width: 19px">:</td> 
	  	    <td style="width: 274px">
				<select name="frbar" id="frbar" style="width: 226px" >
			 	<?php
             		$sql = "select distinct ticketno from sew_entry Where stat = 'ACTIVE' and creation_time >= '$fromDate' ORDER BY ticketno ";
              		$sql_result = mysql_query($sql);
                       
			  		if(mysql_num_rows($sql_result)) 
			  		{
			  			while($row = mysql_fetch_assoc($sql_result)) 
			   			{ 
			   				$bat = htmlentities($row['ticketno']);
							echo '<option value="'.$bat.'">'.$bat.'</option>';
			   			} 
		      		} 
	         	?>				   
	       		</select>
			</td>
			<td></td>
			<td style="width: 159px" class="tdlabel">To Ticket No</td>
			<td style="width: 19px">:</td> 
			 <td style="width: 304px">
				<select name="tobar" id="tobar" style="width: 226px" >
			 	<?php
             		$sql = "select distinct ticketno from sew_entry Where stat = 'ACTIVE'  and creation_time >= '$fromDate' ORDER BY ticketno ";
              		$sql_result = mysql_query($sql);
                       
			  		if(mysql_num_rows($sql_result)) 
			  		{
			  			while($row = mysql_fetch_assoc($sql_result)) 
			   			{ 
			   				$bat = htmlentities($row['ticketno']);
							echo '<option value="'.$bat.'">'.$bat.'</option>';
			   			} 
		      		} 
	         	?>				   
	       		</select>

			</td>

		  </tr>
	  	  <tr>
	  	    <td style="width: 6px"></td> 
	  	    <td style="width: 172px" class="tdlabel">&nbsp;</td>
	  	    <td style="width: 19px"></td> 
            <td style="width: 274px"></td> 
	   	  </tr> 
	   	
	  	  <tr>
	  	   <td colspan="8" align="center">
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
