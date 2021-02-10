function postComment(button,videoId,postedBy,commentContainer,replyTo){
	var textarea=$(button).siblings("textarea");
	var text=textarea.val();
	
	textarea.val("");

	if(text){
		$.post("ajax/postComment.php",{videoId:videoId,postedBy:postedBy,replyTo:replyTo,text:text}).done(function(data){

			if(!replyTo){
				$("." +commentContainer).prepend(data);	
			}
			else
			{	
				$(button).parent().siblings("." + commentContainer).append(data);

			}
			

		});

	}
	else{
		alert("Cannnot post empty comment");
	}
}

function toggleReply(button){
	var parent=$(button).closest(".itemContainer");
	var commentForm=parent.find(".commentForm").first();
	commentForm.toggleClass("hidden");
}

function likeComment(commentId,button,videoId){
	$.post("ajax/likeComment.php",{commentId:commentId,videoId:videoId}).done(function(data){


		var likeBtn=$(button);
		var disLikeBtn=$(button).siblings(".disLikeBtn");
		likeBtn.addClass("active");
		disLikeBtn.removeClass("active");
		
		var likesCount=$(button).siblings(".likesCount");
		updateLikes(likesCount,data);

		

		if(data < 0 ){
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

function disLikeComment(commentId,button,videoId){
	$.post("ajax/disLikeComment.php",{commentId:commentId,videoId:videoId}).done(function(data){


		var disLikeBtn=$(button);
		var likeBtn=$(button).siblings(".likeBtn");
		disLikeBtn.addClass("active");
		likeBtn.removeClass("active");
		
		var likesCount=$(button).siblings(".likesCount");
		updateLikes(likesCount,data);
	
		

		if(data < 0 ){
			disLikeBtn.removeClass("active");
			disLikeBtn.find("img").attr("src","assets/images/icons/thumb-down-active.png");

		}
		else
		{
			disLikeBtn.find("img").attr("src","assets/images/icons/thumb-down.png");
		}
		likeBtn.find("img").attr("src","assets/images/icons/thumb-up.png")
		
		

	});

}


function updateLikes(element,num){
	var likesCount=element.text() || 0;
	element.text(parseInt(likesCount) + parseInt(num) );

}

function getRepliesSec(commentId,videoId,button){
	$.post("ajax/getCommentReplies.php",{commentId:commentId,videoId:videoId}).done(function(data){

		var replies=$("<div>").addClass("repliesSection");
		replies.append(data);
		$(button).replaceWith(replies);

	})
}