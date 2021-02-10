<?php
class ProfileData{
	private $db,$profileUserObj;
	public function __construct($db,$profileUserName){
		$this->db=$db;
		$this->profileUserObj=new User($db,$profileUserName);
	}

	public function getProfileUsername(){
		return $this->profileUserObj->getUsername();

	}

	public function getProfileObj(){
		return $this->profileUserObj;
	}

	public function userExist(){
		$query=$this->db->prepare("SELECT * FROM users WHERE username=:username");
		$username=$this->getProfileUsername();
		$query->bindParam(":username",$username);
		$query->execute();
		if($query->rowCount()>0){
			return true;
		}
		else
		{
			return false;
		}

	}
	public function getCoverPhoto($dir){
		$files=glob($dir. '/*.jpg');
		$file=array_rand($files);
		return $files[$file];
	}

	public function getProfileFullUsername(){
		return $this->profileUserObj->getName();
	}

	public function getProfilePic(){
		return $this->profileUserObj->getProfilePic();
	}

	public function subscriberCount(){
		return $this->profileUserObj->subscriberCount();
	}

	public function getVideos(){
		$query=$this->db->prepare("SELECT * FROM videos WHERE uploadedBy=:username ORDER BY uploadDate DESC");
		$query->bindParam(":username",$username);
		$username=$this->getProfileUsername();
		$query->execute();

		$videos=array();

		while($row=$query->fetch(PDO::FETCH_ASSOC)){
				$video=new Video($this->db,$row,$this->profileUserObj);
				array_push($videos,$video);
		}
		return $videos;
	}

	public function totalViews(){
		$query=$this->db->prepare("SELECT SUM(views) FROM videos WHERE uploadedBy=:username");
		$query->bindParam(":username",$username);
		$username=$this->getProfileUsername();
		$query->execute();
		return $query->fetchColumn();
		
	}
	public function signUpDate(){
		$date=$this->profileUserObj->getSignUpdate();
		return date("F j Y",strtotime($date));
	}
}

?>