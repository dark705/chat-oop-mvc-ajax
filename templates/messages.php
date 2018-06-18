<ul id="messages">
	<?foreach($messages as $message):?>
		<li class="message"><span class="message__user"><?=$message['user'];?></span>: <span class="message__text"><?=$message['message'];?></span><span class="message__time"><?=$message['date_msg'];?></span></li>
	<?endforeach;?>
</ul>
<script src="js/messages.js"></script>