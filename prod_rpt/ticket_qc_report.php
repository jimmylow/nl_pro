<?php
	$title = "Sewig DO VS QC checking report";

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
<title><?php echo $title; ?></title>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />

<style media="all" type="text/css">
@import "../js/jquery/css/smoothness/jquery-ui-1.9.0.custom.css";
@import "../css/styles.css";
@import "../css/demo_table.css";
thead th input { width: 90% }

.style2 {
	margin-right: 0px;
}

.details
{
	position:absolute; 
	top:150px; 
	width: 95%;
	padding: 10px;
}
</style>

<script type="text/javascript" src="../js/jquery/js/jquery-1.8.2.js"></script>
<script type="text/javascript" src="../js/jquery/js/jquery-ui-1.9.0.custom.min.js"></script>
<script type="text/javascript" src="../js/jquery.floatThead.min.js"></script>
<script type="text/javascript" src="../js/jszip.min.js"></script>
<script type="text/javascript" src="../js/kendo/2015.3.930/js/kendo.all.min.js"></script>

<script type="text/javascript" charset="utf-8"> 
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
	
	var $table = $('#dataTable');
	$table.floatThead();

});
</script>

</head>

<body>
<?php include("../topbarm.php"); ?> 
<div class="details">

<div>
<form name="form1" method="post" action="" id="form1">
	<table class="lamp">
		<tr>
			<td width="20%">DO Date</td>
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

<div style="padding-top:10px">

<table class="noborder">
	<tr>
		<td align="right">
		<?php
			$title2 = "Date From " .$report->dateFrom. " To " .$report->dateTo;
			$title3 = "Buyer From " .$report->buyerFrom. " To " .$report->buyerTo;
			$generatedOn = new DateTime("now");
			include("../utils/inc_printable_page.php"); 
			include("../utils/inc_export_table.php"); 
		?> 
		</td>
	</tr>
</table>

<table id="dataTable" class="reference">
	<thead>
		<tr>
			<th>Ticket No</th>
			<th>Product No</th>
			<th>DO Number</th>
			<th>DO Date</th>
			<th>QC Date</th>
			<th>DO Qty</th>
			<th>Defect</th>
			<th>Ok Qty</th>
			<th>Missing</th>
			<th>Difference</th>
		</tr>
	</thead>
	<tbody>	
	<?php
		$totalProductQty = 0;
		$totalDefectQty = 0;
		$totalOkQty = 0;
		$totalMissingQty = 0;
		$totalDiffQty = 0;
		if ($data != "") {
			foreach($data as $value => $item){
				$totalProductQty += $item['productQty'];
				$totalDefectQty += $item['defectQty'];
				$totalOkQty += $item['okQty'];
				$totalMissingQty += $item['missingQty'];
				$totalDiffQty += $item['diffQty'];
				
				echo "<tr>";
				echo "<td>".$item['ticketNo']."</td>";
				echo "<td>".$item['productNo']."</td>";
				echo "<td>".$item['doNumber']."</td>";
				echo "<td>".$item['doDate']."</td>";
				echo "<td>".$item['qcDate']."</td>";
				echo "<td align='center'>".$item['productQty']."</td>";
				echo "<td align='center'>".$item['defectQty']."</td>";
				echo "<td align='center'>".$item['okQty']."</td>";
				echo "<td align='center'>".$item['missingQty']."</td>";
				echo "<td align='center'>".$item['diffQty']."</td>";
				echo "</tr>";
			}
		}
	?>
		<tr class="summary">
			<td colspan="5">Total</td>
			<td align="center"><?php echo $totalProductQty ?></td>
			<td align="center"><?php echo $totalDefectQty ?></td>
			<td align="center"><?php echo $totalOkQty ?></td>
			<td align="center"><?php echo $totalMissingQty ?></td>
			<td align="center"><?php echo $totalDiffQty ?></td>
		</tr>
	</tbody>
</table>
</div>
</div>
</body>
</html>