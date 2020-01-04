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
	
// include database and object files
include_once '../Setting/database.php';
include_once '../objects/ticket_qc_report.php';

//session_start();

//session_destroy();

if (isset($_POST['submit'])) {

	$database = new Database();
	$db = $database->getConnection();
	$report = new TicketQCReport($db);
	
	$report->dateFrom = $_POST['dateFrom'];
	$report->dateTo = $_POST['dateTo'];
	$report->buyerFrom = $_POST['buyerFrom'];
	$report->buyerTo = $_POST['buyerTo'];
	
	//$_SESSION['TICKET_QC_REPORT_SESSION'] = $report;
	
	$data = $report->generateReport();
		
	//$_SESSION['TICKET_QC_REPORT_ROW'] = $data;	
}
else { 
	//$report = $_SESSION['TICKET_QC_REPORT_SESSION'];
	//$data = $_SESSION['TICKET_QC_REPORT_ROW'];
}

//echo json_encode($_SESSION['TICKET_QC_REPORT_SESSION']);


if (empty($report)) {
	// initialize object
	$database = new Database();
	$db = $database->getConnection();
	$report = new TicketQCReport($db);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<title>QC vs DO checking report</title>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="../css/styles.css" />

<style>
.details
{
	position:absolute; 
	top:150px; 
	left:3%;
}

</style>	
	
<script type="text/javascript">
$(function()
{
	var dates = $( "#dateFrom, #dateTo" ).datepicker({					
		defaultDate: "+1w",
		firstDay: 1,
		dateFormat: 'dd-mm-yy',
		changeMonth: true,
		changeYear: true,
		showOn: "both",
		buttonImage: "../images/cal.gif",
		buttonImageOnly: true,
		showOtherMonths: true,
		selectOtherMonths: true,
		showButtonPanel: true,
		showWeek: true,
		onSelect: function( selectedDate ) {
		var option = this.id == "dateFrom" ? "minDate" : "maxDate",
			instance = $( this ).data( "datepicker" ),
			date = $.datepicker.parseDate(
				instance.settings.dateFormat ||
				$.datepicker._defaults.dateFormat,
				selectedDate, instance.settings );
		dates.not( this ).datepicker( "option", option, date );
	}
	});
	$("#dateFrom, #dateTo").attr("size", "10");
});
</script>

</head>

<body onload="setup()">
<?php include("../topbarm.php"); ?> 
<div class="details">
<form name="form1" method="post" action="" id="form1">
	<table class="lamp">
		<tr>
			<td width="20%">Production Date</td>
			<td>
				From <input id="dateFrom" type="text" name="dateFrom" value='<?php echo $report->dateFrom ?>'>
				To <input id="dateTo" type="text" name="dateTo" value='<?php echo $report->dateTo ?>' >
			</td>
		</tr>
		<tr>
			<td width="20%">Buyer</td>
			<td>
				From <select name="buyerFrom" id ="buyerFrom" style="width: 240px">
			   		<?php
						if ($report->buyers != "") {
							foreach($report->buyers as $value => $row){
								$selected = "";
								if ($report->buyerFrom == $row['customer_code']) {
									$selected = "selected";
								}
								echo '<option '. $selected .' value="'.$row['customer_code'].'">'.$row['customer_code']." | ".$row['customer_desc'].'</option>';
							}
						}
	            	?>				   
				</select>
				To <select name="buyerTo" id ="buyerTo" style="width: 240px">
			   		<?php
						if ($report->buyers != "") {
							foreach($report->buyers as $value => $row){
								$selected = "";
								if ($report->buyerTo == $row['customer_code']) {
									$selected = "selected";
								}
								echo '<option '. $selected .' value="'.$row['customer_code'].'">'.$row['customer_code']." | ".$row['customer_desc'].'</option>';
							}
						}
	            	?>				   
				</select>
			</td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="submit" value="Generate Report Now"></td>
		</tr>
	</table>
</form>
</div>


<div class="report" style="padding:10px;">
<table class="noborder">

</table>
<div class="autoscroll">
<table id="dataTable" class="reference">
	<thead>
		<tr>
			<th>Ticket No</th>
			<th>Product No</th>
			<th>Product Qty</th>
			<th>Defect</th>
			<th>Ok Qty</th>
			<th>Missing</th>
			<th>Difference</th>
		</tr>
	</thead>
	<tbody>	
	<?php
		if ($data != "") {
			foreach($data as $value => $item){
				echo "<tr>";
				echo "<td>".$item['ticketNo']."</td>";
				echo "<td>".$item['productNo']."</td>";
				echo "<td align='center'>".$item['productQty']."</td>";
				echo "<td align='center'>".$item['defectQty']."</td>";
				echo "<td align='center'>".$item['okQty']."</td>";
				echo "<td align='center'>".$item['missingQty']."</td>";
				echo "<td align='center'>".$item['diffQty']."</td>";
				echo "</tr>";
			}
		}
	?>
	</tbody>
</table>
</div>
</div>
</body>
</html>