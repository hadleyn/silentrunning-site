<div id="page">
    <div id="content">
        <?php foreach ($alerts as $alert):?>
        <p><?php echo $alert->message;?></p>
        <?php endforeach; ?>
    </div>