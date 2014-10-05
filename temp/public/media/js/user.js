var User = {

	run : function(){
		
		$("#formSignUp").on("submit", function(event){
			event.preventDefault();
			User.checkIfExist();
		});

	},
	
	
	checkIfExist : function(){
		
		$('#alertEmailExist','#formSignUp').hide();
		var email = $('#email','#formSignUp').val();
		console.log(email);
		if(email){
			var request = $.ajax({
				  url: "/user/check-if-exist",
				  type: "POST",
				  data: {email : email},
				  dataType: "json"
				});
				 
				request.done(function(data) {
					if(data == 1){
						$('#alertEmailExist','#formSignUp').show();
					}
					else{
						$("#formSignUp").unbind('submit').submit()
					}
				});
				 
				request.fail(function(jqXHR, textStatus) {
				  alert( "Request failed: " + textStatus );
				});
		}
		
			
	}
		
		
		
		
};

$(document).ready(function() {
	User.run();
});
