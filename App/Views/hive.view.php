<div id="page">
    <div id="content">
        <p>Welcome to the hive!</p>
        <div id="ccForm"><?php echo $contentCreationForm; ?></div>
        <div id="hiveDisplay">
            <?php foreach ($contentBlocks as $content): ?>
                <div class="hiveContentBox" id="<?php echo $content->contentid; ?>"><?php echo $content->content_data; ?></div>
            <?php endforeach; ?>
        </div>
    </div>