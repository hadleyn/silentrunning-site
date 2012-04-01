<div id="page">
    <div id="content">
        <p>Welcome to the hive!</p>
        <div id="messages">
            <?php echo $messageHelper->showMessages(); ?>
        </div>
        <div id="ccForm"><?php echo $contentCreationForm; ?></div>
        
        <div id="hiveDisplay">
            <?php echo $hiveContent; ?>
        </div>
        <div id="depthSlider"></div>
    </div>