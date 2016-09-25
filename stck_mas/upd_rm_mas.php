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
      $var_rm_cd = $_GET['rawmatcd'];
      $var_menucode = $_GET['menucd'];
    }
    
     if ($_POST['Submit'] == "Update") {
       $var_rm_cd = mysql_real_escape_string($_POST['rawmatcd']);
       $var_menucode  = $_POST['menudcode'];
       
       if ($var_rm_cd <> "") {
         $rawmatcd      = mysql_real_escape_string($_POST['rawmatcd']);
         $category      = mysql_real_escape_string($_POST['category']);
         $uom           = mysql_real_escape_string($_POST['seluom']);
         $currency_code = mysql_real_escape_string($_POST['selcurr']);
         $description   = htmlentities(mysql_real_escape_string($_POST['description']));
         $unit_price    = $_POST['unit_price'];
         $remark        = htmlentities(mysql_real_escape_string($_POST['remark']));
         $content       = htmlentities(mysql_real_escape_string($_POST['content']));
         $active_flag   = $_POST['selactive'];
         //$itm_grp_cd    = $_POST['selitm_grp_cd'];
          $itm_grp_cd    = '';
         
         $suppmoby= $var_loginid;
         $suppmoon= date("Y-m-d H:i:s");  
      
      
 	     if ($active_flag == 'DEACTIVATE'){
    	 	$myquery = "select count(main_code) from rawmat_subcode where main_code = '".$var_rm_cd. "'";
    	 	
    	 	$result = mysql_query($myquery);
		    $row = mysql_fetch_row($result);
		    $cnt = $row[0];

	        if ($cnt == '1'){ //no sub code using this main code//
	        	$stat = '0'; //got sub code using this main code//
	        }else{
	        	$stat = '1';
	        }
      	 }else{ //"active flag//
      	 	$stat = '1';
      	 }      	 

         if ($stat == '1' || $stat =='0'){  //open upon request from FNL-Jega
         	$sql = "Update rawmat_master set ";
         	$sql .= " active_flag = '$active_flag', itm_grp_cd = '$itm_grp_cd',";
         	$sql .= " uom='$uom', description ='$description ',";
         	$sql .= " remark = '$remark', content = '$content', currency_code = '$currency_code',";
         	$sql .= " modified_by='$suppmoby',";
         	$sql .= " modified_on='$suppmoon' WHERE rm_code = '$var_rm_cd'";
         	mysql_query($sql) or die("Query 1:".mysql_error());
         	         

        }else{
         	//alert("Cannot Update Status - Have Sub Code Using This Main Code");
         	echo "<script>";
		    echo "alert('Cannot Update Status To DEACTIVATE - Have Sub Code Using This Main Code')";
		    echo "</script>";
        }
         
        $var_menucode  = $_POST['menudcode'];
        $backloc = "../stck_mas/rawmat_mas.php?menucd=".$var_menucode;
        echo "<script>";
        echo 'location.replace("'.$backloc.'")';
        echo "</script>";   
      }      
    }

    if ($_POST['Submit'] == "Back") {
         $var_menucode  = $_POST['menudcode'];
         $backloc = "../stck_mas/rawmat_mas.php?menucd=".$var_menucode;
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
@import "../css/styles.css";
</style>
<script type="text/javascript" language="javascript" src="../media/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="../media/js/jquery.dataTables.js"></script>

<script type="text/javascript" charset="utf-8"> 
function upperCase(x)
{
var y=document.getElementById(x).value;
document.getElementById(x).value=y.toUpperCase();
}

</script>
</head>
<body OnLoad="document.UpdSuppMas.selactive.focus();">
  <?php include("../topbarm.php"); ?> 
 <!-- <?php include("../sidebarm.php"); ?> -->

  <div class="contentc">

	<fieldset name="Group1" style=" width: 850px; height: 350px;">
	 <legend class="title">EDIT RAW MATERIAL MAIN CODE DETAIL</legend>
	  <?php
	    $sql = " select active_flag, currency_code, cat_desc, ";
        $sql .= " uom, description, itm_grp_cd, content, ";
        $sql .= " remark, ";
        $sql .= " rawmat_master.create_by, rawmat_master.creation_time, category";
        $sql .= " from rawmat_master, cat_master";
        $sql .= " where rm_code ='".$var_rm_cd."' and category = cat_code";
        $sql_result = mysql_query($sql);
        //echo $sql;
        $row = mysql_fetch_array($sql_result);

        $active_flag = $row[0];
        $currency_code = $row[1];
        $category = $row[2];
        $uom  = $row[3];
        $description  = $row[4];
        $itm_grp_cd = $row[5];
        $content = $row[6];
        $remark = $row[7];
        $create_by = $row[8];
        $creation_time = $row[9];
        $cat_code = $row[10];
        $mark = $row[11];
        $cut = $row[12];
        $spread = $row[13];
        $bundle = $row[14];

     
        $sql = "select uom_desc from uom_master  ";
        $sql .= " where uom_code ='".$uom."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $uom_desc = $row[0];
        
        $sql = "select currency_desc from currency_master ";
        $sql .= " where currency_code ='".$currency_code."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $currency_desc = $row[0];

        $sql = "select itm_grp_desc from item_group_master";
        $sql .= " where itm_grp_cd  ='".$itm_grp_cd."'";
        $sql_result = mysql_query($sql);
        $row = mysql_fetch_array($sql_result);
        $itm_grp_desc = $row[0];

	    ?>		
	  <br>
	  <form name="UpdSuppMas" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	    <input name="menudcode" type="hidden" value="<?php echo $var_menucode;?>">
		<table>
	  	  <tr>
	  	    <td></td>
	  	    <td>Raw Mat Code</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="rawmatcd" id ="rawmatcdid" readonly="readonly" type="text" style="width: 161px" value="<?php echo $var_rm_cd; ?>" unselectable="on">
			</td>
			<td></td>
		    <td>Status</td>
	  	    <td>:</td>
	  	    <td>
			   <select name="selactive" style="width: 125px" >
			    <option><?php echo $active_flag;?></option>
			    <option>ACTIVE</option>
			    <option>DEACTIVATE</option>
			   </select>
			</td>
	  	  </tr>
	  	  <tr>
	  	    <td></td> 
	  	    <td></td>
	  	    <td></td> 
            <td><div id="msgcd"></div></td>
	   	  </tr> 
	   	   <tr>
	   	    <td></td>
	  	    <td>Category</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="category" id ="rawmatcdid0" readonly="readonly" type="text" style="width: 161px" value="<?php echo $category; ?>" hidefocus="hidefocus" unselectable="on">
			</td>
			<td></td>
		    <td>Currency Code</td>
	  	    <td>:</td>
	  	    <td>
			   <select name="selcurr" style="width: 68px">
			    <?php
                   $sql = "select currency_code, currency_desc from currency_master ORDER BY currency_code ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected value = '$currency_code'>$currency_code</option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['currency_code'].'">'.$row['currency_code'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select>
			</td>
	  	  </tr>
	  	  <tr>
	  	   <td></td>
	  	  </tr>
		  <tr>
	  	    <td></td>
	  	    <td>UOM</td>
	  	    <td>:</td>
	  	    <td>
		      <select name="seluom" style="width: 68px">
			    <?php
                   $sql = "select uom_code, uom_desc from uom_master ORDER BY uom_code ASC";
                   $sql_result = mysql_query($sql);
                   echo "<option size =30 selected value = '$uom'>$uom_desc</option>";
                       
				   if(mysql_num_rows($sql_result)) 
				   {
				   	 while($row = mysql_fetch_assoc($sql_result)) 
				     { 
					  echo '<option value="'.$row['uom_code'].'">'.$row['uom_desc'].'</option>';
				 	 } 
				   } 
	            ?>				   
			  </select>
	  	    </td>
			<td></td>
		  </tr>
		  <tr>
		    <td></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td>Description</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="description" id ="descriptionid" type="text" maxlength="200" style="width: 294px" value="<?php echo $description; ?>">
			</td>
			<td></td>
		    <td></td>
	  	    <td></td>
	  	    <td></td>
		  </tr>
		  <tr>
		   <td></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td>Content</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="content" id ="contentid" type="text" maxlength="50" style="width: 294px" value="<?php echo $content; ?>">
			</td>
			<td></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
            <td>
			   &nbsp;</td>
		  </tr>
		  <tr>
		    <td></td>
		  </tr>
		  <tr>
	  	    <td></td>
	  	    <td>Remark</td>
	  	    <td>:</td>
	  	    <td>
			<input class="inputtxt" name="remark" id ="remarkid" type="text" maxlength="50" style="width: 345px" value="<?php echo $remark; ?>">
			</td>
			<td></td>
		  </tr>
           <tr>
            <td align="center" colspan="8">
	  	    <input type=submit name = "Submit" value="Back" class="butsub" style="width: 60px; height: 32px">
	  	    <input type=submit name = "Submit" value="Update" class="butsub" style="width: 60px; height: 32px" >
	  	   </td>
	  	  </tr>
	  	</table>
	   </form>	
	   </fieldset>
	       </fieldset>
	 </div>      
</body>
</html>
