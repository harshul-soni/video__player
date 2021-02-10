<?php
class Video{
	private $db,$userObj,$sqlData;
	public function __construct($db,$input,$userObj){
		$this->db=$db;
		$this->userObj=$userObj;

		if(is_array($input)){
			$this->sqlData=$input;
		}
		else
		{
			$query=$this->db->prepare("SELECT * FROM videos WHERE id=:id");
			$query->bindParam(":id",$input);
			$query->execute();
			$this->sqlData=$query->fetch(PDO::FETCH_ASSOC);
		}
	}

	public function getId(){
		return $this->sqlData["id"];
	}
	public function getUploadedBy(){
		return $this->sqlData["uploadedBy"];

	}
	public function getTitle(){
		return $this->sqlData["title"];
	}
	public function getDescription(){
		return $this->sqlData["description"];
	}
	public function getPrivacy(){
		return $this->sqlData["privacy"];
	}
	public function getFilepath(){
		return $this->sqlData["filePath"];
	}
	public function getCategory(){
		return $this->sqlData["category"];
	}
	public function getUploadDate(){
		$date= $this->sqlData["uploadDate"];
		return date("M j , Y",strtotime($date));
	}
	public function getViews(){
		return $this->sqlData["views"];
	}
	public function getDuration(){
		return $this->sqlData["duration"];
	}

	public function getThumbnail(){
		$query=$this->db->prepare("SELECT * FROM thumbnails WHERE videoId=:videoId AND selected=1");
		$query->bindParam(":videoId",$videoId);
		$videoId=$this->getId();
		$query->execute();
		$path=$query->fetch(PDO::FETCH_ASSOC);
		return $path["filePath"];
	}
	public function incrementViews(){
		$query=$this->db->prepare("UPDATE videos SET views=views+1 WHERE id=:id");
		$videoId=$this->getId();
		$query->bindParam(":id",$videoId);
		$query->execute();
		$this->sqlData["views"]=$this->sqlData["views"] +1 ;

	}

	public function getLikes(){
		$query=$this->db->prepare("SELECT count(*) as 'count' FROM likes WHERE videoId=:videoId");
		$videoId=$this->getId();
		$query->bindParam(":videoId",$videoId);
		$query->execute();
		$data=$query->fetch(PDO::FETCH_ASSOC);
		return $data["count"];
	}
	public function getDislike(){
		$query=$this->db->prepare("SELECT count(*) as 'count' FROM dislikes WHERE videoId=:videoId");
		$videoId=$this->getId();
		$query->bindParam(":videoId",$videoId);
		$query->execute();
		$data=$query->fetch(PDO::FETCH_ASSOC);
		return $data["count"];
	}

	public function like(){
		$videoId=$this->getId();
		$username=$this->userObj->getUsername();

		if($this->wasLikedBy()){

			$query=$this->db->prepare("DELETE FROM likes WHERE username=:username AND videoId=:videoId");
			$query->bindParam(":username",$username);
			$query->bindParam(":videoId",$videoId);
			$query->execute();


			
			$data=array();
			$likes=-1;
			$dislikes=0;
			array_push($data,["likes" => $likes, "dislikes" => $dislikes ]);
			$json=json_encode($data);
			echo $json;
			
			
		}
		else
		{
				$deleteQuery=$this->db->prepare("DELETE FROM dislikes WHERE username=:username AND videoId=:videoId");
				$deleteQuery->bindParam(":username",$username);
				$deleteQuery->bindParam(":videoId",$videoId);
				$deleteQuery->execute();
				$count=$deleteQuery->rowCount();

			$query=$this->db->prepare("INSERT INTO likes (username,videoId) VALUES (:username,:videoId)");
			$query->bindParam(":username",$username);
			$query->bindParam(":videoId",$videoId);
			$query->execute();

			$data=array();
			$likes=1;
			$dislikes=0;
			array_push($data,["likes" => $likes, "dislikes" => $dislikes - $count ]);
			$json=json_encode($data);
			echo $json;

		}



		

	}

	public function wasLikedBy(){
		$videoId=$this->getId();
		$username=$this->userObj->getUsername();
		$checkQuery=$this->db->prepare("SELECT * FROM likes WHERE username=:username AND videoId=:videoId");
		$checkQuery->bindParam(":username",$username);
		$checkQuery->bindParam(":videoId",$videoId);
		$checkQuery->execute();
		return $checkQuery->rowCount();

	}


	public function wasDisLikedBy(){
		$videoId=$this->getId();
		$username=$this->userObj->getUsername();
		$checkQuery=$this->db->prepare("SELECT * FROM dislikes WHERE username=:username AND videoId=:videoId");
		$checkQuery->bindParam(":username",$username);
		$checkQuery->bindParam(":videoId",$videoId);
		$checkQuery->execute();
		return $checkQuery->rowCount();

	}

	public function disLike(){
		$videoId=$this->getId();
		$username=$this->userObj->getUsername();

		if($this->wasDisLikedBy()){

			$query=$this->db->prepare("DELETE FROM dislikes WHERE username=:username AND videoId=:videoId");
			$query->bindParam(":username",$username);
			$query->bindParam(":videoId",$videoId);
			$query->execute();


			$data=array();
			$likes=0;
			$dislikes=-1;
			array_push($data,["likes" => $likes, "dislikes" => $dislikes ]);
			$json=json_encode($data);
			echo $json;
			
			
		}
		else
		{
				$deleteQuery=$this->db->prepare("DELETE FROM likes WHERE username=:username AND videoId=:videoId");
				$deleteQuery->bindParam(":username",$username);
				$deleteQuery->bindParam(":videoId",$videoId);
				$deleteQuery->execute();
				$count=$deleteQuery->rowCount();

			$query=$this->db->prepare("INSERT INTO dislikes (username,videoId) VALUES (:username,:videoId)");
			$query->bindParam(":username",$username);
			$query->bindParam(":videoId",$videoId);
			$query->execute();

			$data=array();
			$likes=0;
			$dislikes=1;
			array_push($data,["likes" => $likes -$count, "dislikes" => $dislikes ]);
			$json=json_encode($data);
			echo $json;

		}



		

	}

	public function getNumComments(){
		$query=$this->db->prepare("SELECT * FROM comments WHERE videoId=:videoId");
		$videoId=$this->getId();
		$query->bindParam(":videoId",$videoId);
		$query->execute();
		return $query->rowCount();
	}

	public function getComments(){
		$query=$this->db->prepare("SELECT * FROM comments WHERE videoId=:videoId AND responseTo=0 ORDER BY datePosted DESC" );
		$videoId=$this->getId();
		$query->bindParam(":videoId",$videoId);
		$query->execute();
		$comments=array();

		while($row=$query->fetch(PDO::FETCH_ASSOC)){
			$comment=new Comment($videoId,$this->userObj,$this->db,$row);
			array_push($comments,$comment);
		}
		return $comments;
	}


}


?>