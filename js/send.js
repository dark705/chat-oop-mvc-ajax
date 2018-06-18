"use strict";
var elForm = document.forms.send;
var elUser = elForm.elements.user;
var elMessage = elForm.elements.message;
	
$(document).ready(function(){	
	elForm.onsubmit = function(e){
		send(elUser.value, elMessage.value);
		return false;
	};

});


function send(user, message){
	var send = Object.create(null);
	send.user = user;
	send.message = message;
	send = JSON.stringify(send);

	$.ajax({
		type: "POST",
		url: 'index.php',
		data : {transmit: send},
		success: function (reply) {
			if (reply == 'success'){
				elMessage.value = '';
			}
		}
	});
	
}