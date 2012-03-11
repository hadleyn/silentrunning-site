<?php foreach ($contentBlocks as $content): ?>
    <div class="hiveContentBox" id="<?php echo $content->contentid; ?>"><?php echo $content->content_data; ?></div>
<?php endforeach; ?>
