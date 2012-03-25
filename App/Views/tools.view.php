<div id="page">
    <div id="content">
        <h3>Tools</h3>
        <p>Create colony link</p>
        <input type="text" placeholder="expires on" readonly="readonly" id="colonyLinkExpires"/><input type="button" id="createColonyLink" value="build link"/>
        <input type="text" id="colonyLink" value=""/>
        <h4>Existing Colony Links</h4>
        <div id="colonyLinksList">
            <?php echo $colonyLinksList;?>
        </div>
    </div>