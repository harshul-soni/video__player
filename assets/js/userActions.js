function subscribe(userTo,userFrom,button){
	if(userTo==userFrom){
		alert("Cannot subscribe to yourself ");
		return ;
	}
	$.post("ajax/subscribe.php",{userTo: userTo , userFrom:userFrom}).done(function(data){
		console.log(data);
		if(data!=null){
			$(button).toggleClass("subscribe unsubscribe");
			var buttonText=$(button).hasClass("subscribe") ? "SUBSCRIBE " : "SUBSCRIBED" ;
			$(button).text(buttonText + " " + data);

		}
		else
		{
			alert("something went wrong");
		}
	});
}