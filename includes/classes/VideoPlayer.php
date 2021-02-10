<?php
class VideoPlayer{
	private $video;
	public function __construct($video){
		$this->video=$video;
	}
	public function create($autoplay){
		if($autoplay){
			$autoplay="autoplay";
		}
		else
		{
			$autoplay="";
		}
		$filePath=$this->video->getFilepath();
		return "<video class='videoplayer' controls $autoplay>
			<source src='$filePath' type='video/mp4'>
			Not supported by Browser

		</video>";
	}
}


?>