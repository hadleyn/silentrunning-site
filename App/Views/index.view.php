<div id="page">
    <div id="content">
        <h3>Welcome to silentrunning, a major project under development</h3>
        <p>
            silentrunning is both my new social web application and the framework that runs it.
        </p>
        <img src="Webroot/images/me_bridge.JPG" alt="Your Host" width="300" height="200" title="A long bridge" rel="lightbox"/>
        <h3>Log into the hive</h3>
        <div>
            <form action="/hive/login" method="post">
                <input type="text" id="handle" name="handle" placeholder="handle"/>
                <input type="password" id="password" name="password" placeholder="passcode"/>
            </form>
        </div>
        <div>
            <form method="post" action="/hive/register">
                <div id="captcha">
                    <?php
                    echo recaptcha_get_html(Configuration::read('captcha_key'));
                    ?>
                </div>
            </form>

        </div>
    </div>
