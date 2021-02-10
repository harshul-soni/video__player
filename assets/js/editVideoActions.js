function setNewThumbnail(thumbnailId,videoId,thumbnail){
	$.post("ajax/editVideoThumbnail.php",{ thumbnailId:thumbnailId , videoId:videoId }).done(function(data){

		var item=$(thumbnail);
		var itemClass=item.attr("class");

		$("." + itemClass).removeClass("selected");
		item.addClass("selected");
		alert(data);


	})
}