<div id="page">
    <div id="content">
        <div id="messages">
            <?php echo $messageHelper->showMessages(); ?>
        </div>
        <div id="ccForm"><?php echo $contentCreationForm; ?></div>
        <div id="hiveDisplayWrapper">
            <div id="hiveDisplay">
                <?php echo $hiveContent; ?>
            </div>
        </div>
        <div id="depthSlider"></div>
        <?php echo $addCommentForm; ?>
    </div>