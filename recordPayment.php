<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Record Payment</title>
	<link rel="stylesheet" href="style/styling.css">

</head>
<body>
<?php
$errors = 0;
$body = "";
	if(isset($_GET['itemId']))
		$itemId = $_GET['itemId'];
include_once('classMusicStoreForAdmin.php');  
       
    $funObj = new MusicStoreForAdmin();  
    if(isset($_POST['submit'])){  
      
		$amount = $_POST['amount'];
	
		$item = $funObj->isItemExist($itemId);  //check if item exists
        if (!$item) {  
            $body .= "<p>Sorry! The item does not exists</p>\n";
			$errors++;
        } 
		
		$item = $funObj->checkIfRented($itemId); //check if product is rented
		if(!$item){
			$body .= "This product is Not rented. Cannot record payment";
			$errors++;
		}
		$item = $funObj->isNumeric($amount);
		if(!$item){
			$body .= "Amount must be numeric";
			$errors++;
		}
		if($errors == 0){
		$item = $funObj->isAmountValid($itemId, $amount); //check if entered amount is less than or equal to the subtotal
		if(!$item)
		{
			$body .= "<p>Entered amount greater than the subtotal</p>\n";
			$errors++;
		}
		}
				
		if($errors == 0){
		$item = $funObj->recordPayment($itemId, $amount);  //record payment
		if ($item){
				$body .= "Payment Recorded successfully";
		}
		}
			
  
		
	}
	
     
?>
 <div>
	<form class = "form" method = "post" action = "">
		<h3><b> Enter the details</b> </h3>
		<select name="mode" id="mode" required>
			<option value="" disabled selected hidden>Payment Made through?</option>
			<option value="cash">Cash</option>
			<option value="cheque">Cheque</option>
		</select>
		<input class = "text-date"type="text" placeholder="Amount" name="amount" required /><br><br>
		<input type="submit" name="submit" value="Record Payment" class = "button" /><br><br>
		<p class="message">Go Back to <a href="Admin.php">Main Page</a></p>
			  
		<?php 	echo $body; ?>
	</form>
</div>
	
</body>
</html>
