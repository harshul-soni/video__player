<?php

require_once("includes/classes/ButtonProvider.php");

class VideoInfoControls{
	private $video;
	private $userObj;
	public function __construct($video,$userObj){
		$this->video=$video;
		$this->userObj=$userObj;
	}
	public function create(){
		$likeBtn=$this->createLikeBtn();
		$disLikeBtn=$this->createDislikeBtn();
		return "<div class='controls'>
			$likeBtn
			$disLikeBtn
		</div>";
	}
	private function createLikeBtn(){
		$text=$this->video->getLikes();
		$videoId=$this->video->getId();
		$action="likeVideo(this,$videoId)";
		$class="likeBtn";
		$img="assets/images/icons/thumb-up.png";

		if($this->video->wasLikedBy()){
			$img="assets/images/icons/thumb-up-active.png";
		}
		return ButtonProvider::createButton($text,$action,$img,$class);

	}
	private function createDislikeBtn(){
		$text=$this->video->getDislike();
		$videoId=$this->video->getId();
		$action="disLikeVideo(this,$videoId)";
		$class="disLikeBtn";
		$img="assets/images/icons/thumb-down.png";
		if($this->video->wasDisLikedBy()){
			$img="assets/images/icons/thumb-down-active.png";
		}
		return ButtonProvider::createButton($text,$action,$img,$class);

	}

}

?>