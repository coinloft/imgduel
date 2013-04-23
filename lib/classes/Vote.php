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
 *  Vote.php
 *  Build A Social App In PHP
 *  SkillShare/Start It Up Delaware/The coIN Loft
 *  Created:    2013-04-13
 *  Modified:   0000-00-00
 */

imgduel_load_class('ITableRowGateway');
imgduel_load_class('IDataMap');
imgduel_load_class('Registry');

/**
 * Class Vote
 */
class Vote implements ITableRowGateway, IDataMap
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $created;
    /**
     * @var bool
     */
    private $_new = true;

    //  ITableRowGateway

    /**
     *  Fetch Vote by Primary Key
     * @param $pk
     */
    public static function fetchByPk($pk)
    {
        $db = Registry::get('IMGDUEL_DATABASE');
        $sql = 'SELECT `id`, `created` FROM `vote` WHERE `id` = ?';
        $ret = $db->fetchObjectOfType('Vote', $sql, (int)$pk);
        if (isset($ret)) {
            $ret->_new = false;
        }
        return $ret;
    }

    /**
     * Gets a map to all the class members
     * @return array
     */
    public static function getMap()
    {
        return array(
            'id' => IMGDUEL_DATATYPE_INT,
            'created' => IMGDUEL_DATATYPE_STRING
        );
    }

    /**
     *  Save Vote
     */
    public function save()
    {
        $db = Registry::get('IMGDUEL_DATABASE');
        if ($this->_new) {
            $this->created = date('Y-m-d H:i:s');
            $ret = $db->write('INSERT INTO `vote` (`id`,`created`) VALUES (NULL, ?)', $this->created);
            if ($ret) {
                $this->id = (int)$db->lastInsertId();
                $this->_new = false;
            }
            return $ret;
        }
        //  there is no update logic for this class
        return false;
    }

    /**
     *  Delete Vote
     */
    public function delete()
    {
        if (!isset($this->id)) {
            return 0;
        }
        $db = Registry::get('IMGDUEL_DATABASE');
        $ret = $db->write('DELETE FROM `vote` WHERE `id` = ?', (int)$this->id);
        return $ret ? $db->affectedRows() : 0;
    }
}