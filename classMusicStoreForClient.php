<?php

class MusicStoreForClient {
	private $conn = NULL;
	private $inventory = array();
	private $shoppingCart = array();
	function __construct() {
		include("inc_dbconnect.php");
		$this->conn = $conn;
	}
	
	public function listRentedProducts($userId){
		$retval = FALSE;
		$this->calculateCosts($userId);
		$qRes = $this->getRentedList($userId);
		if ($qRes !== FALSE) {
			$this->inventory = array();
		while (($Row = $qRes->fetch_assoc())!== NULL) {
			$this->inventory[$Row['uniqueId']]= array();
			$this->inventory[$Row['uniqueId']]['uniqueId']= $Row['uniqueId'];
			$this->inventory[$Row['uniqueId']]['name']= $Row['name'];
			$this->inventory[$Row['uniqueId']]['category']= $Row['category'];
			$this->inventory[$Row['uniqueId']]['brand']= $Row['brand'];
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
			echo "<thead><tr><th>Name</th><th>Category</th><th>Brand</th><th>Start Date</th><th>End Start</th><th>Total Cost Per Day</th><th>Total Overdue Cost</th><th>Amount Paid</th><th>Subtotal</th><th></th><th></th></tr></thead>\n";
			echo "<tbody>";
			foreach ($this->inventory as $ID => $Info) {
				echo "<tr><td>" . htmlentities($Info['name']) . "</td>\n";
				echo "<td>" . htmlentities($Info['category']) . "</td>\n";
				echo "<td>" . htmlentities($Info['brand']) . "</td>\n";
				echo "<td> " . htmlentities($Info['startDate']) . "</td>\n";
				echo "<td>" . htmlentities($Info['endDate']) . "</td>\n";
				echo "<td>AUD " . htmlentities($Info['totalCostPerDay']) . "</td>\n";
				//echo "<td>" . htmlentities($Info['daysOverdue']) . "</td>\n";
				echo "<td>AUD " . htmlentities($Info['totalOverdueCost']) . "</td>\n";
				echo "<td>AUD " . htmlentities($Info['amountPaid']) . "</td>\n";
				echo "<td>AUD " . htmlentities($Info['subtotal']) . "</td>\n";
				$subtotal = $Info['subtotal'];
				$itemId = $Info['uniqueId'];
						
				echo "<td><a class = 'button' href='returnItem.php?" . $_SERVER['SCRIPT_NAME'] . "?PHPSESSID=" . session_id() . "&userId=$userId&total=$subtotal&itemId=$itemId'>Return</a></td>\n"; 
				if($Info['daysOverdue'] > 0){
					echo "<td><p class = 'overdue'>Overdue by " . htmlentities($Info['daysOverdue']). " days </p></td>";
				}
			}
			//echo "<td>&nbsp;</td></tr>\n";
			echo "</tbody></table>";
			$retval = TRUE;
		}
		return($retval);
		
	}	
	
	public function searchProductList($userId, $string)
	{
		$string = htmlspecialchars($string); 
		$retval = FALSE;
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
			echo "<thead><tr><th>Name</th><th>Category</th><th>Brand</th><th>Year Of Manufacture</th><th>Characteristics</th><th>Staus</th><th>Cost Per Day</th><th>Overdue Cost Per Day</th><th></th></tr></thead>\n";
			echo "<tbody>";
			foreach ($this->inventory as $ID => $Info) {
				echo "<tr><td>" . htmlentities($Info['name']) . "</td>\n";
				echo "<td>" . htmlentities($Info['category']) . "</td>\n";
				echo "<td>" . htmlentities($Info['brand']) . "</td>\n";
				echo "<td>" . htmlentities($Info['yearOfManufacture']) . "</td>\n";
				echo "<td>" . htmlentities($Info['characteristics']) . "</td>\n";
				echo "<td>" . htmlentities($Info['status']) . "</td>\n";
				echo "<td>AUD " . htmlentities($Info['costPerDay']) . "</td>\n";
				echo "<td>AUD " . htmlentities($Info['overdueCostPerDay']) . "</td>\n";
			}
			//echo "<td>&nbsp;</td></tr>\n";
			echo "</tbody></table>";
			$retval = TRUE;
		}
		return($retval);
	}
	public function getProductList($available, $userId) {
		$retval = FALSE;
		if($available == "yes")
			$sql = "SELECT * FROM product WHERE status='available'";
		else
			$sql = "SELECT * FROM product";
			
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
			echo "<thead><tr><th>Name</th><th>Category</th><th>Brand</th><th>Year Of Manufacture</th><th>Characteristics</th><th>Staus</th><th>Cost Per Day</th><th>Overdue Cost Per Day</th><th><th></tr></thead>\n";
			echo "<tbody>";
			foreach ($this->inventory as $ID => $Info) {
				echo "<tr><td>" . htmlentities($Info['name']) . "</td>\n";
				echo "<td>" . htmlentities($Info['category']) . "</td>\n";
				echo "<td>" . htmlentities($Info['brand']) . "</td>\n";
				echo "<td>" . htmlentities($Info['yearOfManufacture']) . "</td>\n";
				echo "<td>" . htmlentities($Info['characteristics']) . "</td>\n";
				echo "<td>" . htmlentities($Info['status']) . "</td>\n";
				echo "<td>AUD " . htmlentities($Info['costPerDay']) . "</td>\n";
				echo "<td>AUD " . htmlentities($Info['overdueCostPerDay']) . "</td>\n";
				$itemId = $Info['uniqueId'];
				if($Info['status'] == 'Available'){
					echo "<td><a class = 'button' href='rentItem.php?" . $_SERVER['SCRIPT_NAME'] . "?PHPSESSID=" . session_id() . "&userId=$userId&itemId=$itemId'>Rent</a></td>\n"; 
				}
			}
			//echo "<td>&nbsp;</td></tr>\n";
			echo "</tbody></table>";
			$retval = TRUE;
		}
		return($retval);
	}
	
		
	public function getRentedList($userId)
	{
		$sql = "SELECT uniqueId, name, category, brand, startDate, endDate, totalCostPerDay, daysOverdue, totalOverdueCost, amountPaid, subtotal FROM product P JOIN rentedproduct R ON R.itemId = P.uniqueId JOIN invoice I ON I.itemId = R.itemId WHERE R.userId=" . $userId;
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
	public function isItemRented($itemId){
		$sql = "SELECT * FROM rentedproduct WHERE itemId=" . $itemId;
		$qRes = @$this->conn->query($sql);
		
		if($qRes != FALSE){
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
	public function isItemUnvailable($itemId){
		$sql = "SELECT * FROM product WHERE status='Not Available' AND uniqueId=" . $itemId;
		$qRes = @$this->conn->query($sql);
		
		if($qRes != FALSE){
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
	public function isDateValid($startDate, $endDate){
		$startDate = new DateTime($startDate);
		$endDate = new DateTime($endDate);

		if($endDate < $startDate) {
			return FALSE;
		}
		else
			return TRUE;
	}
		
	public function rentItem($userId, $itemId, $startDate, $endDate){
		$sql = "INSERT INTO rentedproduct (userId, itemId, startDate, endDate) VALUES ('$userId', '$itemId', '$startDate', '$endDate')";
		$qRes = @$this->conn->query($sql);
		if($qRes !== FALSE){
			$sql1 = "UPDATE product SET status='Rented' WHERE uniqueId=" . $itemId;
			$qRes1 = @$this->conn->query($sql1);
			$sql2 = "SELECT costPerDay, overdueCostPerDay FROM product WHERE uniqueId=" . $itemId;
			$qRes2 = @$this->conn->query($sql2);
			$Row = $qRes2->fetch_assoc();
			if($qRes1 != FALSE && $qRes2 !=FALSE){
				$this->calculateCosts($userId);
				return $Row;
			}
			else
			{
				$errorMsg = "Error code: " . @$this->conn->errno . ": " . @$this->conn->error;
				echo $errorMsg;	
				return FALSE;
			}
		}
		else{
			$errorMsg = "Error code: " . @$this->conn->errno . ": " . @$this->conn->error;
			echo $errorMsg;
			return FALSE;
		}
	}
	public function calculateCosts($userId){
		$sql = "SELECT itemId, startDate, endDate, costPerDay, overdueCostPerDay, DATEDIFF(endDate, startDate) AS days FROM rentedproduct JOIN product ON itemId = uniqueId WHERE userId='" . $userId . "'";
		$qRes = @$this->conn->query($sql);
		
		if($qRes !== FALSE){
			while($Row = $qRes->fetch_assoc()){
				$totalCostPerDay = $Row['days'] * $Row['costPerDay'];
							
				$diff = date_diff(date_create(date('Y-m-d', strtotime($Row['endDate']))), date_create(date('Y-m-d')));
				$overdueDays = $diff->format('%R%a');
				
				if($overdueDays >0)
					$totalOverdueCost = $overdueDays * $Row['overdueCostPerDay'];
				else{
					$overdueDays = 0;
					$totalOverdueCost = 0;
				}
				$subtotal = $totalCostPerDay + $totalOverdueCost;
				$itemId = $Row['itemId'];
				$query = "SELECT * FROM invoice WHERE itemId=". $itemId;
				$queryRes = $this->conn->query($query);
				if (mysqli_num_rows($queryRes) > 0){
					$sql = "UPDATE invoice SET totalCostPerDay =" . $totalCostPerDay . ", daysOverdue =" . $overdueDays . ", totalOverdueCost =" . $totalOverdueCost . ", subtotal=" . $subtotal . " WHERE itemId=" . $Row['itemId'] . " AND userId=" . $userId; 
					$res = $this->conn->query($sql);
					if($res == FALSE){
						$errorMsg = "Error code: " . @$this->conn->errno . ": " . @$this->conn->error;
						echo $errorMsg;
					}
				}
				else{
					$sql = "INSERT INTO invoice (userId, itemId, totalCostPerDay, daysOverdue, totalOverdueCost, subtotal) VALUES ('$userId','$itemId', '$totalCostPerDay', '$overdueDays', '$totalOverdueCost','$subtotal')"; 
					$this->conn->query($sql);
					}
				}
			}
		
		else{
			$errorMsg = "Error code: " . @$this->conn->errno . ": " . @$this->conn->error;
			echo $errorMsg;	
		}
				
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
