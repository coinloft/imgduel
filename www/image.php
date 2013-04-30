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
 *  image.php
 *  Build A Social App In PHP
 *  SkillShare/Start It Up Delaware/The coIN Loft
 *  Created:    2013-04-28
 *  Modified:   0000-00-00
 */
require realpath(dirname(__FILE__) . '/../lib') . '/bootstrap.php';
imgduel_load_class('Session');
imgduel_load_class('ImageFetcher');

if (!isset($_GET['u'])) {
    header('Status: 404 Not Found', true, 404);
    exit();
}
$session = new Session();
if (!isset($session->loggedin)) {
    header('Status: 404 Not Found', true, 404);
    exit();
}

$path = ImageFetcher::fetchUserImageLocation($session->user, $_GET['u']);
if (false === $path) {
    header('Status: 404 Not Found', true, 404);
    exit();
}
if (!file_exists($path)) {
    header('Status: 404 Not Found', true, 404);
    exit();
}
header('Content-Type: image/png');
$img = imagecreatefrompng($path);
imagepng($img, null, 4, PNG_ALL_FILTERS);
imagedestroy($img);
exit();