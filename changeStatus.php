<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Change Status</title>
	<link rel="stylesheet" href="style/styling.css">

</head>
<body>
<?php
$errors = 0;
$body = "";
include_once('classMusicStoreForAdmin.php');  
       
    $funObj = new MusicStoreForAdmin();  
    if(isset($_POST['submit'])){  
        $id = $_POST['id'];
		$status = $_POST['status'];
		
		$item = $funObj->isNumeric($id);  //check if the item exists
        if (!$item) {  
            $body .= "<p>Product ID must be numeric</p>\n";
			$errors++;
        } 
		if($errors == 0){
		$item = $funObj->isItemExist($id);  //check if the item exists
        if (!$item) {  
            $body .= "<p>Sorry! The item does not exists</p>\n";
			$errors++;
        } 
		}
		if($errors == 0){
		$item = $funObj->checkIfRented($id); //check if item is rented
		if($item){
			$body .= "This product is rented. Cannot change it's status";
			$errors++;
		}
		}
		if($errors == 0){
		$item = $funObj->checkStatus($id, $status); //check if current status is same as the new status
		if($item){
			$body .= "Status is already " . $status ;
			$errors++;
		}
		}	
		if($errors == 0){
		$item = $funObj->changeStatus($id, $status);  //change the status
		if ($item){
				header("location: success-admin.php?userId=".$userId . "&total=" . $total);
				exit();
		}
		}
			
  
		
	}

	
     
?>
 <div class="page">
	<form class = "form" method = "post" action = "">
		<h3 style = "text-align:center;"><b> Enter the details</b> </h3>
		  <input class= "text-date"type="text" placeholder="Unique Id" name="id" required /><br><br>
		  <select name="status" id="status" required>
			<option value="" disabled selected hidden>Choose a Status</option>
			<option value="Available">Available</option>
			<option value="Not Available">Not Available</option>
		  </select>
		  <input class = "button" type="submit" name="submit" value="SUBMIT" /><br><br>
		  <input class = "button" type="reset" name="reset" value="RESET" /><br><br>
		  <p class="message">Go Back to <a href="Admin.php">Main Page</a></p>
		  
		  <?php 	echo $body; ?>
	</form>
</div>
	
</body>
</html>
