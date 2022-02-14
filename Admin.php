<?php
session_start();
require_once("classMusicStoreForAdmin.php");
$storeID = "ELECBOUT";
$userId = "";
$storeInfo = array();
if (class_exists("MusicStoreForAdmin")) {
	
		$Store = new MusicStoreForAdmin();
		
		if(isset($_GET['userId']))
			$userId = $_GET['userId'];
	
}
else {
	$ErrorMsgs[] = "The OnlineStore class is not available!";
	$Store = NULL;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Admin Page</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style/AdminClientStyle.css"/>
</head>
<body>

<div class="top">
	<div class = "top-left">
		<a href='Admin.php'>All Products</a>
		<a href='Admin.php?rented=yes'>Rented Products</a>
		<a href='Admin.php?overdue=yes'>Overdue Products</a>
		<a href='Admin.php?available=yes'>Available Products</a>
		<a href='changeStatus.php'>Change Status</a>
		<a href='insertItem.php'>Insert Item</a>
		<a href='index.php'>Log Out</a>
	</div>
  <div class="search-container">
    <form action="/Admin.php" method = "post">
      <input type="text" placeholder="Search.." name="search" required>
      <button type="submit" name="submit">Submit</button>
    </form>
  </div>
</div>

<header>
	<div class = "bg"></div>
  <h2>Admin Page</h2>
</header>

<section>
   
  <article>
  	<?php 
	if(isset($_GET['rented']))
		$Store->listRentedProducts();  //display rented products
	else if(isset($_GET['overdue']))
		$Store->listOverdueProducts(); //display overdue products
	else if(isset($_POST['submit']))
		$Store->searchProductList($_POST['search']); //display searched products
	else if(isset($_GET['available']))
		$Store->getProductList("yes"); // display available products
	else
		$Store->getProductList("no"); //display all products
	?>
  </article>
</section>


</body>
</html>