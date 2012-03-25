<?php foreach ($colonyLinks as $cl): ?>
    <p id="colonyLink_<?php echo $cl->linkid ?>">
        <a id="<?php echo $cl->linkid; ?>" class="delete deleteColonyLink" href="#">&times;</a>
        <input readonly="readonly" type="text" size="100" value="<?php echo 'http://' . HOST . BASEPATH . '/colony/addLink/' . $cl->link_key; ?>"/>
        <span>Expires: <?php echo $cl->getExpires();?></span>
    </p>
<?php endforeach; ?>