<?php 
require_once("ProfileData.php");

class ProfileGenerator{
	public $db,$userObj,$profileData;
	public function __construct($db,$userObj,$profileUsername){
		$this->db=$db;
		$this->userObj=$userObj;
		$this->profileData=new ProfileData($db,$profileUsername);
	}

	public function create(){
		$profileUsername=$this->profileData->getProfileUsername();
		if(!$this->profileData->userExist()){
			return "User does not exist";
		}

		$profileCover=$this->createProfileCover();
		$headerSection=$this->createHeaderSection();
		$tabSection=$this->createTabSection();
		$contentSection=$this->createContentSection();

		return "<div class='profileContainer'>
			$profileCover
			$headerSection
			$tabSection
			$contentSection
		</div>";
		

	}

	public function createProfileCover(){
		$imgSrc=$this->profileData->getCoverPhoto("assets/images/coverPhotos");
		$fullName=$this->profileData->getProfileFullUsername();
		return "<div class='coverPhotoContainer'>
			<img src='$imgSrc' class='coverImage'>
			<span class='userName'>$fullName</span>

		</div>";


	}

	public function createHeaderSection(){
		$imgSrc=$this->profileData->getProfilePic();
		$name=$this->profileData->getProfileFullUsername();
		$subsCount=$this->profileData->subscriberCount();
		$subsBtn=$this->createSubscribeButton();
		return "<div class='profileHeader'>
			<div class='userInfoContainer'>
				<img src='$imgSrc' class='profileImage'>
					<div class='userInfo'>
						<span class='title'>$name</span>
						<span class='subscriberCount'>$subsCount Subscribers</span>
					</div>
			</div>

			<div class='buttonContainer'>
				<div class='buttonItem'>
					$subsBtn
				</div>
			</div>

		</div>";

	}

	public function createTabSection(){

		return "<ul class='nav nav-tabs' role='tablist'>
				  <li class='nav-item'>
				    <a class='nav-link active' id='videos-tab' data-toggle='tab' href='#videos' role='tab' aria-controls='videos' aria-selected='true'>Videos</a>
				  </li>
				  <li class='nav-item'>
				    <a class='nav-link' id='about-tab' data-toggle='tab' href='#about' role='tab' aria-controls='about' aria-selected='false'>About</a>
				  </li>
				</ul>";

	}

	public function createContentSection(){

		$videos=$this->profileData->getVideos();
		if(sizeof($videos)>0){
			$videoGrid=new VideoGrid($this->db,$this->userObj);
			$videoGridHtml=$videoGrid->create($videos,null,false);
		}else{
			$videoGridHtml="<span>This user has no videos</span>";
		}


		$aboutSection=$this->createAboutSection();




		return "<div class='tab-content channel'>
					  <div class='tab-pane fade show active' id='videos' role='tabpanel' aria-labelledby='videos-tab'>
					  	$videoGridHtml 

					  </div>
					  <div class='tab-pane fade' id='about' role='tabpanel' aria-labelledby='about-tab'>
					  	$aboutSection
					  </div>
					  
				</div>";

	}

	private function createSubscribeButton(){
		if($this->userObj->getUsername()==$this->profileData->getProfileUsername()){
			return "";
		}else{
			return ButtonProvider::createSubscriberBtn($this->db,$this->userObj,$this->profileData->getProfileObj());
		}
	}

	private function createAboutSection(){
		$username=$this->profileData->getProfileUsername();
		$name=$this->profileData->getProfileFullUsername();
		$subs=$this->profileData->subscriberCount();
		$totalViews=$this->profileData->totalViews();
		$signUpDate=$this->profileData->signUpDate();
		$html="<div class='section'>
						<div class='title'>
							<span>Details</span>
						</div>
					<div class='values'>
						<span>Name: $name</span>
						<span>Username: $username</span>
						<span>Subscribers: $subs</span>
						<span>Total Views: $totalViews</span>
						<span>Sign Up Date: $signUpDate </span>
					</div>
				</div>

					";

		return $html;
	}







}
?>