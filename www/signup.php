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
 *  signup.php
 *  Build A Social App In PHP
 *  SkillShare/Start It Up Delaware/The coIN Loft
 *  Created:    2013-04-25
 *  Modified:   0000-00-00
 */
require realpath(dirname(__FILE__) . '/../lib') . '/bootstrap.php';
imgduel_load_class('Session');
imgduel_load_class('Token');
imgduel_load_class('Clean');
imgduel_load_class('AuthHandler');
imgduel_load_class('Logger');

$session = new Session();

if (isset($session->loggedin)) {
    header('Location: /');
    exit();
}

//  if user is posting to the server, try to create a new user
if ('POST' === $_SERVER['REQUEST_METHOD']) {
    $postData = Clean::scrubSuperGlobal(INPUT_POST, array(
        'username' => Clean::SANITIZE_XSS,
        'email' => Clean::SANITIZE_NONE,
        'password' => Clean::SANITIZE_NONE,
        'confirm' => Clean::SANITIZE_NONE,
        'challenge' => Clean::SANITIZE_NONE
    ));
    if (!isset($session->challenge) || ($session->challenge !== $postData['challenge'])) {
        header('Status: 403 Forbidden', true, 403);
        exit();
    }

    $error = false;
    $errMsg = array();
    //  validate email address
    if (!Clean::validate($postData['email'], Clean::VALIDATE_EMAIL)) {
        $errMsg[] = 'INVALID_EMAIL';
        $error = true;
    }

    //  validate username
    if (!Clean::validate($postData['username'], Clean::VALIDATE_NOTEMPTY)) {
        $errMsg[] = 'INVALID_USERNAME';
        $error = true;
    }

    //  validate password
    if (!Clean::validate($postData['password'], array(
        Clean::VALIDATE_NOTEMPTY,
        Clean::VALIDATE_EQUALS
    ), array(
        Clean::VALIDATE_EQUALS => $postData['confirm']
    ))) {
        $errMsg[] = 'INVALID_PASSWORD';
        $error = true;
    }

    //  if there's an error, route back to signup.php via GET
    if ($error) {
        $session->errorMessage = implode('|', $errMsg);
        header('Location: /signup.php', true, 303);
        exit();
    }

    //  try to create a user with the AuthHandler
    $user = AuthHandler::createUserWithInfo($postData['username'], $postData['email'], $postData['password']);

    //  if there was a problem saving the user
    if (isset($user->error)) {
        $session->errorMessage = $user->message;
        header('Location: /signup.php', true, 303);
        exit();
    }

    //  user is saved...send message telling user to log in
    $session->infoMessage = 'USER_CREATED_SUCCESSFULLY';
    header('Location: /', true, 303);
    exit();
}

require IMGDUEL_WWW_PATH . '/include/header.inc';
require IMGDUEL_WWW_PATH . '/include/signup.inc';
require IMGDUEL_WWW_PATH . '/include/footer.inc';