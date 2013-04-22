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
 *  functions.php
 *  Build A Social App In PHP
 *  SkillShare/Start It Up Delaware/The coIN Loft
 *  Created:    2013-03-30
 *  Modified:   0000-00-00
 */

/**
 * Loads a class from the classpath
 * @param $classname
 */
function imgduel_load_class($classname)
{
    static $loadedClasses = null;
    if (!isset($loadedClasses)) {
        $loadedClasses = array();
    }
    if (in_array($classname, $loadedClasses, true)) {
        return false;
    }
    $path = sprintf('%s%s%s.php', IMGDUEL_CLASS_PATH, DIRECTORY_SEPARATOR, str_replace('_', DIRECTORY_SEPARATOR, $classname));
    $loaded = require $path;
    if (1 === $loaded) {
        $loadedClasses[] = $classname;
        return true;
    }
    return false;
}

/**
 * Gets a path to a config file
 * @param $filename The name of the config file
 * @param string $suffix The suffix of the config file
 * @return string The full Path to the config file
 */
function imgduel_config_file($filename, $suffix = 'ini')
{
    return sprintf('%s%s%s.%s', IMGDUEL_CONF_PATH, DIRECTORY_SEPARATOR, $filename, $suffix);
}

/**
 * Gets the full path to the default config file based on hostname
 * @return string
 */
function imgduel_default_config()
{
    return imgduel_config_file(php_uname('n'));
}