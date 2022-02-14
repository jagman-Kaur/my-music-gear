<?php
session_start();
$errors = 0;
$body = "";
require_once("classPayment.php");
if (class_exists("Payment")) {
	
		$funObj = new Payment();
		
		if(isset($_GET['total'])){
			$total = $_GET['total'];
		if(isset($_GET['itemId']))
			$itemId = $_GET['itemId'];
		if(isset($_GET['userId']))
			$userId = $_GET['userId'];
}
	
}
else {
	$ErrorMsgs[] = "The OnlineStore class is not available!";
	$Store = NULL;
}

if(isset($_POST['submit'])){  
        $name = stripslashes($_POST['cardname']);
		$number = stripslashes($_POST['cardnumber']);
        $expmonth   = stripslashes($_POST['expmonth']);
		$expyear    = stripslashes($_POST['expyear']);
		$cvv = stripslashes($_POST['cvv']);
		
		$pay = $funObj->isAlpha($name);
		if(!$pay){
			$body .= "<p>Name should only have alphabets<p>\n";
			$errors ++;
		}
		$pay = $funObj->isNumeric($number);
		if(!$pay){
			$body .= "<p>Card Number must be a digit</p>\n";
			$number = ""; 
			$errors++;
		}
		
        $pay = $funObj->checkCardLength($number);  
        if (!$pay) {  
            $body .= "<p>Card length must be 16 digits</p>\n";
			$number = ""; 
			$errors++;
        } 
		
		$pay = $funObj->isNumeric($expmonth);
		if(!$pay){
			$body .= "<p>Month must be a digit</p>\n";
			$expmonth = ""; 
			$errors++;
		}
		$pay = $funObj->checkMonthYearLength($expmonth);
		if(!$pay){
			$body .= "<p>Month must be 2 digits</p>\n";
			$expmonth = ""; 
			$errors++;
		}
		$pay = $funObj -> isMonthValid($expmonth);
		if(!$pay){
			$body .= "<p>Invalid month<p>\n";
			$errors++;
		}
		$pay = $funObj->isNumeric($expyear);
		if(!$pay){
			$body .= "<p>Year must be a digit</p>\n";
			$expyear = ""; 
			$errors++;
		}
		$pay = $funObj->checkMonthYearLength($expyear);
		if(!$pay){
			$body .= "<p>Year must be 2 digits</p>\n";
			$expyear = ""; 
			$errors++;
		}
		$pay = $funObj->isNumeric($cvv);
		if(!$pay){
			$body .= "<p>CVV must be a digit</p>\n";
			$cvv = ""; 
			$errors++;
		}
		$pay = $funObj->checkCVVLength($cvv);
		if(!$pay){
			$body .= "<p>CVV must be 3 digits</p>\n";
			$cvv = ""; 
			$errors++;
		}
			
		if($errors == 0){
		$pay = $funObj->makePayment($itemId, $total); 
		if (!$pay) {
			$body .= "Payment Unsuccessfull</p>\n";
			++$errors;
		}
		
	}
		if ($errors == 0) {
			header("location: success-client.php?userId=".$userId . "&total=" . $total);
			exit();
    }  

}

?>
<!DOCTYPE html>
<html>
<head>
<title> Payment </title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="style/paymentStyle.css">
</head>
<body>


<div class="row">
  <div>
    <div class="container">
         <p>Total Amount<span class="price" style="color:black"><b><?php echo $total; ?></b></span></p>
    </div>
    <div class="container">
      <form action=""  method="post">
          <div class="col-25">
            <h3>Payment</h3>
			<hr>
            <label for="cname">Name on Card</label>
            <input type="text" id="cname" name="cardname" required>
            <label for="ccnum">Credit card number</label>
            <input type="text" id="ccnum" name="cardnumber" required>
            <label for="expmonth">Exp Month</label>
            <input type="text" id="expmonth" name="expmonth" required>
            <div class="row">
              <div class="col-50">
                <label for="expyear">Exp Year</label>
                <input type="text" id="expyear" name="expyear" required>
              </div>
              <div class="col-50">
                <label for="cvv">CVV</label>
                <input type="text" id="cvv" name="cvv" required>
              </div>
            </div>
          </div>
          
        </div>
        <input type="submit" value="PAY" class="btn" name = "submit">
		<p class = "message"> Return to the <a href='Client.php?userId= <?php echo $userId; ?>'>Main Page</a></p><br>
		<?php echo $body; ?>
      </form>
    </div>
  </div>
</div>

</body>
</html>
