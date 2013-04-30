<?php
/**
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 *  and associated documentation files (the "Software"), to deal in the Software without restriction,
 *  including without limitation the rights to use, copy, modify, merge, publish, distribute,
 *  sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is
 *  furnished to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all copies or substantial
 *  portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT
 *  NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 *  NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES
 *  OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 *  CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *  login.php
 *  Build A Social App In PHP
 *  SkillShare/Start It Up Delaware/The coIN Loft
 *  Created:    2013-03-30
 *  Modified:   0000-00-00
 */
require realpath(dirname(__FILE__) . '/../lib') . '/bootstrap.php';
imgduel_load_class('AuthHandler');
imgduel_load_class('Clean');
imgduel_load_class('Session');
imgduel_load_class('Logger');
imgduel_load_class('Token');

$session = new Session();
$postData = Clean::scrubSuperGlobal(INPUT_POST, array(
    'username' => Clean::SANITIZE_XSS,
    'password' => Clean::SANITIZE_NONE,
    'challenge' => Clean::SANITIZE_NONE
));

if (!isset($session->challenge) || ($session->challenge !== $postData['challenge'])) {
    header('Status: 403 Forbidden', true, 403);
    exit();
}

$auth = AuthHandler::authenticateWithCredentials($postData['username'], $postData['password']);
if (isset($auth) && $auth instanceof User) {
    $session->user = $auth;
    $session->loggedin = true;
    $token = new Token();
    $session->csrf = (string)$token;
    unset($session->challenge);

    //  always regenerate the session id after a privilege escalation/deescalation
    session_regenerate_id();
    Logger::userLogin($auth);
} else {
    $session->errorMessage = 'ERROR_LOGIN_FAILED';
}
header('Location: /index.php', true, 303);
exit();