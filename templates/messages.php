<div id="messages">
	<?foreach($messages as $message):?>
		<div class="message" data-msg_num="<?=$message['id_msg'];?>">
			<div class="message__user"><?=$message['user'];?></div>
			<div class="message__time"><?=$message['date_msg'];?></div>
			<div class="message__text"><?=$message['message'];?></div>
		</div>
	<?endforeach;?>
</div>
<script src="js/messages.js"></script>