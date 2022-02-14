<?php

class MusicStoreForAdmin {
	private $conn = NULL;
	private $inventory = array();
	private $shoppingCart = array();
	function __construct() {
		include("inc_dbconnect.php");
		$this->conn = $conn;
	}
	
	public function listRentedProducts(){
		$retval = FALSE;
		
		// retrieve required values from the product, rentedProduct, user and invoice table
		$sql = "SELECT uniqueId, name, firstName, lastName, status, startDate, endDate, totalCostPerDay, daysOverdue, totalOverdueCost, amountPaid, subtotal FROM product P JOIN rentedproduct R ON R.itemId = P.uniqueId JOIN user U ON U.id = R.userId JOIN invoice I ON I.itemId = R.itemId";
		
		$qRes = @$this->conn->query($sql);
		if ($qRes !== FALSE) {
			$this->inventory = array();
		while (($Row = $qRes->fetch_assoc())!== NULL) {
			$this->inventory[$Row['uniqueId']]= array();
			$this->inventory[$Row['uniqueId']]['uniqueId']= $Row['uniqueId'];
			$this->inventory[$Row['uniqueId']]['name']= $Row['name'];
			$fullName = $Row['firstName'] . $Row['lastName'];
			$this->inventory[$Row['uniqueId']]['fullName']= $fullName;
			$this->inventory[$Row['uniqueId']]['status']= $Row['status'];	
			$this->inventory[$Row['uniqueId']]['startDate']= $Row['startDate'];
			$this->inventory[$Row['uniqueId']]['endDate']= $Row['endDate'];
			$this->inventory[$Row['uniqueId']]['totalCostPerDay']= $Row['totalCostPerDay'];
			$this->inventory[$Row['uniqueId']]['daysOverdue']= $Row['daysOverdue'];
			$this->inventory[$Row['uniqueId']]['totalOverdueCost']= $Row['totalOverdueCost'];
			$this->inventory[$Row['uniqueId']]['amountPaid']= $Row['amountPaid'];
			$this->inventory[$Row['uniqueId']]['subtotal']= $Row['subtotal'];
		}
		}
		if (count($this->inventory) > 0) {
			echo "<table width='100%'>\n";
			echo "<thead><tr><th>Product ID</th><th>Name</th><th>Client</th><th>Status</th><th>Start Date</th><th>End Date</th><th>Total Cost Per Day</th><th>Days Overdue</th><th>Total Overdue Cost</th><th>Amount Paid</th><th>Subtotal</th><th></th><th></th></tr></thead>\n";
			echo "<tbody>";
			foreach ($this->inventory as $ID => $Info) {
				echo "<tr><td>" . htmlentities($Info['uniqueId']) . "</td>\n";
				echo "<td>" . htmlentities($Info['name']) . "</td>\n";
				echo "<td>" . htmlentities($Info['fullName']) . "</td>\n";
				echo "<td>" . htmlentities($Info['status']) . "</td>\n";
				echo "<td>" . htmlentities($Info['startDate']) . "</td>\n";
				echo "<td>" . htmlentities($Info['endDate']) . "</td>\n";
				echo "<td>AUD " . htmlentities($Info['totalCostPerDay']) . "</td>\n";
				echo "<td>" . htmlentities($Info['daysOverdue']) . "</td>\n";
				echo "<td>AUD " . htmlentities($Info['totalOverdueCost']) . "</td>\n";
				echo "<td>AUD " . htmlentities($Info['amountPaid']) . "</td>\n";
				echo "<td>AUD " . htmlentities($Info['subtotal']) . "</td>\n";
				$itemId = $Info['uniqueId'];
				echo "<td><a class = 'button' href='recordPayment.php?" . $_SERVER['SCRIPT_NAME'] . "?PHPSESSID=" . session_id() . "&itemId=$itemId'>Record Payment</a></td>\n"; 
				
			}
			echo "<td>&nbsp;</td></tr>\n";
			echo "</tbody></table>";
			$retval = TRUE;
		}
		return($retval);
		
	}	
	
	public function searchProductList($string)
	{
		$string = htmlspecialchars($string); 
		$retval = FALSE;
		
		// check if entered string matches the values in category, brand, charteristics or status column
		$sql = "SELECT * FROM product WHERE
					category LIKE '%" . $string . "%' OR
					brand LIKE '%" . $string . "%' OR
					characteristics LIKE '%" . $string . "%' OR
					status LIKE '%" . $string . "%' ";
		$qRes = @$this->conn->query($sql);
		if ($qRes !== FALSE) {
				$this->inventory = array();
				$this->shoppingCart = array();
				while (($Row = $qRes->fetch_assoc())!== NULL) {
					$this->inventory[$Row['uniqueId']]= array();
					$this->inventory[$Row['uniqueId']]['uniqueId']= $Row['uniqueId'];
					$this->inventory[$Row['uniqueId']]['name']= $Row['name'];
					$this->inventory[$Row['uniqueId']]['category']= $Row['category'];
					$this->inventory[$Row['uniqueId']]['brand']= $Row['brand'];
					$this->inventory[$Row['uniqueId']]['yearOfManufacture']= $Row['yearOfManufacture'];
					$this->inventory[$Row['uniqueId']]['characteristics']= $Row['characteristics'];
					$this->inventory[$Row['uniqueId']]['status']= $Row['status'];
					$this->inventory[$Row['uniqueId']]['costPerDay']= $Row['costPerDay'];
					$this->inventory[$Row['uniqueId']]['overdueCostPerDay']= $Row['overdueCostPerDay'];
					$this->shoppingCart[$Row['uniqueId']]= 0;
				}
			}
			
		if (count($this->inventory) > 0) {
			echo "<table width='100%'>\n";
			echo "<thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Brand</th><th>Year Of Manufacture</th><th>Characteristics</th><th>Staus</th><th>Cost Per Day</th><th>Overdue Cost Per Day</th><th></th></tr></thead>\n";
			echo "<tbody>";
			foreach ($this->inventory as $ID => $Info) {
				echo "<tr><td>" . htmlentities($Info['uniqueId']) . "</td>\n";
				echo "<td>" . htmlentities($Info['name']) . "</td>\n";
				echo "<td>" . htmlentities($Info['category']) . "</td>\n";
				echo "<td>" . htmlentities($Info['brand']) . "</td>\n";
				echo "<td>" . htmlentities($Info['yearOfManufacture']) . "</td>\n";
				echo "<td>" . htmlentities($Info['characteristics']) . "</td>\n";
				echo "<td>" . htmlentities($Info['status']) . "</td>\n";
				echo "<td>AUD " . htmlentities($Info['costPerDay']) . "</td>\n";
				echo "<td>AUD " . htmlentities($Info['overdueCostPerDay']) . "</td>\n";
			}
			echo "<td>&nbsp;</td></tr>\n";
			echo "</tbody></table>";
			$retval = TRUE;
		}
		return($retval);
	}
	public function getProductList($available) {
		$retval = FALSE;
		if($available == "yes")
			$sql = "SELECT * FROM product WHERE status='available'";  // retrieve only available products
		else
			$sql = "SELECT * FROM product"; // retrieve all the products
			
			$qRes = @$this->conn->query($sql);
		
			if ($qRes !== FALSE) {
				$this->inventory = array();
				$this->shoppingCart = array();
				while (($Row = $qRes->fetch_assoc())!== NULL) {
					$this->inventory[$Row['uniqueId']]= array();
					$this->inventory[$Row['uniqueId']]['uniqueId']= $Row['uniqueId'];
					$this->inventory[$Row['uniqueId']]['name']= $Row['name'];
					$this->inventory[$Row['uniqueId']]['category']= $Row['category'];
					$this->inventory[$Row['uniqueId']]['brand']= $Row['brand'];
					$this->inventory[$Row['uniqueId']]['yearOfManufacture']= $Row['yearOfManufacture'];
					$this->inventory[$Row['uniqueId']]['characteristics']= $Row['characteristics'];
					$this->inventory[$Row['uniqueId']]['status']= $Row['status'];
					$this->inventory[$Row['uniqueId']]['costPerDay']= $Row['costPerDay'];
					$this->inventory[$Row['uniqueId']]['overdueCostPerDay']= $Row['overdueCostPerDay'];
					$this->shoppingCart[$Row['uniqueId']]= 0;
				}
			}
			
		if (count($this->inventory) > 0) {
			echo "<table width='100%'>\n";
			echo "<thead><tr><th>ID</th><th>Name</th><th>Category</th><th>Brand</th><th>Year Of Manufacture</th><th>Characteristics</th><th>Staus</th><th>Cost Per Day</th><th>Overdue Cost Per Day</th><th></th></tr></thead>\n";
			echo "<tbody>";
			foreach ($this->inventory as $ID => $Info) {
				echo "<tr><td>" . htmlentities($Info['uniqueId']) . "</td>\n";
				echo "<td>" . htmlentities($Info['name']) . "</td>\n";
				echo "<td>" . htmlentities($Info['category']) . "</td>\n";
				echo "<td>" . htmlentities($Info['brand']) . "</td>\n";
				echo "<td>" . htmlentities($Info['yearOfManufacture']) . "</td>\n";
				echo "<td>" . htmlentities($Info['characteristics']) . "</td>\n";
				echo "<td>" . htmlentities($Info['status']) . "</td>\n";
				echo "<td>AUD " . htmlentities($Info['costPerDay']) . "</td>\n";
				echo "<td>AUD " . htmlentities($Info['overdueCostPerDay']) . "</td>\n";
				
			}
			echo "<td>&nbsp;</td></tr>\n";
			echo "</tbody></table>";
			$retval = TRUE;
		}
		return($retval);
	}
	
		
	public function getRentedList()
	{
		//retrieve rented items from renredProucts table
		$sql = "SELECT uniqueId, name, category, brand, startDate, endDate, costPerDay, overdueCostPerDay FROM product JOIN rentedproduct ON itemId = uniqueId";
		$qRes = @$this->conn->query($sql);
		
		if($qRes !== FALSE)
		{
			return $qRes;
		}
		else
		{
				$errorMsg = "Error code: " . @$this->conn->errno . ": " . @$this->conn->error;
				echo $errorMsg;
		}
	}
	public function isItemExist($id){
		$sql = "SELECT * FROM product WHERE uniqueId=" . $id; // check if the product exists in product table
		$qRes = @$this->conn->query($sql);
		
		if($qRes !== FALSE){
			if (mysqli_num_rows($qRes)==0) 
				return false;
			else
				return true;
		}
		else{
			$errorMsg = "Error code: " . @$this->conn->errno . ": " . @$this->conn->error;
			echo $errorMsg;
		}
	}	
	public function insertItem($id, $name, $category, $brand, $year, $char, $status, $cpd, $ocpd){  
		$sql = "INSERT INTO product (uniqueId, name, category, brand, yearOfManufacture, characteristics, status, costPerDay, overdueCostPerDay) VALUES ('$id', '$name', '$category', '$brand', '$year', '$char', '$status', '$cpd', '$ocpd')";
		$qRes = @$this->conn->query($sql);
		if($qRes == FALSE)
		{
			$errorMsg = "Error code: " . @$this->conn->errno . ": " . @$this->conn->error;
			echo $errorMsg;
		}
        else {
			return true;
		}
	}
		
	public function changeStatus($id, $status){
		$sql = "UPDATE product SET status='" . $status ."' WHERE uniqueId=" . $id;
		$qRes = @$this->conn->query($sql);
		if($qRes == FALSE)
		{
			$errorMsg = "Error code: " . @$this->conn->errno . ": " . @$this->conn->error;
			echo $errorMsg;
		}
        else {
			return true;
		}
		
	}
	public function checkStatus($id, $status){        
		$sql = "SELECT status FROM product WHERE uniqueId=" . $id ;
		$qRes = @$this->conn->query($sql);
		if($qRes == FALSE)
		{
			$errorMsg = "Error code: " . @$this->conn->errno . ": " . @$this->conn->error;
			echo $errorMsg;
		}
        else {
			$Row = $qRes->fetch_assoc();
			if($Row['status'] == $status)
				return true;
			else
				return false;
		}
	}
	public function isAmountValid($id, $amount){
		$sql = "SELECT * FROM invoice WHERE itemId=" . $id;
		$qRes = @$this->conn->query($sql);
		
		$Row = $qRes->fetch_assoc();
		if($amount > $Row['subtotal'])
			return FALSE;
		else
			return TRUE;
	}
	
	public function recordPayment($id, $amount){
		$sql = "UPDATE invoice SET subtotal = subtotal - " . $amount . ", amountPaid = " . $amount . " WHERE itemId=" . $id;
		$qRes = @$this->conn->query($sql);
		
		if($qRes != FALSE){
			$sql = "SELECT subtotal FROM invoice WHERE itemId=" . $id;
			$qRes = @$this->conn->query($sql);
			$Row = $qRes->fetch_assoc();
			if($Row['subtotal'] == 0.00){
				$sql1 = "DELETE from rentedproduct WHERE itemId=" . $id;
				@$this->conn->query($sql1);
				$sql2 = "UPDATE product SET status= 'Available' WHERE uniqueId = " . $id;
				@$this->conn->query($sql2);
			}
			return TRUE;
		}
		else{
			$errorMsg = "Error code: " . @$this->conn->errno . ": " . @$this->conn->error;
			echo $errorMsg;
			return FALSE;
		}
	}		
	public function checkIfRented($id){
		$sql = "SELECT status FROM product WHERE uniqueId=" . $id ;
		$qRes = @$this->conn->query($sql);
		if($qRes == FALSE)
		{
			$errorMsg = "Error code: " . @$this->conn->errno . ": " . @$this->conn->error;
			echo $errorMsg;
		}
        else {
			$Row = $qRes->fetch_assoc();
			if($Row['status'] == "Rented" || $Row['status'] == "Overdue")
				return true;
			else
				return false;
		}
	}
	public function listOverdueProducts(){
		$retval = FALSE;
		$sql = "SELECT uniqueId, name, firstName, lastName, status, startDate, endDate, totalCostPerDay, daysOverdue, totalOverdueCost, amountPaid, subtotal FROM product P JOIN rentedproduct R ON R.itemId = P.uniqueId JOIN user U ON U.id = R.userId JOIN invoice I ON I.itemId = R.itemId WHERE P.status='Overdue'";
		
		$qRes = @$this->conn->query($sql);
		if ($qRes !== FALSE) {
			$this->inventory = array();
		while (($Row = $qRes->fetch_assoc())!== NULL) {
			$this->inventory[$Row['uniqueId']]= array();
			$this->inventory[$Row['uniqueId']]['uniqueId']= $Row['uniqueId'];
			$this->inventory[$Row['uniqueId']]['name']= $Row['name'];
			$fullName = $Row['firstName'] . $Row['lastName'];
			$this->inventory[$Row['uniqueId']]['fullName']= $fullName;
			$this->inventory[$Row['uniqueId']]['status']= $Row['status'];	
			$this->inventory[$Row['uniqueId']]['startDate']= $Row['startDate'];
			$this->inventory[$Row['uniqueId']]['endDate']= $Row['endDate'];
			$this->inventory[$Row['uniqueId']]['totalCostPerDay']= $Row['totalCostPerDay'];
			$this->inventory[$Row['uniqueId']]['daysOverdue']= $Row['daysOverdue'];
			$this->inventory[$Row['uniqueId']]['totalOverdueCost']= $Row['totalOverdueCost'];
			$this->inventory[$Row['uniqueId']]['amountPaid']= $Row['amountPaid'];
			$this->inventory[$Row['uniqueId']]['subtotal']= $Row['subtotal'];
		}
		}
		if (count($this->inventory) > 0) {
			echo "<table width='100%'>\n";
			echo "<thead><tr><th>Product ID</th><th>Name</th><th>Client</th><th>Status</th><th>Start Date</th><th>End Date</th><th>Total Cost Per Day</th><th>Days Overdue</th><th> Total Overdue Cost</th><th>Amount Paid</th><th>Subtotal</th><th></th></tr></thead>\n";
			echo "<tbody>";
			foreach ($this->inventory as $ID => $Info) {
				echo "<tr><td>" . htmlentities($Info['uniqueId']) . "</td>\n";
				echo "<td>" . htmlentities($Info['name']) . "</td>\n";
				echo "<td>" . htmlentities($Info['fullName']) . "</td>\n";
				echo "<td>" . htmlentities($Info['status']) . "</td>\n";
				echo "<td>" . htmlentities($Info['startDate']) . "</td>\n";
				echo "<td>" . htmlentities($Info['endDate']) . "</td>\n";
				echo "<td>AUD " . htmlentities($Info['totalCostPerDay']) . "</td>\n";
				echo "<td>" . htmlentities($Info['daysOverdue']) . "</td>\n";
				echo "<td>AUD " . htmlentities($Info['totalOverdueCost']) . "</td>\n";
				echo "<td>AUD " . htmlentities($Info['amountPaid']) . "</td>\n";
				echo "<td>AUD " . htmlentities($Info['subtotal']) . "</td>\n";
			}
			echo "<td>&nbsp;</td></tr>\n";
			echo "</tbody></table>";
			$retval = TRUE;
		}
		return($retval);
		
	}	
	public function isNumeric($input){
		if(is_numeric($input))
			return TRUE;
		else
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
