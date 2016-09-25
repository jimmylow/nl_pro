<?php

    include("../Setting/Configifx.php");
	
	$db_link  = mysql_connect($var_server, $var_userid, $var_password)or die("cannot connect");
   	mysql_query("SET character_set_results=utf8", $db_link) or die(mysql_error());
	mb_language('uni');
	mb_internal_encoding('UTF-8');
	mysql_select_db("$var_db_name", $db_link);
	mysql_query("set names 'utf8'",$db_link);
	
	 if(!isset($_GET['q']) || !$method = $_GET['q']) exit; 
	$q=$_GET['q'];

$sql="SELECT remark, category, content, uom, description, itm_grp_cd FROM rawmat_master WHERE rm_code = '".$q."'";

$result = mysql_query($sql);

echo "<table border='0'>
<tr>
<td style='width: 100px;' class='auto-style1'><strong>Details :- </strong></td>
</tr>";

while($row = mysql_fetch_array($result))
  {
  echo "<tr>";
  echo "<td style='width: 100px;' class='tdlabel'>Category</td><td>:</td><td style='width: 100px;'>" . $row['category'] . "</td>";
  echo "<td style='width: 100px;' class='tdlabel'>Stock Group</td><td>:</td><td style='width: 100px;'>" . $row['itm_grp_cd'] . "</td>";
  echo "</tr>";
  echo "<tr>";
  echo "<td style='width: 100px;' class='tdlabel'>Description</td><td>:</td><td style='width: 1000px;'>" . $row['description'] . "</td>";
  echo "</tr>";
  echo "<td style='width: 100px;' class='tdlabel'>Content</td><td>:</td><td style='width: 20px;'>" . $row['content'] . "</td>";
  echo "<td style='width: 100px;' class='tdlabel'>UOM</td><td>:</td><td style='width: 100px;'>" . $row['uom'] . "</td>";
  echo "<tr>";
  echo "<td style='width: 100px;' class='tdlabel'>Remark</td><td>:</td><td style='width: 1000px;'>" . $row['remark'] . "</td>";
  echo "</tr>";


  echo "</tr>";
  }
echo "</table>";

?> 