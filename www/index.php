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
 *  index.php
 *  Build A Social App In PHP
 *  SkillShare/Start It Up Delaware/The coIN Loft
 *  Created:    2013-03-30
 *  Modified:   0000-00-00
 */
ini_set('display_errors', 1);
error_reporting(E_ALL);
require realpath(dirname(__FILE__) . '/../lib') . '/bootstrap.php';
imgduel_load_class('Config');
imgduel_load_class('Registry');
imgduel_load_class('Database');
imgduel_load_class('Vote');

$config = new Config(imgduel_default_config());
Registry::set('IMGDUEL_CONFIG', $config);

$db = Database::fromConfig($config);
Registry::set('IMGDUEL_DATABASE', $db);
$sql = 'SELECT `id` FROM `vote`';
$votes = $db->fetchColumn($sql);

var_dump($votes);
