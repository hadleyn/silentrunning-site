<?php foreach ($hivemodel->layers as $content): ?>
    <div style="top: <?php echo $content->getY();?>px; left: <?php echo $content->getX();?>px; z-index: <?php echo $content->getZ();?>; opacity: <?php echo $content->getOpacity();?>;" class="hiveContentBox" id="<?php echo $content->contentid; ?>">
        <div class="contentOwner"><a href="#"><?php echo $content->getOwner()->handle;?></a></div><div class="contentModified"><?php echo date('Y-m-d h:i:s', strtotime($content->modified));?></div>
        <?php $content->display(); ?>
    </div>
<?php endforeach; ?>
