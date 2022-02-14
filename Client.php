<?php
session_start();
require_once("classMusicStoreForClient.php");
$storeID = "ELECBOUT";
$userId = "";
$storeInfo = array();
if (class_exists("MusicStoreForClient")) {
	
		$Store = new MusicStoreForClient();
		
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
<title>Client Page</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="style/AdminClientStyle.css"/>
</head>
<body>

<div class="top">
	<div class = "top-left">
		<a href='Client.php?rented=yes&userId=<?php echo $userId ?>'>My Rentals</a>
		<a href='Client.php?available=yes&userId=<?php echo $userId ?>'>Available Products</a>
		<a href='Client.php?userId=<?php echo $userId ?>'>All Products</a>
		<a href='index.php'>Log Out</a>
	</div>
  <div class="search-container">
    <form action="/Client.php?userId=<?php echo $userId ?>" method = "post">
      <input type="text" placeholder="Search.." name="search" required>
      <button type="submit" name="submit">Submit</button>
    </form>
  </div>
</div>

<header>
	<div class = "bg"></div>
  <h2>My Music Gear<h2>
</header>

<section>
  
  
  <article>
  	<?php 
	if(isset($_GET['rented']))
		$Store->listRentedProducts($userId);  //display rented products
	else if(isset($_GET['available']))
		$Store->getProductList("yes", $userId); //display available products
	else if(isset($_POST['submit']))
		$Store->searchProductList($userId, $_POST['search']); //display searched products
	else
		$Store->getProductList("no", $userId); //display all products
	?>
  </article>
</section>


</body>
</html>