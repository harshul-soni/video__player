$(document).ready(function(){
	$(".navShowhide").on("click",function(){

		var main=$("#mainsectioncontainer");
		var nav=$("#sidenavcontainer");
		if(main.hasClass("leftPadding")){
			nav.hide();
		}
		else
		{
			nav.show();
		}
		main.toggleClass("leftPadding");
		


	});
});

function notSignedIn(){
	alert("You are not signed In ..Please Sign In First");

}