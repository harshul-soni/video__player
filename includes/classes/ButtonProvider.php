<?php 
class ButtonProvider{

	public static $notSignedIn="notSignedIn()";

	public static function createLink($link){
		return User::isLoggedIn() ? $link : ButtonProvider::$notSignedIn;
	}
	public static function createButton($text,$action,$imgSrc,$class){

		$image=($imgSrc==null)? "" : "<img src='$imgSrc'>";

		$action=ButtonProvider::createLink($action);

		return "<button class='$class' onclick='$action'>
		$image

		<span class='text'>$text</span>

		</button>";

	}

	public static function getProfileButton($db,$username){
		$userObj=new User($db,$username);
		$profilePic=$userObj->getProfilePic();

		return "<div class='profileButton'>
			<a href='profile.php?username='$username'>
				<img src='$profilePic' class='profilePic'>
			</a>
		</div>";

	}

	public static function editVideoButton($videoId){
		$href="editVideo.php?videoId=$videoId";
		$button=ButtonProvider::createEditBtn("EDIT VIDEO", $href, null, "edit button");
		return "<div class='editVideoButtonContainer'>
					$button
			</div>";
	}
	public static function createEditBtn($text,$href,$imgSrc,$class){

		$image=($imgSrc==null)? "" : "<img src='$imgSrc'>";

		return "
		<a href='$href'>
			<button class='$class'>
				$image

				<span class='text'>$text</span>

			</button>
		</a>";

	}
	public static function createSubscriberBtn($db,$userloggedInObj,$userToObj){
		$userTo=$userToObj->getUsername();
		$userloggedIn=$userloggedInObj->getUsername();

		$isSubscribedTo=$userloggedInObj->isSubscribedTo($userTo);
		$buttonText= $isSubscribedTo ? "SUBSCRIBED" : "SUBSCRIBE";
		$buttonText .= " " . $userloggedInObj->subscriberCount();
		$buttonClass=$isSubscribedTo ? "unsubscribe button" : "subscribe button";
		$action ="subscribe(\"$userTo\",\"$userloggedIn\",this)";

		$button =ButtonProvider::createButton($buttonText,$action,null,$buttonClass);

		return "<div class='subscribeButtonContainer'>
			$button
		</div>";


	}

	public static function createUserProfileButton($db,$username){
		if(User::isLoggedIn()){
			return ButtonProvider::getProfileButton($db,$username);

		}else{
			return "<a href='signIn.php'>
				<span class='signInText'>SIGN IN</span>
			</a>";
		}
	}


	
}
?>