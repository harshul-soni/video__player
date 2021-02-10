<?php
require_once("includes/classes/VideoInfoControls.php");
class VideoInfo{
	private $db,$video,$userObj;
	public function __construct($db,$video,$userObj){
		$this->db=$db;
		$this->video=$video;
		$this->userObj=$userObj;
	}
	public function create(){
		return $this->getPrimaryInfo().$this->getSecondaryInfo();

	}
	private function getPrimaryInfo(){
		$title=$this->video->getTitle();
		$views=$this->video->getViews();
		$VideoInfoControls=new VideoInfoControls($this->video,$this->userObj);
		$controls=$VideoInfoControls->create();
		return "<div class='videoInfo'>
			 <h1>$title</h1>

			  <div class='bottomSection'>
			  	<span class='viewCount'>$views Views</span>
			  	$controls
			  </div>

		</div>";

	}
	private function getSecondaryInfo(){
		$description=$this->video->getDescription();
		$uploadDate=$this->video->getUploadDate();
		$uploadedBy=$this->video->getuploadedBy();
		$profileButton=ButtonProvider::getProfileButton($this->db,$uploadedBy);

		if($uploadedBy==$this->userObj->getUsername()){
			$actionButton=ButtonProvider::editVideoButton($this->video->getId());
		}
		else
		{
			$userToObj=new User($this->db,$uploadedBy);
			$actionButton=ButtonProvider::createSubscriberBtn($this->db,$this->userObj,$userToObj);
		}

		return "<div class='secondaryInfo'>
			<div class='topRow'>
				$profileButton
					<div class='uploadInfo'>
						<span class='owner'>
							<a href='profile.php?username=$uploadedBy'>
								$uploadedBy
							</a>
						</span>
						<span class='date'>
							Published on $uploadDate

						</span>
					</div>
					$actionButton
			
			</div>
				<div class='descriptionContainer'>
					$description
				</div>
		</div>";
		
	}
}


?>