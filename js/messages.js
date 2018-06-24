"use strict";
var messages = document.getElementById('messages');
//Первое попавшийся элемент с сообщением для клонирования
var message = document.querySelector('.message').cloneNode(true);
//номер последнего сообщения берём из атрибута последнего узла
var lastMessageNum = messages.lastElementChild.getAttribute('data-msg_num') || 0;

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
			//если есть что обновлять
			if(reply != '' && reply != 'no'){
				try {
					var obj = JSON.parse(reply);
					lastMessageNum = +obj[obj.length - 1].id_msg; //номер последнего принятого сообщения
					
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
				} catch(e) {
					
				}
			}

		}
	});
}