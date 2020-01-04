<?php
// include database and object files
include_once '../Setting/database.php';
include_once '../objects/ticket_qc_report.php';
 
if (isset($_POST['submit'])) {

	session_start();

	$database = new Database();
	$db = $database->getConnection();
	$report = new TicketQCReport($db);
	
	$report->dateFrom = $_POST['dateFrom'];
	$report->dateTo = $_POST['dateTo'];
	$report->buyerFrom = $_POST['buyerFrom'];
	$report->buyerTo = $_POST['buyerTo'];
	
	$_SESSION['TICKET_QC_REPORT_SESSION'] = $report;	
	
	$reportRow = new TicketQCReportRow($db, $report);
	$data = $reportRow->generateReport();
		
	$_SESSION['TICKET_QC_REPORT_ROW'] = $data;
	
	header('Location: ticket_qc_report.php');
}
?>