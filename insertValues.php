<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Insert Values</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<?php
    include 'inc_dbconnect.php';
    if ($conn===FALSE){
        die("connection failed");
    }
    
    if ($stmt = mysqli_prepare($conn, "INSERT INTO product (uniqueId, name, category, brand, yearOfManufacture, characteristics, status, costPerDay, overdueCostPerDay) VALUES (?,?,?,?,?,?,?,?,?)"))
    {
        mysqli_stmt_bind_param($stmt, "issssssdd", $uniqueId, $name, $category, $brand, $yearOfManufacture, $characteristics, $status, $costPerDay, $overdueCostPerDay);


        // set parameters and execute
        $uniqueId = 100;
		$name = "Acoustic Guitar 1";
		$category = "Guitar";
		$brand = "Martin";
		$yearOfManufacture = "2008";
		$characteristics = "Acoustic Guitar";
		$status = "Available";
		$costPerDay = 10.50;
		$overdueCostPerDay = 20.50;
        mysqli_stmt_execute($stmt);

		$uniqueId = 101;
		$name = "Electric Guitar 1";
		$category = "Guitar";
		$brand = "Taylor";
		$yearOfManufacture = "2009";
		$characteristics = "Electric Guitar"; 
		$status = "Available";
		$costPerDay = 12.50;
		$overdueCostPerDay = 24.50;
        mysqli_stmt_execute($stmt);
		
		$uniqueId = 102;
		$name = "Digital Drum 1";
		$category = "Drum";
		$brand = "Gibson";
		$yearOfManufacture = "2010";
		$characteristics = "Digital Drum";
		$status = "Available";
		$costPerDay = 15.50;
		$overdueCostPerDay = 30.00;
        mysqli_stmt_execute($stmt);
		
		$uniqueId = 103;
		$name = "Electric Piano 1";
		$category = "Piano";
		$brand = "Gibson";
		$yearOfManufacture = "2011";
		$characteristics = "Electric Piano";
		$status = "Available";
		$costPerDay = 17.50;
		$overdueCostPerDay = 32.00;
        mysqli_stmt_execute($stmt);
		
		$uniqueId = 104;
		$name = "Headephone 1";
		$category = "Headphone";
		$brand = "Gibson";
		$yearOfManufacture = "2011";
		$characteristics = "Bluetooth Headephone";
		$status = "Not Available";
		$costPerDay = 17.50;
		$overdueCostPerDay = 32.00;
        mysqli_stmt_execute($stmt);
		
		$uniqueId = 105;
		$name = "Violin 1";
		$category = "Violin";
		$brand = "Taylor";
		$yearOfManufacture = "2008";
		$characteristics = "Electric Violin";
		$status = "Not Available";
		$costPerDay = 20.50;
		$overdueCostPerDay = 35.00;
        mysqli_stmt_execute($stmt);
		
		$uniqueId = 106;
		$name = "Violin 2";
		$category = "Violin";
		$brand = "Taylor";
		$yearOfManufacture = "2008";
		$characteristics = "Acoustic Violin";
		$status = "Overdue";
		$costPerDay = 20.50;
		$overdueCostPerDay = 35.00;
		mysqli_stmt_execute($stmt);
		
		$uniqueId = 107;
		$name = "Saxophone 1";
		$category = "Saxophone";
		$brand = "Taylor";
		$yearOfManufacture = "2009";
		$characteristics = "Saxophone";
		$status = "Overdue";
		$costPerDay = 25.50;
		$overdueCostPerDay = 40.00;
        mysqli_stmt_execute($stmt);
		
		echo "Values inserted into product table <br>";
     
    }
	
	if ($stmt = mysqli_prepare($conn, "INSERT INTO user (id, firstName, lastName, phone, email, type, password) VALUES (?,?,?,?,?,?,?)"))
    {
        mysqli_stmt_bind_param($stmt, "issssss", $id, $firstName, $lastName, $phone, $email, $type, $password);


        // set parameters and execute
		$id = 1;
		$firstName = "Kashish";
		$lastName = "Kakkar";
		$phone = "1234569783";
		$email = "kashishkakkar@gmail.com";
		$type = "Client";
		$password = "d8578edf8458ce06fbc5bb76a58c5ca4";
		mysqli_stmt_execute($stmt);
		
		$id = 2;
		$firstName = "Jagman";
		$lastName = "Kaur";
		$phone = "1234567896";
		$email = "jagman@gmail.com";
		$type = "Admin";
		$password = "d8578edf8458ce06fbc5bb76a58c5ca4";
        mysqli_stmt_execute($stmt);
		
		echo "Values inserted into user table <br>";
		    
    }
	
	if ($stmt = mysqli_prepare($conn, "INSERT INTO rentedProduct (userId, itemId, startDate, endDate) VALUES (?,?,?,?)"))
    {
        mysqli_stmt_bind_param($stmt, "iiss", $userId, $itemId, $startDate, $endDate);


        // set parameters and execute
		$userId = 1;
		$itemId = 106;
		$startDate = date('Y-m-d' , strtotime('04/04/2021'));
		$endDate = date('Y-m-d' , strtotime('05/04/2021'));
        mysqli_stmt_execute($stmt);
		
		$userId = 1;
		$itemId = 107;
		$startDate = date('Y-m-d' , strtotime('04/21/2021'));
		$endDate = date('Y-m-d' , strtotime('05/20/2021'));
        mysqli_stmt_execute($stmt);
     
		echo "Values inserted into rentedProduct table <br>";
    }

	if ($stmt = mysqli_prepare($conn, "INSERT INTO invoice (userId, itemId, totalCostPerDay, daysOverdue, totalOverDueCost, subtotal) VALUES (?,?,?,?,?,?)"))
    {
        mysqli_stmt_bind_param($stmt, "iididd", $userId, $itemId, $totalCostPerDay, $daysOverdue, $totalOverDueCost, $subtotal);


        // set parameters and execute
		$userId = 1;
		$itemId = 106;
		$totalCostPerDay = 615;
		$daysOverdue = 26;
		$totalOverDueCost = 910;
		$subtotal = 1525;
        mysqli_stmt_execute($stmt);
		
		$userId = 1;
		$itemId = 107;
		$totalCostPerDay = 739.50;
		$daysOverdue = 10;
		$totalOverDueCost = 400;
		$subtotal = 1139.50;
        mysqli_stmt_execute($stmt);
     
		echo "Values inserted into invoice table <br>";
    }


mysqli_close($conn);
?>
</body>
</html>
