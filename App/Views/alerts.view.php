<div id="page">
    <div id="content">
        <?php foreach ($alerts as $alert):?>
        <p><a href="/sr<?php echo $alert->url;?>" class="alertLink" id="<?php echo $alert->alertid;?>"><?php echo $alert->message;?></a></p>
        <?php endforeach; ?>
    </div>