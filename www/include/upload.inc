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
                <div>
                    <ul class="nav">
                        <li class="active"><a href="#">Hello, <?php echo $session->user->username; ?></a></li>
                        <li><a href="/index.php">duel</a></li>
                        <li><a href="/logout.php">logout</a></li>
                    </ul>
                </div>
                <?php
                if (isset($session->errorMessage)) {
                    echo <<<EOT
                <div class="error">{$session->errorMessage}</div>
EOT;
                    unset($session->errorMessage);
                }

                if (isset($session->infoMessage)) {
                    echo <<<EOT
                <div class="info">{$session->infoMessage}</div>
EOT;

                    unset($session->infoMessage);
                }
                ?>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>

<div class="container">

    <!-- Example row of columns -->
    <div class="row">
        <div class="fileupload">
            <h3>Upload your image.  Keep it under <?php echo $kb; ?> kilobytes, and don't upload anything you wouldn't
                want your sweet old grandmother to see, unless of course she's into that kind of stuff, in which case
                nevermind.  Just don't post anything that will get us in trouble with the law, mkay?</h3>
            <form method="POST" action="/upload.php" enctype="multipart/form-data">
                <input type="hidden" name="csrf" value="<?php echo $session->csrf; ?>" />
                <p><input type="file" class="btn" name="image" placeholder="select an image..." /></p>
                <p><input type="submit" class="btn" value="upload image" /></p>
            </form>
        </div>
    </div>

    <hr>

    <footer>
        <p>&copy; imgduel <?php echo date('Y'); ?></p>
    </footer>

</div> <!-- /container -->