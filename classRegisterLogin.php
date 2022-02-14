<?php  

session_start();  
    class Register {  
		
		private $conn = NULL;            
        function __construct() {  
              
            include("inc_dbconnect.php");
			$this->conn = $conn;  
               
        }  
        // destructor  
        function __destruct() {
			if (!$this->conn->connect_error)
			@$this->conn->close();       
        }  
        public function UserRegister($fname, $lname, $phone, $email, $type, $password){  // adds new user details to the database
       
                $sql = "INSERT INTO user (firstName, lastName, phone, email, type, password) VALUES('$fname','$lname', '$phone', '$email', '$type' ," . " '" . md5($password) . "')";
				$qRes = @$this->conn->query($sql);
				if($qRes == FALSE)
				{
					$errorMsg = "Error code: " . @$this->conn->errno . ": " . @$this->conn->error;
					return $errorMsg;
				}
                else{
					$id = @$this->conn->insert_id;
					$_SESSION['id'] = $id;
				}
				 $sql = "SELECT * FROM user";
				$qRes = @$this->conn->query($sql);
				
        }  
		 
      
        public function isUserExist($email, $type){   // checks if user already exists in the database
            $sql = "SELECT count(*) FROM user where email='" . $email . "'";
			$qRes = @$this->conn->query($sql);
			if ($qRes != FALSE) {
				$Row = mysqli_fetch_row($qRes);
				if ($Row[0]>0) {
					return TRUE;
				}
				else
					return FALSE;
			}
        }  
		public function isEmailValid($email){
			if (preg_match("/^[\w-]+(\.[\w-]+)*@[\w-]+(\.[\w-]+)*(\.[a-z]{2,3})$/i", $email) == 0) {
				return FALSE;
			}
			else
				return TRUE;
		}
		public function checkPasswordLength($password){
			if (strlen($password) < 6) {
				return FALSE;
			}
			else
				return TRUE;
		}
		public function isPasswordMatch($password1, $password2){
			if ($password1 <> $password2) {
				return FALSE;
			}
			else
				return TRUE;
		}
    }  
class Login {  
		
		private $conn = NULL;            
        function __construct() {  
              
            include("inc_dbconnect.php");
			$this->conn = $conn;  
               
        }  
        // destructor  
        function __destruct() {
			if (!$this->conn->connect_error)
			@$this->conn->close();       
        }  
        
        public function verifyLogin($email, $password){   //match email and password
            $sql = "SELECT id, firstName, lastName, type FROM user where email='" . $email."' and password='" . md5(stripslashes($password)) . "'";
			$qRes = @$this->conn->query($sql); 
          
			if ($qRes != FALSE) {
				if (mysqli_num_rows($qRes)==0) {
					return FALSE;
				}
	
			else {
				$Row = mysqli_fetch_assoc($qRes);
				$id = $Row['id'];
				$fname = $Row['firstName'];
				$lname = $Row['lastName'];
				$_SESSION['id'] = $id;
				return $Row;
			} 
			}
    }
        
    }  
?>