"use strict";
$(document).ready(function(){
	setInterval(refreshMessages, 300);
});

function refreshMessages(){
	$.ajax({
		type: "POST",
		url: 'index.php',
		data : {receive: true},
		success: function (d) {
			//console.log(d);
			var obj = $.parseJSON(d); // = JSON.parse(d)
				
			//Узел со всеми сообщениями
			var messages = document.getElementById('messages');
			//Первое попавшийся узел с сообщением для клонирования
			var message = document.querySelector('.message').cloneNode(true);
				
			//Затираем исходные сообщения
			while (messages.firstChild) {
				messages.removeChild(messages.firstChild);
			}

			//Показываем новые, обновляем
			for (var i = 0; i < obj.length; i++){
				var messageNew = message.cloneNode(true);
				messageNew.querySelector('.message__user').innerHTML = obj[i].user;
				messageNew.querySelector('.message__text').innerHTML = obj[i].message;
				messageNew.querySelector('.message__time').innerHTML = obj[i].date_msg;
				messages.appendChild(messageNew);
			}
		}
	});
}