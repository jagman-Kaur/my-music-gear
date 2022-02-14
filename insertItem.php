<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Insert Product</title>
	<link rel="stylesheet" href="style/styling_return.css">

</head>
<body>
<?php
$errors = 0;
$body = "";
include_once('classMusicStoreForAdmin.php');  
       
    $funObj = new MusicStoreForAdmin();  
    if(isset($_POST['submit'])){  
        $id = $_POST['id'];
		$name = $_POST['name'];
        $category   = $_POST['category'];
		$brand    = $_POST['brand'];
		$year = $_POST['year'];
		$char   = $_POST['char'];
		$status    = $_POST['status'];
		$cpd = $_POST['cpd'];
		$ocpd = $_POST['ocpd'];

		$item = $funObj->isNumeric($id);  //check if product id is numeric
		if(!$item){
			$body .= "<p>Product ID must be numeric<p>\n";
			$errors++;
		}
		if($errors==0){
        $item = $funObj->isItemExist($id);  //check if item with same id exists
        if ($item) {  
            $body .= "<p>Sorry! The item with the same id already exists</p>\n";
			$errors++;
        } 
		}
		if($errors==0){
		$item = $funObj->isNumeric($cpd); // check if cost per day is numeric
		if(!$item){
			$body .= "<p>Cost per day value must be numeric<p>\n";
			$errors++;
		}
		}
		if($errors == 0){
		$item = $funObj->isNumeric($ocpd); // check if overdue cost per day is numeric
		if(!$item){
			$body .= "<p>Overdue Cost per day value must be numeric<p>\n";
			$errors++;
		}
		}
		if($errors == 0){
		$item = $funObj->insertItem($id, $name, $category, $brand, $year, $char, $status, $cpd, $ocpd); //insert the new item into database
		if ($item){
				header("location: success-admin.php?userId=".$userId . "&total=" . $total);
				exit();
		}
		}
		
	}
	     
?>
 <div>
 	<form class = "form" method = "post" action = "">
		 <h3><b> Enter the product details</b> </h3>
		<input class = "text-date" type="text" placeholder="Unique Id" name="id" required /><br><br>
		<input class = "text-date" type="text" placeholder="Name of Product" name="name" required /><br><br>
		<input class = "text-date" type="text" placeholder="Category" name="category" required /><br><br>
		<input class = "text-date" type="text" placeholder="Brand" name="brand" required /><br><br>
		<input class = "text-date" type="text" placeholder="Year of Manufacture" name="year" required /><br><br>
		<input class = "text-date" type="text" placeholder="Characteristics" name="char" required /><br><br>
		<select name="status" id="status" required>
			<option value="" disabled selected hidden>Choose a Status</option>
			<option value="Available">Available</option>
			<option value="Not Available">Not Available</option>
		 </select>
		<input class = "text-date" type="text" placeholder="Cost Per Day" name="cpd" required /><br><br>
		<input class = "text-date" type="text" placeholder="Overdue Cost Per Day" name="ocpd" required /><br><br>
		<input type="submit" name="submit" value="SUBMIT" class = "button" /><br><br>
		<input type="reset" name="reset" value="RESET" class = "button" /><br><br>
		<p class="message">Go Back to <a href="Admin.php">Main Page</a></p>
		
		<?php echo $body; ?>
	</form>

</div>
	
</body>
</html>
