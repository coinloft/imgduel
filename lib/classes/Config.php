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
 *  Config.php
 *  Build A Social App In PHP
 *  SkillShare/Start It Up Delaware/The coIN Loft
 *  Created:    2013-04-16
 *  Modified:   0000-00-00
 */

class Config extends ArrayObject
{
    /**
     *
     * @var null|string
     */
    public $tag = null;

    /**
     * @param null $filePath
     */
    public function __construct($filePath = null)
    {
        parent::__construct(array(), parent::ARRAY_AS_PROPS);
        if (isset($filePath)) {
            $this->parseFile($filePath);
        }
        $this->_updateTag();
    }

    /**
     * @param $filePath
     * @return bool
     */
    public function parseFile($filePath)
    {
        $_filePath = (string)$filePath;
        if (!file_exists($_filePath)) {
            trigger_error(sprintf('Config file [%s] not found', $_filePath), E_USER_WARNING);
            return false;
        }
        $ini = parse_ini_file($_filePath, true);
        if (false === $ini) {
            trigger_error(sprintf('Could not parse ini file [%s]', $_filePath), E_USER_WARNING);
            return false;
        }
        foreach ($ini as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $_key => $_val) {
                    $this->_set("{$key}/{$_key}", $_val);
                }
            } else {
                $this->_set($key, $value);
            }
        }
        return true;
    }

    /**
     * @param $index
     * @param $value
     * @return bool
     */
    private function _set($index, $value)
    {
        $_index = (string)$index;
        if (!(isset($value) && isset($_index{0}))) {
            return false;
        }

        $this->offsetSet($_index, $value);
        return true;
    }
    /**
     * @param $index
     * @param $value
     * @return bool
     */
    public function set($index, $value)
    {
        $ret = $this->_set($index, $value);
        if ($ret) {
            $this->_updateTag();
        }
    }

    /**
     * @param $index
     * @return null
     */
    public function get($index)
    {
        return ($this->offsetExists($index)) ? $this->offsetGet($index) : null;
    }

    /**
     * @param $index
     * @return mixed|null
     */
    public function remove($index)
    {
        $_index = (string)$index;
        if (!isset($_index{0})) {
            return null;
        }
        if (!$this->offsetExists($_index)) {
            return null;
        }

        $ret = $this->offsetGet($_index);
        $this->offsetUnset($_index);

        $this->_updateTag();

        return $ret;
    }

    private function _updateTag()
    {
        $this->tag = null;
        $this->tag = md5(serialize($this));
    }
}