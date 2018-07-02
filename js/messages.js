"use strict";
var messages = document.getElementById('messages');
//Первое попавшийся элемент с сообщением для клонирования
var message = document.querySelector('.message').cloneNode(true);
//номер последнего сообщения берём из атрибута последнего узла
var lastMessageNum = messages.lastElementChild.getAttribute('data-msg_num') || 0;

$(document).ready(function(){
	setInterval(refreshMessages, 1000);
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
					var arr = JSON.parse(reply);
					lastMessageNum = +arr[arr.length - 1].id_msg; //номер последнего принятого сообщения
					
					//Добавляем новые в конец
					for (var i = 0; i < arr.length; i++){
						var messageNew = message.cloneNode(true);
						messageNew.setAttribute('data-msg_num', arr[i].id_msg);
						messageNew.querySelector('.message__user').innerHTML = arr[i].user;
						messageNew.querySelector('.message__text').innerHTML = arr[i].message;
						messageNew.querySelector('.message__time').innerHTML = arr[i].date_msg;
						//немного анимации
						messageNew.style.display = 'none';
						messages.appendChild(messageNew);
						$(messageNew).slideDown(500);
					}
					if (messages.children.length >= 50){ //на случай если мало сообщений в БД
						//И удаляем столько старых сколько пришло новых
						for (var i = 0; i < arr.length; i++){
							messages.removeChild(messages.children[0]);
						}
					}

				} catch(e) {
					
				}
			}

		}
	});
}