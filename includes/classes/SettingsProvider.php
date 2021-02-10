<?php 

	class SettingsProvider{
		


		public function createUploadForm($firstName,$lastName,$email){

			$firstName=$this->getfirstName($firstName);
			$lastName=$this->getlastName($lastName);
			$email=$this->getemail($email);
			$saveUserBtn=$this->createSaveUserButton();
			return "<form action='settings.php' method='POST' enctype='multipart/form-data'>
				<span class='title'>User Details</span>
				$firstName
				$lastName
				$email
				$saveUserBtn

				</form>";

		}

		public function createPasswordForm(){
			$oldPassword=$this->createPasswordInput("oldPassword","Old Password");
			$newPassword=$this->createPasswordInput("newPassword","New Password");
			$confirmPassword=$this->createPasswordInput("confirmPassword","Confirm Password");
			$savePasswordBtn=$this->savePasswordBtn();
			return "<form action='settings.php' method='POST' enctype='multipart/form-data'>
				<span class='title'>Update Password</span>
				$oldPassword
				$newPassword
				$confirmPassword
				$savePasswordBtn

				</form>";

		}

		

		private function getfirstName($value){
			if($value==null) $value="";
			return "<div class='form-group'>
				<input class='form-control' type='text' placeholder='First Name' name='firstName' required value='$value'>
			</div>";
			
		}
		private function getlastName($value){
			if($value==null) $value="";

			return "<div class='form-group'>
				<input class='form-control' type='text' placeholder='Last Name' name='lastName' required value='$value'>
			</div>";
			
		}
		private function getemail($value){
			if($value==null) $value="";
			return "<div class='form-group'>
				<input class='form-control' type='email' placeholder='Email' name='email' required value='$value'>
			</div>";
			
		}

	

		private function createSaveUserButton(){
			return "<button type='submit' class='uploadButton' name='saveUserBtn' title='Save'>
			<img src='assets/images/icons/upload.png'>
			Save


			</button>";
		}


		private function createPasswordInput($name ,$placeholder){

			return "<div class='form-group'>
				<input class='form-control' type='password' placeholder='$placeholder' name='$name' required >
			</div>";

		}

		private function savePasswordBtn(){

			return "<button type='submit' class='uploadButton' name='savePasswordBtn' title='Save'>
			<img src='assets/images/icons/upload.png'>
			Save


			</button>";

		}




	}
?>