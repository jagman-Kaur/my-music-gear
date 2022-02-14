<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Registration</title>
    <link rel="stylesheet" href="style/styling.css"/>
</head>
<body>
<?php
$errors = 0;
$body = "";
include_once('classRegisterLogin.php');  
       
    $funObj = new Register();  
    if(isset($_POST['register'])){  
        $fname = stripslashes($_POST['fname']);
		$lname = stripslashes($_POST['lname']);
        $email   = stripslashes($_POST['email']);
		$phone    = stripslashes($_POST['phone']);
		$type = stripslashes($_POST['type']);
		$password1 = stripslashes($_POST['password1']);
        $password2 = stripcslashes($_POST['password2']);
		
        $user = $funObj->isEmailValid($email);  
        if (!$user) {  
            $body .= "<p>You need to enter a valid " . "e-mail address.</p>\n";
			$email = ""; 
			$errors++;
        } 
		
		$user = $funObj->checkPasswordLength($password1); 
		if (!$user){
			$body .= "<p>The password is too short.</p>\n";
			$password = "";
			$password2 = "";
			$errors++;
		}
		
		$user = $funObj->isPasswordMatch($password1, $password2); 
		if (!$user){
			$body .= "<p>The passwords do not match.</p>\n";
			$password = "";
			$password2 = "";
			$errors++;
		}
		
		if($errors == 0){
		$user = $funObj->isUserExist($email, $type); 
		if($user){
			$body .= "<p>User already Exists.</p>\n";
			++$errors;
		}
	}
	
		if($errors == 0){
		$user = $funObj->UserRegister($fname, $lname, $phone, $email, $type, $password1); 
		if ($user) {
			$body .= "<p>Unable to save your registration " . " information." . $user . "</p>\n";
			++$errors;
		}
		
	}
		if ($errors == 0) {
			$body .= "<p>Thank you, $fname $lname. Your registration was successfull";
    }  

}
     
?>
 <div>
	<form  class = "form" method = "post" action = "">
		<input type="text" placeholder="First Name" name="fname" required />
		<input type="text" placeholder="Last Name" name="lname" required />
		<input type="email" placeholder="Email" name="email" required />
		<input type="text" placeholder="Phone Number" name="phone" required />
		<select name="type" id="type" required>
			<option value="" disabled selected hidden>Choose a Type</option>
			<option value="Admin">Admin</option>
			<option value="Client">Client</option>
		</select>
		<input type="password" placeholder="Password" name="password1" required />
		<input type="password" placeholder="Confirm Password" name="password2" required />
		<input type="submit" name="register" value="REGISTER" class = "button" />
		<input type="reset" name="reset" value="RESET" class = "button" />
		<p class="message">Already registered? <a href="WelcomePage.php">Sign In</a></p>

<?php
    echo $body;
?>
	</form>
</div>
	
</body>
</html>