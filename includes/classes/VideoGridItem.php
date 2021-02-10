<?php 

	class VideoGridItem{

		private $video,$largeMode;

		public function __construct($video,$largeMode){
			$this->video=$video;
			$this->largeMode=$largeMode;
		}

		public function create(){
			$thumbnail=$this->createThumbnail();
			$details=$this->getDetails();
			$url="watch.php?id=".$this->video->getId();
			return "<a href=$url>
				<div class='videoGridItem'>
					$thumbnail
					$details

				</div>
			</a>";
		}

		private function createThumbnail(){
			$path=$this->video->getThumbnail();
			$duration=$this->video->getDuration();

			return "<div class='thumbnail'>
				<img src=$path>
				<div class='duration'>
					<span>$duration</span>
				</div>
			</div>";
		}
		private function getDetails(){
			$title=$this->video->getTitle();
			$username=$this->video->getUploadedBy();
			$views=$this->video->getViews(). " views";
			$date=$this->video->getUploadDate();
			$description=$this->createDescription();
			return "<div class='details'>
				<h3 class='title'>$title</h3>
				<span class='username'>$username</span>
				<div class='stats'>
					<span class='viewsCount'>$views -</span>
					<span class='date'>$date</span>
				</div>
				$description
			</div>";
		}

		private function createDescription(){
			if(!$this->largeMode){
				return "";
			}else{
				$description=$this->video->getDescription();
				$description=(strlen($description) >350) ? substr($description,0,347) . "..." : $description;
				return "<span class='description'>$description</span>";
			}
			
		}






}
?>