<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="/">imgduel</a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container">

    <!-- Example row of columns -->
    <div class="row">
        <div class="span4">
            <h2>Sign Up</h2>
            <?php
            if (isset($session->errorMessage)) {
                echo <<<EOT
            <div class="error">{$session->errorMessage}</div>
EOT;
                unset($session->errorMessage);
            }
            ?>
            <form method="POST" action="signup.php" enctype="application/x-www-form-urlencoded">
                <input type="hidden" name="challenge" type="hidden" value="<?php echo $session->challenge; ?>" />
                <p>
                    <input type="text" name="email" placeholder="email address" />
                </p>
                <p>
                    <input type="text" name="username" placeholder="user name" />
                </p>
                <p>
                    <input type="password" name="password" placeholder="password" />
                </p>
                <p>
                    <input type="password" name="confirm" placeholder="confirm password" />
                </p>
                <p>
                    <input type="submit" class="btn" value="create account" />
                </p>
            </form>
        </div>
    </div>

    <hr>

    <footer>
        <p>&copy; imgduel <?php echo date('Y'); ?></p>
    </footer>

</div> <!-- /container -->