<?php 

	class VideoGrid{
		private $db,$userLoggedInObj;
		private $largerMode=false;
		private $gridClass="videoGrid";
		public function __construct($db,$userLoggedInObj){
			$this->db=$db;
			$this->userLoggedInObj=$userLoggedInObj;


		}

		public function create($videos,$title,$showFilter){

			if($videos==null){
				$gridItems=$this->generateItems();

			}else{
				$gridItems=$this->generateItemsFromVideos($videos);

			}

			$header="";
			if($title !=null){
				$header=$this->generateHeader($title,$showFilter);

			}
			return "$header
			<div class='$this->gridClass'>
				$gridItems

			</div>";
		}

		public function generateItems(){
			$query=$this->db->prepare("SELECT * FROM videos ORDER BY RAND() LIMIT 15");
			$query->execute();
			$htmlElements="";
			while($row=$query->fetch(PDO::FETCH_ASSOC)){
				$video=new Video($this->db,$row,$this->userLoggedInObj);
				$item=new VideoGridItem($video,$this->largerMode);
				$htmlElements.=$item->create();
			}
			return $htmlElements;

		}

		public function generateItemsFromVideos($videos){
			$elementsHtml="";

			foreach($videos as $video){
				$videoItem=new VideoGridItem($video,$this->largerMode);
				$elementsHtml.=$videoItem->create();
			}
			return $elementsHtml;

		}

		public function generateHeader($title,$showFilter){
			$filter="";
			if($showFilter){
				$link="http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$urlArray=parse_url($link);
				$query=$urlArray["query"];

				parse_str($query,$params);
		
				unset($params["orderBy"]);
				
				$newQuery=http_build_query($params);
				$newUrl=basename($_SERVER["PHP_SELF"]). "?".$newQuery;
				$filter="<div class='right'>
					<span>Order By:</span>
					<a href='$newUrl&orderBy=uploadDate'>Upload Date</a>
					<a href='$newUrl&orderBy=views'>Most Viewed</a>
				</div>";

			}
			
			return "<div class='videoGridHeader'>
				<div class='left'>
					$title
				</div>
				$filter
			</div>";

		}

		public function createLarge($videos,$title,$showFilter){
			$this->gridClass.=" large";
			$this->largerMode=true;
			return $this->create($videos,$title,$showFilter);

		}



}
?>