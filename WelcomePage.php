
<!DOCTYPE html>
<html>
<head>
    <title>Welcome to My Music Gear</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="style/styling.css">
</head>
<body> 
<?php
$errors = 0;
$body = "";
if (isset($_POST['email'])){
	include_once('classRegisterLogin.php');
	
	$email    = stripslashes($_POST['email']);
	$password    = stripslashes($_POST['password']);
	
	$funObj = new Login();  
	
	$user = $funObj->verifyLogin($email, $password);  //check if entered email password combo is valid
	if($errors ==0){
		if(!$user){
			$body .= "<p>The id/password " . " combination entered is not valid. </p>\n";
			++$errors;
		}
		
		else
		{
			$name = $user['firstName'] . " " . $user['lastName'];
			$userId = $user['id'];
			if($user['type'] == "Admin")
				header("location: Admin.php?name=".$name);
			else if($user['type'] == "Client")
				header("location: Client.php?userId=".$userId);
			
			exit();
		}
	}
}
?>
<div class="page">
	<h3> <b>Welcome to My Music Gear </b></h3>
    <form class="form" action = "" method = "post">
      <input class = "text-date" type="email" placeholder="Email" name = "email"/>
      <input class = "text-date" type="password" placeholder="Password" name = "password" />
      <button class = "button">login</button>
      <p class="message">Not registered? <a href="register.php">Create an account</a></p>
 
 <?php
	echo $body;
?>
  </form>
  </div>
</body>
</html>

