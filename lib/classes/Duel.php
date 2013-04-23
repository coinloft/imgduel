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
 *  Duel.php
 *  Build A Social App In PHP
 *  SkillShare/Start It Up Delaware/The coIN Loft
 *  Created:    2013-04-13
 *  Modified:   0000-00-00
 */

imgduel_load_class('ITableRowGateway');
imgduel_load_class('IDataMap');
imgduel_load_class('Database');
imgduel_load_class('Registry');
imgduel_load_class('Token');

/**
 * Class Duel
 */
class Duel implements ITableRowGateway, IDataMap
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var int
     */
    public $image1;
    /**
     * @var int
     */
    public $image2;
    /**
     * @var int
     */
    public $user_id;
    /**
     * @var string
     */
    public $created;
    /**
     * @var string
     */
    public $token;
    /**
     * @var bool
     */
    private $_new = true;

    //  ITableRowGateway

    /**
     *  Fetch Duel by Primary Key
     * @param $pk
     * @return Duel
     */
    public static function fetchByPk($pk)
    {
        $db = Registry::get('IMGDUEL_DATABASE');
        $sql = 'SELECT `id`, `image1`, `image2`, `user_id`, `created`, `token` FROM `duel` WHERE `id` = ?';
        $ret = $db->fetchObjectOfType('Duel', $sql, (int)$pk);
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
            'image1' => IMGDUEL_DATATYPE_INT,
            'image2' => IMGDUEL_DATATYPE_INT,
            'user_id' => IMGDUEL_DATATYPE_INT,
            'created' => IMGDUEL_DATATYPE_STRING,
            'token' => IMGDUEL_DATATYPE_STRING
        );
    }

    /**
     *  Save Duel
     */
    public function save()
    {
        $db = Registry::get('IMGDUEL_DATABASE');
        if ($this->_new) {
            $this->created = date('Y-m-d H:i:s');
            $token = new Token();
            $this->token = (string)$token;

            $ret = $db->write('INSERT INTO `duel` (`id`,`image1`,`image2`,`user_id`,`created`,`token`) VALUES (NULL, ?, ?, ?, ?, ?)',
                $this->image1,
                $this->image2,
                $this->user_id,
                $this->created,
                $this->token
            );
            if ($ret) {
                $this->id = (int)$db->lastInsertId();
                $this->_new = false;
            }
            return $ret;
        }
        //  there is nothing that can be updated in this class
        return false;
    }

    /**
     *  Delete Duel
     */
    public function delete()
    {
        if (!isset($this->id)) {
            return 0;
        }
        $db = Registry::get('IMGDUEL_DATABASE');
        $ret = $db->write('DELETE FROM `duel` WHERE `id` = ?', (int)$this->id);
        return $ret ? $db->affectedRows() : 0;
    }
}