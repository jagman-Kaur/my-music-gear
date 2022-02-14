<?php

class Payment {
	private $conn = NULL;
	private $inventory = array();
	private $shoppingCart = array();
	function __construct() {
		include("inc_dbconnect.php");
		$this->conn = $conn;
	}
	
	public function isAlpha($name){
		if(ctype_alpha($name))
			return TRUE;
		else
			return FALSE;
	}
	public function checkCardLength($card){
		if(strlen($card) != 16)
			return FALSE;
		else
			return TRUE;
	}
	public function isNumeric($input){
		if(is_numeric($input))
			return TRUE;
		else
			return FALSE;
	}
	public function checkCVVLength($cvv){
		if(strlen($cvv) != 3)
			return FALSE;
		else
			return TRUE;
	}
	public function checkMonthYearLength($input){
		if(strlen($input) != 2)
			return FALSE;
		else
			return TRUE;
	}
	public function isMonthValid($month){
		if($month >0 && $month < 13)
			return TRUE;
		else
			return FALSE;
	}
	public function makePayment($itemId, $total){
		$sql = "UPDATE product SET status='Available' WHERE uniqueId=" . $itemId;
		$qRes = @$this->conn->query($sql);
		
		if($qRes != FALSE){
			$sql1 = "DELETE from rentedProduct WHERE itemId=" . $itemId;
			$qRes1 = @$this->conn->query($sql1);
			$sql2 = "UPDATE invoice SET amountPaid =" . $total . "AND subtotal= subtotal - " . $total;
			$qRes2 = @$this->conn->query($sql2);
			if($qRes1 != FALSE && $qRes != FALSE)
				return TRUE;
		}
		$errorMsg = "Error code: " . @$this->conn->errno . ": " . @$this->conn->error;
		echo $errorMsg;
		return FALSE;
	}
	function __wakeup() {
		include("inc_dbconnect.php");
		$this->conn = $conn;
	}

	function __destruct() {
		if (!$this->conn->connect_error)
			@$this->conn->close();
	}
}
?>
