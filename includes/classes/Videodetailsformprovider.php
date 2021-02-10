<?php 

	class Videodetailsformprovider{
		private $db;

		public function __construct($db){
			$this->db=$db;
		}


		public function createUploadForm(){
			$fileinput=$this->getInput();
			$titleinput=$this->getTitle(null);
			$getDes=$this->getDescription(null);
			$privacyInput=$this->privacyInput(null);
			$categoriesInput=$this->createCategories(null);
			$uploadButton=$this->createUploadButton();
			return "<form action='processing.php' method='POST' enctype='multipart/form-data'>
				$fileinput
				$titleinput
				$getDes
				$privacyInput
				$categoriesInput
				$uploadButton




				</form>";

		}

		public function createDetailsForm($video){
			
			$titleinput=$this->getTitle($video->getTitle());
			$getDes=$this->getDescription($video->getDescription());
			$privacyInput=$this->privacyInput($video->getPrivacy());
			$categoriesInput=$this->createCategories($video->getCategory());
			$saveButton=$this->createSaveButton();
			return "<form method='POST'>
				
				$titleinput
				$getDes
				$privacyInput
				$categoriesInput
				$saveButton




				</form>";

		}

		private function getInput(){
			
			return "<div class='form-group'>
			    <label for='exampleFormControlFile1'>Your File</label>
			    <input type='file'  class='form-control-file' id='exampleFormControlFile1' name='inputFile'>
			  </div>";
		}

		private function getTitle($value){
			if($value==null) $value='';
			return "<div class='form-group'>
				<input class='form-control' value='$value' type='text' placeholder='Title' name='inputTitle'>
			</div>";
			
		}
		private function getDescription($value){
			if($value==null) $value='';
			return " <div class='form-group'>
					<textarea class='form-control' id='exampleFormControlTextarea1' placeholder='Description' rows='3' name='inputDescription'>$value</textarea>
			</div>";
		}

		private function privacyInput($value){
			if($value==null) $value='';

			$privateSelected=($value==0) ? "selected='selected'" : "";
			$publicSelected=($value==1) ? "selected='selected'" : "";
			return "<div class='form-group'>
					<select class='form-control' name='privacyInput'>
						  <option value='0' $privateSelected>Private</option>
						  <option value='1' $publicSelected>Public</option>
					</select>


				</div>";
		}
		private function createCategories($value){
			if($value==null) $value='';

				$html="<div class='form-group'>
					<select class='form-control' name='categoriesInput'>";
					$query=$this->db->prepare("SELECT * FROM categories");
					$query->execute();
					while($row=$query->fetch()){
						$name=$row['name'];
						$id=$row['id'];
						$selected=($value==$id) ? "selected='selected'" : "";
						$html.="<option value='$id' selected> $name</option>";
					}


				$html.="</select></div>";
				return $html;
				
		}

		private function createUploadButton(){
			return "<button type='submit' class='uploadButton' name='uploadButton' title='Upload'>
			<img src='assets/images/icons/upload.png'>
			Upload


			</button>";
		}

		private function createSaveButton(){
			return "<button type='submit' class='uploadButton' name='saveButton' title='Save'>
			<img src='assets/images/icons/upload.png'>
			Update


			</button>";

		}




	}
?>