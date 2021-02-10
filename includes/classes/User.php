<?php
class User{
	private $db;
	private $sqlData;
	public function __construct($db,$username){
		$this->db=$db;
		$query=$this->db->prepare("SELECT * FROM users WHERE username=:username");
		$query->bindParam(":username",$username);
		$query->execute();
		$this->sqlData=$query->fetch(PDO::FETCH_ASSOC);
	}

	public static function isLoggedIn(){
		return isset($_SESSION["userLoggedIn"]);
	}

	public function getUsername(){
		return $this->sqlData["username"];
	}
	public function getName(){
		return $this->sqlData["firstName"]. " " . $this->sqlData["lastName"];

	}
	public function getFirstName(){
		return $this->sqlData["firstName"];
	}
	public function getLastName(){
		return $this->sqlData["lastName"];
	}
	public function getEmail(){
		return $this->sqlData["email"];
	}
	public function getProfilePic(){
		return $this->sqlData["profilePic"];
	}
	public function getSignUpdate(){
		return $this->sqlData["signUpdate"];
	}
	public function isSubscribedTo($userTo){
		$query=$this->db->prepare("SELECT * FROM subscribers WHERE userTo=:userTo AND userFrom = :userFrom ");
		$query->bindParam(":userTo",$userTo);
		$username=$this->getUsername();
		$query->bindParam(":userFrom",$username);
		$query->execute();
		return $query->rowCount() > 0;

	}
	public function subscriberCount(){
		$query=$this->db->prepare("SELECT * FROM subscribers WHERE userTo=:userTo");
		$username=$this->getUsername();
		$query->bindParam(":userTo",$username);
		$query->execute();
		return $query->rowCount();

	}
	public function getSubscription(){
		$query=$this->db->prepare("SELECT userTo FROM subscribers WHERE userFrom=:userFrom");
		$query->bindParam(":userFrom",$userFrom);
		$userFrom=$this->getUsername();
		$query->execute();

		$subs=array();
		while($row=$query->fetch(PDO::FETCH_ASSOC)){
			$user=new User($this->db,$row["userTo"]);
			array_push($subs,$user);
		}
		return $subs;

	}
}


?>