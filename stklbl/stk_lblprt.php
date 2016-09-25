<?php
	include("../Setting/Configifx.php");
	include("../Setting/Connection.php");
	$var_loginid = $_SESSION['sid'];

    if($var_loginid == "") { 
         echo "<script>";   
         echo "alert('Not Log In to the system');"; 
         echo "</script>"; 

         echo "<script>";
         echo 'top.location.href = "./index.html"';
         echo "</script>";
    }else{
 		 $itemno = $_GET['i'];
 		 $refno  = $_GET['r'];
 		 $refopt = $_GET['d'];
 		 $scopy  = $_GET['cp'];
 		 
 		 $arrqty = unserialize(urldecode($_GET["lq"]));
         $arrser = unserialize(urldecode($_GET["lr"]));
    }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<style type="text/css" media="print">

table  {border: 1px solid #000}
td, tr {border: 0}

</style>
    
<script type="text/javascript">
function confirmPrint()
{
   //i = confirm("Do you want to print this Report?");		
   //if(i)
	//{
	  window.print();		  
	  setTimeout("window.close()",1000);
	//}
}	  
</script>
</head>

<body >

<!-- ########################### Start Body ############################### -->
<div id="maincontent">
     <?php

     	switch ($refopt){ 
     		case "1":
     			$sql = "select po_number, refno from rawmat_receive ";
	        	$sql .= " where rm_receive_id ='".$refno."'";
             	break;
            case "2":
     			$sql = "select po_number, refno from rawmat_receive ";
	        	$sql .= " where invno ='".$refno."'";
             	break;
			case "3":
     			$sql = "select po_number, refno from rawmat_receive ";
	        	$sql .= " where refno ='".$refno."'";
             	break; 	
            default: 
            	$sql = "";	
     	}
     	if ($sql != ""){
     		$sql_result = mysql_query($sql) or die(mysql_error());
        	$row = mysql_fetch_array($sql_result);
        	$ponum = $row[0];
        	$doref = $row[1];
        }	
     
     	$i     = 0;
     	$leftw = 3;
		$leftp = 228;
		$topw  = 18;
		$topp  = 60;
		$c     = 1;
		$r     = 1;
		
		$curdte = date("d/m/y");
     	foreach($arrqty as $row=>$lqqty)
     	{
			$lqser = $arrser[$row];				
	
			if ($c == 1){
				$varl = $leftw;
				
				//$tw   = ($r * $topp);
				//$vart = ($r * $topw) + $tw;
			}else{
				$rw   = ($c * $leftp) - $leftp;
				$varl = ($c * $leftw) + $rw;
				
				//$tw   = ($r * $topp);
				//$vart = ($r * $topw) + $tw;
			}
			
			if ($r == 1){
				$vart = 25;
				$tmp = $r;
			}else{
				if ($r != $tmp){	
					if (($r % 13) == 0){
						$vart = $vart + $topp + $topw + 50;
					}else{
						$vart = $vart + $topp + $topw;
					}	
				}
				$tmp = $r;
	 		}
			//echo $i.' '.$c.' '.$r.' '.$varl. " ".$vart."<br>";
			//echo "   "."<br>";
			//echo "   "."<br>";
   	 ?>   
			<div style="width:228px; height:60px; position:absolute; left:<?php echo $varl;?>px; top:<?php echo $vart;?>px;">
				<table style="width:228px; height:73px;" cellpadding="0" cellspacing="0">
					<tr>
						<td style="width: 228px;text-align :left">&nbsp;&nbsp;<strong>ID : </strong><?php echo $itemno." Q".number_format($lqqty, "2",".", ""); ?></td>
					</tr>
					<tr>
						<td style="width: 228px">&nbsp;&nbsp;<strong>Rem :</strong>S:<?php echo $lqser."/".$scopy. " DO:".$doref;?></td>
					</tr>
					<tr>
						<td style="width: 228px">&nbsp;&nbsp;D:<?php echo $curdte. " PO:".$ponum;?></td>
					</tr>
			   </table>
			</div>
	<?php
			$i = $i + 1;
			if (($i % 3) == 0){
				$c = 1;
				$r = $r + 1;
			}else{
				$c = $c + 1;
			}
		}		
	?>
</div>

<div id="footer"></div>
<!-- ############################# End Body ######################## -->
</body>
<script type="text/javascript" language="JavaScript1.2">confirmPrint()</script>
</html>
