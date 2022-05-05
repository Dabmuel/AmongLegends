<div class="login-page">
    <?php if(SingletonRegistry::$registry['Controller']->errorMessage) {echo('
    <div class="error-message">
        <p>'.SingletonRegistry::$registry['Controller']->errorMessage.'</p>
    </div>
    '); }?>
    <form class="form" action="" method="post">
        <h4 class="title">Connecte toi pd</h4>
        <label class="input-text-label" for="nickname">Nickname :</label>
        <input class="input-text" type="text" name="nickname" id="nickname" value="<?php echo($_SESSION['nickname']) ?>"/>
        <br/>
        <?php
            if ($_GET["party"]) {
                echo('<input class="button" type="submit" value="Rejoindre">');
            } else {
                echo('<input class="button" type="submit" value="Créer une partie">');
            }
        ?>
        <?php
        if (SingletonRegistry::$registry['SessionManager']->currentSessionDTO) {
            echo('<a class="button" href="'
                . Config::$baseUrl
                . '/party">Reprendre</a>');
        }
        ?>
    </form>
</div>