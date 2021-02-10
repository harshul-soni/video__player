<?php
	class videoProccessor{
		private $db;
		private $sizeLimit=1000000000;
		private $allowedTypes=array("mp4", "flv", "webm", "mkv", "vob", "ogv", "ogg", "avi", "wmv", "mov", "mpeg", "mpg");
		private $ffmpeg;
		private $ffprobe;
		public function __construct($db){
			$this->db=$db;
			$this->ffmpeg=realpath("ffmpeg/bin/ffmpeg.exe");
			$this->ffprobe=realpath("ffmpeg/bin/ffprobe.exe");

		}
		public function upload($videoUploadData){
			$targetDir="uploads/videos/";
			$videoData=$videoUploadData->videoDataArray;

			$tempFilePath=$targetDir.uniqid().basename($videoData["name"]);
			$tempFilePath=str_replace(" ", "_", $tempFilePath);

			$isvalid=$this->processData($videoData,$tempFilePath);
			if(!$isvalid){
				return false;
			}
			if(move_uploaded_file($videoData["tmp_name"],$tempFilePath)){

				$finalPath=$targetDir.uniqid() . ".mp4";
				if(!$this->insertVideoData($videoUploadData,$finalPath)){
						echo "Insert not successful";
						return false;
				}

				if(!$this->convertVideoTomp4($tempFilePath,$finalPath)){
					echo "Error converting video";
					return false;
				}
				if(!$this->deleteFile($tempFilePath)){
					echo "Error";
					return false;
				}
				if(!$this->generateThumbnails($finalPath)){
					echo "Error";
					return false;
				}
				return true;
				

			}

		}
		private function processData($videoData,$tempFilePath){
			$videoType=pathinfo($tempFilePath,PATHINFO_EXTENSION);
				if(!$this->validSize($videoData)){
					echo "FIle size cannot exceed more than 1000 MB";
					return false;

				}
				elseif(!$this->validType($videoType))
				{
					echo "Invalid FIle type ";
					return false;
				}
				elseif ($this->hasError($videoData)) {
					echo "Error code ".$videoData["error"];
					return false;
					
				}
			
			return true;
			

		}
		private function validSize($videoData){
			return $videoData["size"] <= $this->sizeLimit;
		}
		private function validType($type){
			$lowercased=strtolower($type);
			return in_array($lowercased, $this->allowedTypes);

		}
		private function hasError($videoData){
			return $videoData["error"]!=0;
		}
		private function insertVideoData($videoData,$finalPath){
			$query=$this->db->prepare("INSERT INTO videos(uploadedBy,title,description,privacy,filePath,category) VALUES(:uploadedBy, :title, :description , :privacy, :filePath, :category)");
			$query->bindParam(":uploadedBy",$videoData->uploadedBy);
			$query->bindParam(":title",$videoData->title);
			$query->bindParam(":description",$videoData->description);
			$query->bindParam(":privacy",$videoData->privacy);
			$query->bindParam(":filePath",$finalPath);
			$query->bindParam(":category",$videoData->categories);
			return $query->execute();
			
		}

		public function convertVideoTomp4($tempFilePath,$finalPath){
			$cmd="$this->ffmpeg -i $tempFilePath $finalPath 2>&1";
			$outputLog=array();
			exec($cmd,$outputLog,$returnCode);
				if($returnCode!=0){
					foreach($outputLog as $line){
						echo $line ."<br>";
						return false;

					}
				}
			return true;
		}

		private function deleteFile($filePath){
			if(!unlink($filePath)){
				echo "Could not delete File";
				return false;
			}
			return true;
		}

		public function generateThumbnails($filePath){
			$thumbnailSize="210*118";
			$thumbnailNum=3;
			$pathOfThumbnail="uploads/videos/thumbnails";

			$duration=$this->getVideoDuration($filePath);
			$videoId=$this->db->lastInsertId();
			$this->updateDuration($duration,$videoId);
			for($num=1;$num<=$thumbnailNum;$num++){
				$imageName=uniqid() .".jpg";
				$interval=($duration * 0.8) / $thumbnailNum *$num;
				$fullThumbnailPath="$pathOfThumbnail/$videoId--$imageName";

				$cmd="$this->ffmpeg -i $filePath -ss $interval -s $thumbnailSize -vframes 1 $fullThumbnailPath 2>&1";
				$outputLog=array();
				exec($cmd,$outputLog,$returnCode);
					if($returnCode!=0){
						foreach($outputLog as $line){
							echo $line ."<br>";

						}
					}

				$query=$this->db->prepare("INSERT INTO thumbnails (videoId,filePath,selected) values (:videoId,:filePath ,:selected)");
				$query->bindParam(":videoId",$videoId);
				$query->bindParam(":filePath",$fullThumbnailPath);
				$query->bindParam(":selected",$selected);

				$selected=$num==1 ? 1: 0;
				$success=$query->execute();

				if(!$success){
					echo "error inserting thumbnails";
					return false;
				}
				
			}
			return true;
		}
		public function getVideoDuration($filePath){
			return (int)shell_exec("$this->ffprobe -v error -select_streams v:0 -show_entries stream=duration -of default=noprint_wrappers=1:nokey=1 $filePath");
		}

		private function updateDuration($duration,$videoId){
			$videoId=(int)$videoId;
			$hours = floor($duration / 3600);
	        $mins = floor(($duration - ($hours*3600)) / 60);
	        $secs = floor($duration % 60);


	        
	        $hours = ($hours < 1) ? "" : $hours . ":";
	        $mins = ($mins < 10) ? "0" . $mins . ":" : $mins . ":";
	        $secs = ($secs < 10) ? "0" . $secs : $secs;
	

	        $finalDuration = $hours.$mins.$secs;

			$query=$this->db->prepare("UPDATE videos SET duration=:duration WHERE id=:videoId");
			$query->bindParam(":duration", $finalDuration);
			$query->bindParam(":videoId",$videoId);
			$query->execute();

		}
	}

?>

