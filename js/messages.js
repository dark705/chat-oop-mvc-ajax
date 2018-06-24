"use strict";
var lastMessageNum = 0;
$(document).ready(function(){
	setInterval(refreshMessages, 300);
});

function refreshMessages(){
	var send = Object.create(null);
	send.last = lastMessageNum;
	send = JSON.stringify(send);
	$.ajax({
		type: "POST",
		url: 'index.php',
		data : {receive: send},
		success: function (reply) {
			//console.log(reply);
			if(reply != 'no'){
				var obj = JSON.parse(reply); // = JSON.parse(reply)
				lastMessageNum = +obj[obj.length - 1].id_msg; //номер последнего принятого сообщения
				//Узел со всеми сообщениями
				var messages = document.getElementById('messages');
				//Первое попавшийся элемент с сообщением для клонирования
				var message = document.querySelector('.message').cloneNode(true);

				//Затираем все исходные сообщения
				/*
				while (messages.firstChild) {
					messages.removeChild(messages.firstChild);
				}
				*/

				//Добавляем новые в конец
				for (var i = 0; i < obj.length; i++){
					var messageNew = message.cloneNode(true);
					messageNew.setAttribute('data-msg_num', obj[i].id_msg);
					messageNew.querySelector('.message__user').innerHTML = obj[i].user;
					messageNew.querySelector('.message__text').innerHTML = obj[i].message;
					messageNew.querySelector('.message__time').innerHTML = obj[i].date_msg;
					//немного анимации
					messageNew.style.display = 'none';
					messages.appendChild(messageNew);
					$(messageNew).slideDown(500);
				}
				
				//И удаляем столько старых сколько пришло новых
				for (var i = 0; i < obj.length; i++){
					messages.removeChild(messages.children[0]);
				}
			} else {
				//нет нового
			}
			

		}
	});
}