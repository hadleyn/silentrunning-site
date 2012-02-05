<html xmlns="http://www.w3.org/1999/xhtml">
    
    <body>
        <div id="wrapper">
            <div id="header-wrapper">
                <div id="header">
                    <div id="logo">
                        <h1><a href="<?php echo base_url(); ?>"><span>silent</span>running</a></h1>
                    </div>
                    <div id="menu">
                        <ul>
                            <li><?php echo anchor('about', 'comm link'); ?></li>
                            <li><?php echo anchor('whiteboard', 'whiteboard'); ?></li>
                            <?php if ($this->session->userdata('authenticated') === TRUE): ?>
                                <li><?php echo anchor('login/logout', 'punch out'); ?></li>
                                <li><?php echo anchor('certificatecontrol', 'certificates'); ?></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>