<!--<canvas id="hiveGraphics" width="3000" height="1000"></canvas>-->
<?php foreach ($hivemodel->layers as $content): ?>
    <div style="<?php echo $content->getStyleString(); ?>" class="hiveContentBox">
        <svg width="270" height="125" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink">
        <g>
        <rect width="270" height="125" style="fill: #EEE;"/>
        <a xlink:href="#">
            <text class="contentOwner" x="0" y="15"><?php echo $content->getOwner()->handle; ?></text>
        </a>
        <?php $content->display(); ?>
        </g>
        </svg>
    </div>
    <!--    <div style="<?php echo $content->getStyleString(); ?>" class="hiveContentBox <?php if ($content->isPseudoRoot): ?>root<?php else: ?>child<?php endif; ?>" id="<?php echo $content->contentid; ?>">
            <div class="contentOwner"><a href="#"><?php echo $content->getOwner()->handle; ?></a></div>
            <div class="contentModified"><?php echo date('Y-m-d h:i:s', strtotime($content->modified)); ?></div>
            <div class="expandContent"></div>
    <?php $content->display(); ?>
            <div class="commentCount"><span><?php echo $content->childCount(); ?></span></div>
        </div>-->
<?php endforeach; ?>
