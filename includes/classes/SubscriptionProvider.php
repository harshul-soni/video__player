<?php
class SubscriptionProvider{
	private $db,$userObj;
	public function __construct($db,$userObj){
		$this->db=$db;
		$this->userObj=$userObj;
	}
	public function getVideos(){
		$videos=array();
		$subscription=$this->userObj->getSubscription();

		if(sizeof($subscription)>0){

			$condition="";
			$i=0;
			while($i < sizeof($subscription)){
				if($i==0){
					$condition.="WHERE uploadedBy=?";
				}else{
					$condition.="OR uploadedBy=?";
				}

				$i++;

			}

			$videoquery="SELECT * FROM videos $condition ORDER BY uploadDate DESC";
			$query=$this->db->prepare($videoquery);
			$i=1;

			foreach ($subscription as $subs) {
				$query->bindParam("$i",$username);
				$username=$subs->getUsername();
				$i++;
				
			}

			$query->execute();
			while($row=$query->fetch(PDO::FETCH_ASSOC)){
				$video=new Video($this->db,$row,$this->userObj);
				array_push($videos,$video);
			}



		}


		return $videos;
	}
}


?>