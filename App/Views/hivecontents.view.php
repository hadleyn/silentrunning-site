<canvas id="hiveGraphics" width="900" height="400"></canvas>
<?php foreach ($hivemodel->layers as $content): ?>
    <div style="<?php echo $content->getStyleString();?>" class="hiveContentBox <?php if ($content->isPseudoRoot):?>root<?php else: ?>child<?php endif;?>" id="<?php echo $content->contentid; ?>">
        <div class="contentOwner"><a href="#"><?php echo $content->getOwner()->handle;?></a></div>
        <div class="contentModified"><?php echo date('Y-m-d h:i:s', strtotime($content->modified));?></div>
        <div class="expandContent"></div>
        <?php $content->display(); ?>
        <div class="commentCount"><span><?php echo $content->childCount();?></span></div>
    </div>
<?php endforeach; ?>
