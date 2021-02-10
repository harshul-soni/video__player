<?php

	class uploadVideoData{
		public $videoDataArray,$title,$description,$privacy,$categories,$uploadedBy;
		public function __construct ($videoDataArray,$title,$description,$privacy,$categories,$uploadedBy){
			$this->videoDataArray=$videoDataArray;
			$this->title=$title;
			$this->description=$description;
			$this->privacy=$privacy;
			$this->categories=$categories;
			$this->uploadedBy=$uploadedBy;


		}


		public function updateDetails($db,$videoId){
			$query=$db->prepare("UPDATE videos SET title=:title , description=:description , category=:category, privacy=:privacy WHERE id=:videoId");
			$query->bindParam(":title",$this->title);
			$query->bindParam(":description",$this->description);
			$query->bindParam(":privacy",$this->privacy);
			$query->bindParam(":category",$this->category);
			$query->bindParam(":videoId",$videoId);
			 $query->execute();
			 if($query->execute()){
			 	return true;
			 }else{
			 	return false;
			 }
		}
	}

?>