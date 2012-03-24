<body>
    <div id="wrapper">
        <div id="header-wrapper">
            <div id="header">
                <div id="logo">
                    <h1><a href="<?php echo Configuration::read('basepath'); ?>"><span>silent</span>running</a></h1>
                </div>

                <div id="menu">

                    <ul>
                        <li><div id="menuLeftBlock"></div><a href="<?php echo BASEPATH;?>/hive" title="hive">Hive</a></li>
                        <?php if ($userLoggedIn): ?>
                            <li><a href="<?php echo BASEPATH; ?>/about" title="about">comm link</a></li>
                            <li><a href="<?php echo BASEPATH; ?>/tools" title="tools">tools</a></li>
                            <li><a href="<?php echo BASEPATH; ?>/hive/logout">exit</a></li>
                        <?php endif; ?>
                    </ul>
                    <div id="menuRightBlock"></div>
                </div>
            </div>
        </div>