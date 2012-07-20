<div id="page">
    <div id="content">
        <div class="messages">
            <?php $messageHelper->showMessages();?>
        </div>
        <?php foreach ($alerts as $alert):?>
        <p><i><?php echo date('M d, Y h:i:s a', strtotime($alert->timestamp));?></i> <a href="/sr<?php echo $alert->url;?>" class="alertLink" id="<?php echo $alert->alertid;?>"><?php echo $alert->message;?></a></p>
        <?php endforeach; ?>
    </div>