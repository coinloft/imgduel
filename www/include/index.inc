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
                    <li><a href="/signup.php">signup</a></li>
                </ul>
                <form class="navbar-form pull-right" method="POST" action="/login.php" enctype="application/x-www-form-urlencoded">
                    <input name="username" class="span2" type="text" placeholder="username" />
                    <input name="password" class="span2" type="password" placeholder="password" />
                    <input name="challenge" type="hidden" value="<?php echo $session->challenge; ?>">
                    <button type="submit" class="btn">Sign in</button>
                </form>

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
        <div class="hero">
            <h2>It's simple.  You see these two images, right?  Cool.  Now, just pick the one you like more.  That's it.</h2>
            <img src="/img/dogs.jpg" />
            <h4>OK, well not <em>these</em> images.  These are just for show.  To upvote/downvote images, you have to sign in.  You need to create
            an account if you don't have one.  We won't sell your information to spammers, we promise*.</h4>
            <br />
            <h3>So, log in so you can start dueling!</h3>
        </div>
    </div>

    <hr>

    <footer>
        <p>&copy; imgduel <?php echo date('Y'); ?></p>
        <p class="wee"><strong>*</strong>Made you look!</p>
    </footer>

</div> <!-- /container -->