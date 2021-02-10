<?php
require_once("Constants.php");

class Account{
	private $db;
	private $errorArray=array();
	public function __construct($db){
		$this->db=$db;
	}
	public function register($fname ,$lname ,$username,$email1,$email2,$password1,$password2){
		$this->validateName($fname);
		$this->validateLname($lname);
		$this->validateUname($username);
		$this->validateEmail($email1,$email2);
		$this->validatePassword($password1,$password2);
		if(empty($this->errorArray)){
			return $this->insertDetails($fname,$lname,$username,$email1,$password1);

		}
		else
		{
			return false;
		}

	}

	public function updateDetails($firstName,$lastName,$email,$username){

		$this->validateName($firstName);
		$this->validateLname($lastName);
		$this->validateNewEmail($email,$username);

		if(empty($this->errorArray)){
			$query=$this->db->prepare("UPDATE users SET firstName='$firstName',lastName='$lastName',email='$email' WHERE username='$username'");
			$query->execute();
			return true;

		}else{
			return false;
		}

	}

	public function getFirstError(){
		if(!empty($this->errorArray)){
			return $this->errorArray[0];
		}else{
			return "";
		}
	}


	private function insertDetails($fname,$lname,$username,$email,$pass){
		$pw=hash("sha512",$pass);
		$profilePicture="assets/images/profilePictures/default.png";
		$query=$this->db->prepare("INSERT INTO users(firstName,lastName,username,email,password,profilePic)VALUES(:fname,:lname,:username,:email,:pass,:profilePicture)");


		$query->bindParam(":fname",$fname);
		$query->bindParam(":lname",$lname);
		$query->bindParam(":username",$username);
		$query->bindParam(":email",$email);
		$query->bindParam(":pass",$pw);
		$query->bindParam(":profilePicture",$profilePicture);

		return $query->execute();
		
	}

	private function validateName($fname){
		if(strlen($fname)<3 || strlen($fname)>20){
			array_push($this->errorArray,Constants::$firstNameError);
			return;
		}
	}
	private function validateLname($lname){
		if(strlen($lname)<3 || strlen($lname)>25){
			array_push($this->errorArray,Constants::$lastNameError);
			return;
		}
	}
	private function validateUname($username){
		if(strlen($username)>25 || strlen($username)<5){
			array_push($this->errorArray,Constants::$usernameCharError);
			return;
		}

		$query=$this->db->prepare("SELECT username FROM users WHERE username='$username'");
		$query->execute();
		if($query->rowCount() != 0){
			array_push($this->errorArray,Constants::$usernameExists);
			return;
		}
	}
	private function validateEmail($email1,$email2){
		if($email1!=$email2){
			array_push($this->errorArray,Constants::$emailMismatch);
			return;
		}
		if(!filter_var($email1,FILTER_VALIDATE_EMAIL)){
			array_push($this->errorArray,Constants::$invalidEmail);
			return;
		}

		$query=$this->db->prepare("SELECT email FROM users WHERE email='$email1'");
		$query->execute();
		if($query->rowCount()!=0){
			array_push($this->errorArray,Constants::$emailTaken);
		}
	}

	private function validateNewEmail($email1,$username){
		
		if(!filter_var($email1,FILTER_VALIDATE_EMAIL)){
			array_push($this->errorArray,Constants::$invalidEmail);
			return;
		}

		$query=$this->db->prepare("SELECT email FROM users WHERE email='$email1' AND username != '$username'");
		$query->execute();
		if($query->rowCount()!=0){
			array_push($this->errorArray,Constants::$emailTaken);
		}
	}

	private function validatePassword($pass1,$pass2){
		if($pass1!=$pass2){
			array_push($this->errorArray,Constants::$passMismatch);
			return;
		}
		if(preg_match("/[^a-zA-Z0-9]/", $pass1)){
			array_push($this->errorArray,Constants::$passAlphaError);
			return;

		}
		if(strlen($pass1)>50 || strlen($pass1)<5){
			array_push($this->errorArray,Constants::$passLengthError);
			return;
		}
	}

	public function getError($errorText){
		if(in_array($errorText,$this->errorArray)){
			return "<span class='errorMessage'>$errorText</span>";
		}

	}

	public function checkLogIn($username,$password){
		$EncryptPassword=hash("sha512",$password);
		$query=$this->db->prepare("SELECT * FROM users WHERE username=:username AND password=:password");
		$query->bindParam(":username",$username);
		$query->bindParam(":password",$EncryptPassword);
		$query->execute();
		if($query->rowCount()==1){
			return true;
		}
		else
		{
			array_push($this->errorArray,Constants::$logInError);
			return false;
		}
	}
	public function updatePassword($oldPassword,$newPassword,$confirmPassword,$username){

		$this->validateOldPassword($oldPassword,$username);
		$this->validatePassword($newPassword,$confirmPassword);

		if(empty($this->errorArray)){
			$query=$this->db->prepare("UPDATE users SET password=:pw WHERE username='$username'");
			$pw=hash("sha512",$newPassword);
			$query->bindParam(":pw",$pw);
			$query->execute();
			return true;
		}else{
			return false;
		}

		

		

	}

	public function validateOldPassword($oldPassword,$username){
		$oldpw=hash("sha512",$oldPassword);
		$query=$this->db->prepare("SELECT * FROM users WHERE username='$username' AND password='$oldpw'");
		$query->execute();
		if($query->rowCount()==0){
			array_push($this->errorArray,Constants::$invalidPassword);
			return false;
		}

	}



}
?>