<?php
class TicketQCReport{
	private $conn;
	
	// object properties
	public $dateFrom;
	public $dateTo;
	public $buyerFrom;
	public $buyerTo;
	public $buyers;
	
	public function __construct($db) {
		$this->conn = $db;
		$this->dateFrom = date('d-m-Y');
		$this->dateTo = date('d-m-Y');
		$this->buyers = $this->getBuyers();
    }
	
	function getBuyers() {
		$query = "SELECT customer_code, customer_desc FROM customer_master ORDER BY customer_code";
		
		// prepare query statement
		$stmt = $this->conn->prepare($query);
	 
		// execute query
		$stmt->execute();
	 
		$num = $stmt->rowCount();
        if($num>0){
			$messages_arr=array();
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);

				$message_item=array(
					"customer_code" => $customer_code,
					"customer_desc" => $customer_desc,
				);
		
				array_push($messages_arr, $message_item);				
            }
        }
		return $messages_arr;
	}
	
	function generateReport(){
 
		$dateFrom = date('Y-m-d', strtotime($this->dateFrom));
		$dateTo = date('Y-m-d', strtotime($this->dateTo));
		$buyerFrom = $this->buyerFrom;
		$buyerTo = $this->buyerTo;
		
		// select query
		$query = "SELECT e.sew_doid, e.ticketno, e.productcode, e.issueqty, d.dodate, q.okqty, q.defectqty, q.missingqty, q.qcdate
					FROM sew_do_tran e
					INNER JOIN sew_do d ON d.sew_doid = e.sew_doid
					INNER JOIN sew_qc q ON q.ticketno = e.ticketno
					WHERE d.dodate between '$dateFrom' and '$dateTo'
					AND d.buyer between '$buyerFrom' and '$buyerTo'";
		
		// prepare query statement
		$stmt = $this->conn->prepare($query);
		
		// execute query
		$stmt->execute();
	 
		$num = $stmt->rowCount();
        if($num>0){
			$messages_arr=array();
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($row);
				
				$dqty = empty($defectqty)?0:$defectqty;
				$okqty = empty($okqty)?0:$okqty;
				$mqty = empty($missingqty)?0:$missingqty;
				
				$message_item=array(
					"ticketNo" => $ticketno,
					"productNo" => $productcode,
					"productQty" => $issueqty,
					"defectQty" => $dqty,
					"okQty" => $okqty,
					"missingQty" => $mqty,
					"diffQty" => ($issueqty - $dqty - $okqty),
					"doDate" => $dodate,
					"qcDate" => $qcdate,
					"doNumber" => $sew_doid
				);
		
				array_push($messages_arr, $message_item);				
            }
        }
		return $messages_arr;
	}
}
?>