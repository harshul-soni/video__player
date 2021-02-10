<?php

class NavigationMenuProvider{
	private $db,$userObj;
	public function __construct($db,$userObj){
		$this->db=$db;
		$this->userObj=$userObj;
	}
	public function create(){

		$menuHtml=$this->createNavItem("Home","assets/images/icons/home.png","index.php");
		$menuHtml.=$this->createNavItem("Trending","assets/images/icons/trending.png","trending.php");
		$menuHtml.=$this->createNavItem("Subscriptions","assets/images/icons/subscriptions.png","subscriptions.php");
		$menuHtml.=$this->createNavItem("Liked Videos","assets/images/icons/thumb-up.png","likedVideos.php");

		if(User::isLoggedIn()){
			$menuHtml.=$this->createNavItem("Settings","assets/images/icons/settings.png","settings.php");
			$menuHtml.=$this->createNavItem("Logout","assets/images/icons/logout.png","logout.php");
			$menuHtml.=$this->createSubscriptionSection();

		}

		
		return "<div class='navigationsItems'>
			$menuHtml
		</div>";

	}
	private function createNavItem($text,$icon,$link){

		return "<div class='navigationsItem'>
			<a href='$link'>
				<img src='$icon'>
				<span>$text</span>
			</a>

		</div>";

	}

	private function createSubscriptionSection(){
		$subscriptions=$this->userObj->getSubscription();
		$html="<span class='subscriptionsHeader'>Subscriptions</span>";
		foreach($subscriptions as $subs) {
			$username=$subs->getUsername();
			$html.=$this->createNavItem($username,$subs->getProfilePic(),"profile.php?username=$username");
			
		}
		return $html;
	} 
}


?>