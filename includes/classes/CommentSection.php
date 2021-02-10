<?php

	class CommentSection {
		private $db,$video,$userObj;
		public function __construct($db,$video,$userObj){
			$this->db=$db;
			$this->video=$video;
			$this->userObj=$userObj;
		}

		public function create(){
			return $this->createCommentSection();

		}
		private function createCommentSection(){
			$numComments=$this->video->getNumComments();
			$postedBy=$this->userObj->getUsername();
			$videoId=$this->video->getId();

			$profilepic=ButtonProvider::getProfileButton($this->db,$postedBy);
			$action="postComment(this,$videoId,\"$postedBy \",\"comments \",null)";

			$commentButton=ButtonProvider::createButton("COMMENT",$action,null,"postComment");

			$comments=$this->video->getComments();
			$commentItems="";
			foreach ($comments as $comment) {
				$commentItems .= $comment->create();
			}


			return "<div class='commentSection'>
				<div class='header'>
					<span class='commentCount'>$numComments Comments</span>

					<div class='commentForm'>
						$profilepic
						<textarea class='commentBody' placeholder='Add comment'></textarea>
						$commentButton
					</div>
					<div class='comments'>
						$commentItems
					</div>
				</div>




			</div>";

		}
	}


?>