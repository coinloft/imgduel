<?php
require '/Users/steve/Projects/skillshare/imgduel/lib/bootstrap.php';
imgduel_load_class('AuthHandler');
imgduel_load_class('User');
$a = User::getAuthenticationTokensForUsername('steve');
//$a = AuthHandler::authenticateWithCredentials('steve', 'network=-0');

var_dump($a);