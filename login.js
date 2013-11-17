
$(document).ready(function() {
// inicio uma requisi�‹o
	$("#SignInButton").click(
			function(){
				askIfLoginIsOk();	
			}
	);
	$('#logForm input').keydown(function(e) {
	    if (e.keyCode == 13) {
	    	$("#SignInButton").click();
	    }
	});
	$('.top_lis').click(function(){
			fetchPages($(this).attr("id"));
	});
	
});

function fetchPages(top_lis_id){
	var page_request;
	if(top_lis_id == "li_prod")	page_request= "searchProduct.php";
	else if(top_lis_id == "li_invoice") page_request="searchInvoice.php";
	else
		return;
	
	
	$.ajax({
        url : page_request,
        dataType : "html",
        data : {},
        success : function(data){
        	$("#mainDiv").html(data);
        },
        
        error: function(jqXHR, exception) {
            if (jqXHR.status === 0) {
                alert('Not connect.\n Verify Network.');
            } else if (jqXHR.status == 404) {
                alert('Requested page not found. [404]');
            } else if (jqXHR.status == 500) {
                alert('Internal Server Error [500].');
            } else if (exception === 'parsererror') {
                alert('Requested JSON parse failed.');
            } else if (exception === 'timeout') {
                alert('Time out error.');
            } else if (exception === 'abort') {
                alert('Ajax request aborted.');
            } else {
                alert('Uncaught Error.\n' + jqXHR.responseText);
            }
        }
    });
}


function askIfLoginIsOk(){
	
	var emailField= $("#emailInput");
	var email=emailField.val();
	var pw= $("#pwInput").val();
	var params= new Array();
	params[0]= new Array("Email", new Array(email), "equal");
	params[1]= new Array("Password", new Array(pw), "equal");
	
	console.log(params);
	$.ajax({
        url : "loginAjax.php",
        dataType : "json",
        data : {"params":params},
        success : function(data){
           if($.isEmptyObject(data))alert("Invalid user and/or password.");
           else $('#logForm').submit();
           var i=0;
        },
	
	
		error: function(jqXHR, exception) {
            if (jqXHR.status === 0) {
                alert('Not connect.\n Verify Network.');
            } else if (jqXHR.status == 404) {
                alert('Requested page not found. [404]');
            } else if (jqXHR.status == 500) {
                alert('Internal Server Error [500].');
            } else if (exception === 'parsererror') {
                alert('Requested JSON parse failed.');
            } else if (exception === 'timeout') {
                alert('Time out error.');
            } else if (exception === 'abort') {
                alert('Ajax request aborted.');
            } else {
                alert('Uncaught Error.\n' + jqXHR.responseText);
            }
        }
    });//termina o ajax	
}



function loggedIn(){	
	$(document).ready(function() {
		// inicio uma requisi�‹o
		$(".logged").css('visibility', 'visible');
		});
}