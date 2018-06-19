<div id="messages">
	<?foreach($messages as $message):?>
		<div class="message"><div class="message__user"><?=$message['user'];?></div><div class="message__time"><?=$message['date_msg'];?></div><div class="message__text"><?=$message['message'];?></div></div>
		<div class="cls"></div>
	<?endforeach;?>
</div>
<script src="js/messages.js"></script>