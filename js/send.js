"use strict";
var elForm = document.forms.send;
var elUser = elForm.elements.user;
var elMessage = elForm.elements.message;


$(document).ready(function(){	
	elForm.onsubmit = function(e){
		if (checkSend(elUser.value, elMessage.value))
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
			} else {
				//console.log(reply);
			}
		}
	});	
}

function checkSend(user, message) {
	var errors = [];
	user = user.trim();
	message = message.trim();
	
	if (!user)
		errors.push('Нет имени пользователя');
	if (!message)
		errors.push('Нет сообщения');

	//HTML
	var regexpHTML = /[<\/][a-zA-Z]{1,10}[>]+/;
	if (regexpHTML.test(user) || regexpHTML.test(message)){

		errors.push('Теги запрещены');
	}
		
	if (errors.length > 0){
		$('.popup__text').html(errors.join('<br>\n'));
		$('#popup').fadeIn(100);
		$('#popup').delay(1500).fadeOut(300);
		return false;
	}
	return true;
}