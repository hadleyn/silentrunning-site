<!--<canvas id="hiveGraphics" width="3000" height="1000"></canvas>-->
<svg id="hiveGraphics" width="3000" height="1000" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink">

</svg>
<?php foreach ($hivemodel->layers as $content): ?>
    <div id="<?php echo $content->contentid; ?>" style="<?php echo $content->getStyleString(); ?>" class="hiveContentBox <?php if ($content->isPseudoRoot): ?>root<?php else: ?>child<?php endif; ?>">
        <svg width="270" height="125" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink">
            <g>
                <rect width="270" height="125" style="fill: #EEE;"/>
                <a xlink:href="#">
                    <text class="addComment" x="190" y="15">add comment</text>
                </a>
                <a xlink:href="#">
                    <text class="contentOwner" x="0" y="15"><?php echo $content->getOwner()->handle; ?></text>
                </a>
                <text class="contentModified" x="0" y="27"><?php echo date('Y-m-d h:i:s', strtotime($content->modified)); ?></text>
                <?php $content->display(); ?>
                <a xlink:href="#">
                    <text class="commentCount" x="250" y="115"><?php echo $content->childCount(); ?></text>
                </a>
                <?php if ($content->isPseudoRoot): ?>
                    <a xlink:href="#">
                        <text class="closeComments" x="0" y="115">&times;</text>
                    </a>
                <?php endif; ?>
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
