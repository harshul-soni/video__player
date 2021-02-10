<?php
require_once("ButtonProvider.php");
require_once("commentControls.php");

	class Comment{
		private $videoId,$userLoggedObj,$db,$sqlData;
		public function __construct($videoId,$userLoggedObj,$db,$input){


			$this->db=$db;

			if(!is_array($input)){
				$query=$this->db->prepare("SELECT * FROM comments WHERE id=:id");
				$query->bindParam(":id",$input);
				$query->execute();

				$input=$query->fetch(PDO::FETCH_ASSOC);
			}
			$this->sqlData=$input;
			$this->videoId=$videoId;
			$this->userLoggedObj=$userLoggedObj;


		}

		public function getId(){
			return $this->sqlData["id"];
		}
		public function getVideoId(){
			return $this->sqlData["videoId"];
		}

		public function wasLikedBy(){
		$id=$this->getId();
		$username=$this->userLoggedObj->getUsername();
		$checkQuery=$this->db->prepare("SELECT * FROM likes WHERE username=:username AND commentId=:commentId");
		$checkQuery->bindParam(":username",$username);
		$checkQuery->bindParam(":commentId",$id);
		$checkQuery->execute();
		return $checkQuery->rowCount();

		}
		public function time_elapsed_string($datetime, $full = false) {
		    $now = new DateTime;
		    $ago = new DateTime($datetime);
		    $diff = $now->diff($ago);

		    $diff->w = floor($diff->d / 7);
		    $diff->d -= $diff->w * 7;

		    $string = array(
		        'y' => 'year',
		        'm' => 'month',
		        'w' => 'week',
		        'd' => 'day',
		        'h' => 'hour',
		        'i' => 'minute',
		        's' => 'second',
		    );
		    foreach ($string as $k => &$v) {
		        if ($diff->$k) {
		            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
		        } else {
		            unset($string[$k]);
		        }
		    }

		    if (!$full) $string = array_slice($string, 0, 1);
		    return $string ? implode(', ', $string) . ' ago' : 'just now';
		}


		public function wasDisLikedBy(){
			$id=$this->getId();
			$username=$this->userLoggedObj->getUsername();
			$checkQuery=$this->db->prepare("SELECT * FROM dislikes WHERE username=:username AND commentId=:commentId");
			$checkQuery->bindParam(":username",$username);
			$checkQuery->bindParam(":commentId",$id);
			$checkQuery->execute();
			return $checkQuery->rowCount();

		}


		public function create(){
			$id=$this->getId();
			$videoId=$this->getVideoId();

			$body=$this->sqlData["body"];
			$postedBy=$this->sqlData["postedBy"];
			$profileButton=ButtonProvider::getProfileButton($this->db,$postedBy);
			$time=$this->time_elapsed_string($this->sqlData["datePosted"]);
			$commentControls=new commentControls($this->db,$this,$this->userLoggedObj);
			$comments=$commentControls->create();

			$numResponses=$this->getReplies();
			if($numResponses >0){
				$viewRepliesText="<span class='repliesSection viewReplies' onclick='getRepliesSec($id,$videoId,this)'>
					View all $numResponses replies
				</span>";
			}else{
				$viewRepliesText="<div class='repliesSection'></div>";
			}

			return "<div class='itemContainer'>
				<div class='comment'>
					$profileButton
						<div class='mainContainer'>
							<div class='commentHeader'>
								<a href='profile.php?username=$postedBy'>
									<span class='username'>$postedBy</span>
								</a>
								<span class='time'>$time</span>
							</div>

							<div class='body'>
								$body
							</div>
						</div>
				</div>
				$comments
				$viewRepliesText

			</div>";

		}

		public function getReplies(){
			$query=$this->db->prepare("SELECT count(*) as 'count' FROM comments WHERE responseTo=:responseTo");
			$query->bindParam(":responseTo",$id);
			$id=$this->sqlData["id"];
			$query->execute();
			$data= $query->fetch(PDO::FETCH_ASSOC);
			return $data["count"];
		}

		public function getLikes(){
			$query=$this->db->prepare("SELECT COUNT(*) as 'count' FROM likes WHERE commentId=:commentId");
			$commentId=$this->getId();
			$query->bindParam(":commentId",$commentId);
			$query->execute();
			$data=$query->fetch(PDO::FETCH_ASSOC);

			$numLikes=$data["count"];


			$query=$this->db->prepare("SELECT COUNT(*) as 'count' FROM dislikes WHERE commentId=:commentId");
			$commentId=$this->getId();
			$query->bindParam(":commentId",$commentId);
			$query->execute();
			$data=$query->fetch(PDO::FETCH_ASSOC);

			$numDisLikes=$data["count"];

			return $numLikes-$numDisLikes;
		}

		public function like(){
		$id=$this->getId();
		$username=$this->userLoggedObj->getUsername();

		if($this->wasLikedBy()){

			$query=$this->db->prepare("DELETE FROM likes WHERE username=:username AND commentId=:commentId");
			$query->bindParam(":username",$username);
			$query->bindParam(":commentId",$id);
			$query->execute();

			return -1;
			
			
		}
		else
		{
				$deleteQuery=$this->db->prepare("DELETE FROM dislikes WHERE username=:username AND commentId=:commentId");
				$deleteQuery->bindParam(":username",$username);
				$deleteQuery->bindParam(":commentId",$id);
				$deleteQuery->execute();
				$count=(int)$deleteQuery->rowCount();

			$query=$this->db->prepare("INSERT INTO likes (username,commentId) VALUES (:username,:commentId)");
			$query->bindParam(":username",$username);
			$query->bindParam(":commentId",$id);
			$query->execute();

			return 1 + $count;

		}



		

	}

	public function disLike(){
		$id=$this->getId();
		$username=$this->userLoggedObj->getUsername();

		if($this->wasDisLikedBy()){

			$query=$this->db->prepare("DELETE FROM dislikes WHERE username=:username AND commentId=:commentId");
			$query->bindParam(":username",$username);
			$query->bindParam(":commentId",$id);
			$query->execute();


			return 1;
			
			
		}
		else
		{
				$deleteQuery=$this->db->prepare("DELETE FROM likes WHERE username=:username AND commentId=:commentId");
				$deleteQuery->bindParam(":username",$username);
				$deleteQuery->bindParam(":commentId",$id);
				$deleteQuery->execute();
				$count=(int)$deleteQuery->rowCount();

			$query=$this->db->prepare("INSERT INTO dislikes (username,commentId) VALUES (:username,:commentId)");
			$query->bindParam(":username",$username);
			$query->bindParam(":commentId",$id);
			$query->execute();

			return -1 -$count;


		}



		

	}

	public function getRepliesSec(){
		$query=$this->db->prepare("SELECT * FROM comments WHERE responseTo=:commentId ORDER BY datePosted ASC" );
		$Id=$this->getId();
		$query->bindParam(":commentId",$Id);
		$query->execute();
		$comments="";
		$videoId=$this->getVideoId();

		while($row=$query->fetch(PDO::FETCH_ASSOC)){
			$comment=new Comment($videoId,$this->userLoggedObj,$this->db,$row);
			$comments.=$comment->create();
		}
		return $comments;
	}




} 
?>