function likeVideo(button,videoId){
	$.post("ajax/likeVideo.php",{videoId:videoId}).done(function(data){


		var likeBtn=$(button);
		var disLikeBtn=$(button).siblings(".disLikeBtn");
		likeBtn.addClass("active");
		disLikeBtn.removeClass("active");
		var result=JSON.parse(data);

		updateLikes(likeBtn.find(".text"),result[0].likes);
		updateLikes(disLikeBtn.find(".text"),result[0].dislikes);

		if(result[0].likes<0){
			likeBtn.removeClass("active");
			likeBtn.find("img").attr("src","assets/images/icons/thumb-up.png");

		}
		else
		{
			likeBtn.find("img").attr("src","assets/images/icons/thumb-up-active.png");
		}
		disLikeBtn.find("img").attr("src","assets/images/icons/thumb-down.png")
		
		

	});
}

function disLikeVideo(button,videoId){
	$.post("ajax/disLikeVideo.php",{videoId:videoId}).done(function(data){


		var disLikeBtn=$(button);
		var likeBtn=$(button).siblings(".likeBtn");
		disLikeBtn.addClass("active");
		likeBtn.removeClass("active");
		var result=JSON.parse(data);

		updateLikes(likeBtn.find(".text"),result[0].likes);
		updateLikes(disLikeBtn.find(".text"),result[0].dislikes);

		if(result[0].dislikes<0){
			disLikeBtn.removeClass("active");
			disLikeBtn.find("img").attr("src","assets/images/icons/thumb-down.png");

		}
		else
		{
			disLikeBtn.find("img").attr("src","assets/images/icons/thumb-down-active.png");
		}
		likeBtn.find("img").attr("src","assets/images/icons/thumb-up.png");
		
		

	});
}


function updateLikes(element,num){
	var likesCount=element.text() || 0;
	element.text(parseInt(likesCount) + parseInt(num) );

}