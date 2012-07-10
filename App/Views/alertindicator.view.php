<?php $tenchunks = array_chunk($alerts, 10); ?>
<?php foreach ($tenchunks as $tenChunk): ?>
    <?php if (count($tenChunk) == 10): ?>
        <span class="alertIndicatorIcon tenGroup">&block;</span>
    <?php else: ?>
        <?php $fivechunks = array_chunk($tenChunk, 5); ?>
        <?php foreach ($fivechunks as $fiveChunk): ?>
            <?php if (count($fiveChunk) == 5): ?>
                <span class="alertIndicatorIcon fiveGroup">&block;</span>
            <?php else: ?>
                <?php foreach ($fiveChunk as $alert): ?>
                    <span class="alertIndicatorIcon">&block;</span>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endforeach; ?>

