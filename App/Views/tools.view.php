<div id="page">
    <div id="content">
        <h3>Tools</h3>
        <h4>Create colony link</h4>
        <input type="text" placeholder="expires on" readonly="readonly" id="colonyLinkExpires"/><input type="button" id="createColonyLink" value="build link"/>
        <input type="text" id="colonyLink" value=""/>
        <h4>Existing Colony Links</h4>
        <div id="colonyLinksList">
            <?php echo $colonyLinksList;?>
        </div>
        <hr/>
        <h4>Manage Alert Preferences</h4>
        <p>
            <input type="checkbox" <?php if (isset($alertPreferences[Configuration::read('new_comment')])):?> checked="checked" <?php endif;?> class="alertPreference" name="newComment" id="newComment" value="<?php echo Configuration::read('new_comment'); ?>"/>
            <label for="newComment">Alerts for new comments on content.</label>
        </p>
    </div>