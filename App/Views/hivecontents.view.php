<?php foreach ($hivemodel->hiveContent as $content): ?>
    <div style="top: <?php echo rand(0, 275);?>px; left: <?php echo rand(0, 600);?>px" class="hiveContentBox" id="<?php echo $content->contentid; ?>"><?php $content->display(); ?></div>
<?php endforeach; ?>
