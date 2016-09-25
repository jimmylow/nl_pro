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
      $varitm= $_GET['rawmatcd'];
    }
	
	if ($_POST['Submit'] == "Delete") {
		 $itm = stripslashes(mysql_real_escape_string($_POST['itm']));
		if ($itm <> "") {
			$sql  = "select itemno, photo from tblphoto "; 
			$sql .= " where itemno = '$itm' and typ = 'R'";
			$sql_result = mysql_query($sql);
			$row = mysql_fetch_array($sql_result);
			$itemno = $row[0];
			$fname = $row[1];
			
			if ($itemno <> ""){
				if ($fname <> ""){
					#-------------Delete First --------------------------------
					$filename = "../stck_mas/itmpic/". $fname;
					unlink($filename);
					#----------------------------------------------------------
				}
			
				$sql  = "Delete From tblphoto "; 
				$sql .= " where itemno = '$itm' and typ = 'R'";
				mysql_query($sql) or die("Query 1 :".mysql_error()); 
				
				$backloc =  'upd_rmpic_sub.php?rawmatcd='.htmlentities($itm);
				echo "<script>";
				echo "alert('Item Code Picture Have Been Deleted.');";
        			echo 'location.replace("'.$backloc.'");';
        			echo "</script>";
			}else{
				echo "<script>";   
				echo "alert('Sorry, No Item Picture To Delete For This Item No.');"; 
				echo "</script>";

				$backloc =  'upd_rmpic_sub.php?rawmatcd='.htmlentities($itm);
				echo "<script>";
        			echo 'location.replace("'.$backloc.'")';
        			echo "</script>";

			}	
		}
	}
	
	if ($_POST['Submit'] == "Apply") {
		$itm = stripslashes(mysql_real_escape_string($_POST['itm']));
		$dir  = '../stck_mas/itmpic';
		#----------------------Exe Picture Product Code-----------------------------------------------
		if ($_FILES['uploadfile']['name'] <> "") {
			$ext1 = end(explode('.', $_FILES['uploadfile']['name']));
			$sql  = "select itemno, photo from tblphoto "; 
      	    $sql .= " where itemno = '$itm' and typ = 'R'";
     		$sql_result = mysql_query($sql) or die(mysql_error());
              $row = mysql_fetch_array($sql_result);
              $pfname = $row[0];
              $ffname = $row[1];
			 
			$imgnm = $itm;
			$imgnms = $itm.'.'.$ext1; 

			 if ($pfname == "" or empty($pfname)){
			 
				include("../Setting/uploadFuc.php");
				$sql = "INSERT INTO tblphoto values 
      	            ('$itm', '$imgnms', 'R')";
				mysql_query($sql) or die("Query 1 :".mysql_error()); 
				
				echo "<script>";   
				echo "alert('Item Code Picture Have Been Uploaded.');"; 
				echo "</script>";
			
			}else{
				#-------------Delete First --------------------------------
				if (!empty($ffname)){ 
					$filename = $dir .'/'.$ffname;
					unlink($filename);
				
					$sql  = "Delete From tblphoto "; 
      	         	 	$sql .= " where itemno = '$itm' and typ = 'R'";
					mysql_query($sql) or die("Query 1 :".mysql_error());
				}	 
				#----------------------------------------------------------
				
				include("../Setting/uploadFuc.php");
				$sql = "INSERT INTO tblphoto values 
      	            ('$itm', '$imagename', 'R')";
				mysql_query($sql) or die("Query 1 :".mysql_error()); 
			}	
	    }else{
			echo "<script>";   
			echo "alert('Please Select Picture To Upload/Replace.');"; 
			echo "</script>";
		}
		echo "<script>";
         echo 'window.close()';
         echo "</script>";
         #---------------------------------------------------------------------------------------------  
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<title>Manage Item Picture</title>

<style media="all" type="text/css">
@import "../css/styles.css";
@import "../css/demo_table.css";
thead th input { width: 90% }

</style>


<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" charset="utf-8">
function readURL(input) {

	if (input.files && input.files[0]) {
       var reader = new FileReader();

       reader.onload = function (e) {
       $('#proimgpre')
          .attr('src', e.target.result)
          .width(346)
          .height(210);
       };
       reader.readAsDataURL(input.files[0]);
       
    }
}


</script>
</head>
<?php
	$var_sql = "select photo from tblphoto";
    $var_sql .= " where typ = 'R'";
    $var_sql .= " and itemno ='$varitm'";
    $que_photo = mysql_query($var_sql) or die (mysql_error());
    $res_photo = mysql_fetch_object($que_photo);

    $var_photo = $res_photo->photo;
	$picsour = "../stck_mas/itmpic/".$var_photo; 
?>
<body>
	<form name="PicMana" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
		<input type="hidden" name="itm" id="itm" value="<?php echo htmlentities($varitm); ?>">
		<table height="100%" width="100%">
			<tr>
				<td width = "40%" rowspan = '4'>
					<img src = "<?php echo $picsour; ?>" border="0" id="proimgpre" width="346px" height="210px">
				</td>
				<td align="center">
					<table height="100%" width="100%">
					<tr>
						<td align="center">
							<span style="font-weight:bold;">Item Code :<?php echo $varitm; ?></span>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td height="30%">
						<span class="title3">Select/Replace Picture :</span>
						<input name="uploadfile" style="width: 253px" class="butsub" type="file" onchange="readURL(this);">
						</td>
					</tr>
					<tr>
						<td>
							<input name="Submit" type="submit" value="Apply"class="butsub" style="width: 60px; height: 32px">
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td height="30%">
						<span class="title3">Delete Current Item Picture :</span>
						<input name="Submit" type="submit" value="Delete" class="butsub" style="width: 60px; height: 32px" onClick="return confirm('Are You Sure Delete This Item Picture??');">
						</td>
					</tr>
					</table>	
			       </td>
			</tr>
		</table>
	</form>
</body>
</html>