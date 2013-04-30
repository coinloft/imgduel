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
 *  upload.php
 *  Build A Social App In PHP
 *  SkillShare/Start It Up Delaware/The coIN Loft
 *  Created:    2013-04-25
 *  Modified:   0000-00-00
 */
require realpath(dirname(__FILE__) . '/../lib') . '/bootstrap.php';
imgduel_load_class('Session');
imgduel_load_class('User');
imgduel_load_class('ImageUploader');
imgduel_load_class('AuthHandler');
imgduel_load_class('Logger');

$session = new Session();

if (!isset($session->loggedin)) {
    $session->destroy();
    header('Status: 403 Forbidden', true, 403);
    exit();
}

if ('POST' === $_SERVER['REQUEST_METHOD']) {
    if (!AuthHandler::validateCSRFToken()) {
        $session->destroy();
        header('Status: 403 Forbidden', true, 403);
        exit();
    }

    //  try to upload the image
    $result = ImageUploader::uploadImage('image');
    if ($result->error) {
        $session->errorMessage = $result->message;
    } else {
        Logger::imageUploaded($result->image_id, $result);
    }
}

$kb = ImageUploader::MAX_IMAGE_BYTES/1000;
require IMGDUEL_WWW_PATH . '/include/header.inc';
require IMGDUEL_WWW_PATH . '/include/upload.inc';
require IMGDUEL_WWW_PATH . '/include/footer.inc';