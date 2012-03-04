<div id="page">
    <div id="content">
        <div id="welcomebox">
            <h3>Log into the hive</h3>
            <?php $errorHelper->showErrors(); ?>
            <div id="loginform">
                <form action="/hive/login" method="post">
                    <input type="text" id="handle" name="handle" placeholder="handle"/>
                    <input type="password" id="password" name="password" placeholder="password"/>
                    <input type="submit" value="authenticate"/>
                </form>
            </div>
            <div id="registerform">
                <form method="post" action="hive/register">
                    <input type="text" id="registerHandle" name="registerHandle" placeholder="handle"/>
                    <input type="password" id="registerPassword" name="registerPassword" placeholder="password"/>
                    <input type="password" id="registerPasswordConf" name="registerPasswordConf" placeholder="password confirm"/>
                    <div id="captcha">
                        <?php
                        echo recaptcha_get_html(Configuration::read('captcha_key'));
                        ?>

                    </div>
                    <input type="submit" id="registerSubmit" value="Register"/>
                </form>

            </div>
        </div>
    </div>
