

<!DOCTYPE html>
<html>
<head>
    <title>Rent Product</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="style/styling.css">
</head>
<body> 
<?php
session_start();
require_once('classMusicStoreForClient.php');
$errors = 0;
$body = "";

$funObj = new MusicStoreForClient();

	$item = $funObj->isItemRented($_GET['itemId']);      // check if item is already rented
	$item2 = $funObj->isItemUnvailable($_GET['itemId']); // check if item is unavailable
	if($item || $item2){
		header("location: failure.php?userId=".$_GET['userId']);
		exit();
		}
	
if (isset($_POST['startDate'])){

	$startDate    = $_POST['startDate'];
	$endDate    = $_POST['endDate'];
	
	$item = $funObj->isDateValid($startDate, $endDate);     //check if end date is before start date
	if(!$item){
		$body .= "<p><b>Inavlid End Date<b></p>\n";
		$errors++;
	}
	if ($errors == 0){
	$item = $funObj->rentItem($_GET['userId'], $_GET['itemId'], $startDate, $endDate);  //rent the item
	if($errors ==0){
			
		if($item)
		{
			$costPerDay = $item['costPerDay'];
			$overdueCostPerDay = $item['overdueCostPerDay'];
			header("location: success-rent.php?costPerDay=".$costPerDay . "&overdue=" . $overdueCostPerDay . "&endDate=" . $endDate . "&userId=" . $_GET['userId']);
			exit();
		}	
			//$body .= "Success. Item rented. It must be returned on or before " . $endDate . ". You will be charged $" . $costPerDay . " per day till " . $endDate . " and $" . $overdueCostPerDay . " per day after " . $endDate;
			
		}
		else
			$errors++;
	}
	
}
?>
	
<div>
    <form class="form" action = "" method = "post">
	<h3 style="text-align:center;"> <b>Enter the details</b></h3>
	<label for = "start"> Start Date </label>
      <input class= "text-date" type="date" id = "start" name = "startDate" min ="<?php echo date("Y-m-d");?>"/>
	 <label for = "end"> End Date </label>
	  <input class= "text-date" type="date" id = "end" name = "endDate" min ="<?php echo date("Y-m-d");?>"/>
	 <div>
      <button class = "button">submit</button>
	 </div>
      
	<p class = "message">Return to the <a href='/Client.php?userId= <?php echo $_GET['userId'] ?>'>Main Page</a></p>
	<?php echo $body; ?>



  </form>
</div>
</body>
</html>


