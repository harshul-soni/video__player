<?php

require_once("ButtonProvider.php");
require_once("User.php");

class commentControls{
	private $db;
	private $comment;
	private $userObj;
	public function __construct($db,$comment,$userObj){
		$this->db=$db;
		$this->comment=$comment;
		$this->userObj=$userObj;
	}
	public function create(){
		$replyBtn=$this->replyBtn();
		$likesCount=$this->createLikesCount();
		$replySection=$this->createReplySection();
		$likeBtn=$this->createLikeBtn();
		$disLikeBtn=$this->createDislikeBtn();
		return "<div class='controls'>
			$replyBtn
			$likesCount
			$likeBtn
			$disLikeBtn
		</div>
		$replySection";
	}
	private function replyBtn(){
		$text="REPLY";
		$action="toggleReply(this)";
		return ButtonProvider::createButton($text,$action,null,"replyBtn");


	}
	private function createLikesCount(){
		$text=$this->comment->getLikes();
		if($text==0){
			$text="";
		}
		return "<span class='likesCount'>$text</span>";

	}
	private function createReplySection(){
			$postedBy=$this->userObj->getUsername();
			$videoId=$this->comment->getVideoId();
			$commentId=$this->comment->getId();

			$profilepic=ButtonProvider::getProfileButton($this->db,$postedBy);

			$cancelAction="toggleReply(this)";
			$cancelButton=ButtonProvider::createButton("Cancel",$cancelAction,null,"cancelComment");
			
			$postAction="postComment(this,$videoId,\"$postedBy\",\"repliesSection\",$commentId)";
			$postButton=ButtonProvider::createButton("Reply",$postAction,null,"postComment");


			return "<div class='commentForm hidden'>
						$profilepic
						<textarea class='commentBody' placeholder='Add comment'></textarea>
						$cancelButton
						$postButton
					</div>";


	}

	private function createLikeBtn(){
		$videoId=$this->comment->getVideoId();
		$commentId=$this->comment->getId();
		$action="likeComment($commentId,this,$videoId)";
		$class="likeBtn";
		$img="assets/images/icons/thumb-up.png";

		if($this->comment->wasLikedBy()){
			$img="assets/images/icons/thumb-up-active.png";
		}
		return ButtonProvider::createButton("",$action,$img,$class);

	}
	private function createDislikeBtn(){

		$commentId=$this->comment->getId();

		$videoId=$this->comment->getVideoId();
		$action="disLikeComment($commentId,this,$videoId)";
		$class="disLikeBtn";
		$img="assets/images/icons/thumb-down.png";
		if($this->comment->wasDisLikedBy()){
			$img="assets/images/icons/thumb-down-active.png";
		}
		return ButtonProvider::createButton("",$action,$img,$class);

	}

}

?>