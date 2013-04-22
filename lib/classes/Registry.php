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
 *  Registry.php
 *  Build A Social App In PHP
 *  SkillShare/Start It Up Delaware/The coIN Loft
 *  Created:    2013-04-16
 *  Modified:   0000-00-00
 */

class Registry extends ArrayObject
{
    /**
     * @var Registry
     */
    private static $_instance = null;

    /**
     * @param $index
     * @return null
     */
    public static function get($index)
    {
        $instance = self::getInstance();
        return ($instance->offsetExists($index)) ? $instance->offsetGet($index) : null;
    }

    /**
     * @param $index
     * @param $value
     * @return bool
     */
    public static function set($index, $value)
    {
        $_index = (string)$index;
        if (!(isset($value) && isset($_index{0}))) {
            return false;
        }

        $instance = self::getInstance();
        $instance->offsetSet($_index, $value);
        return true;
    }

    /**
     * @return null|Registry
     */
    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new Registry();
        }

        return self::$_instance;
    }

    /**
     * @param $index
     * @return null|mixed
     */
    public static function remove($index)
    {
        $_index = (string)$index;
        if (!isset($_index{0})) {
            return null;
        }
        $instance = self::getInstance();
        if (!$instance->offsetExists($_index)) {
            return null;
        }

        $ret = $instance->offsetGet($_index);
        $instance->offsetUnset($_index);

        return $ret;
    }
}