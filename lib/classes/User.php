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
 *  User.php
 *  Build A Social App In PHP
 *  SkillShare/Start It Up Delaware/The coIN Loft
 *  Created:    2013-04-13
 *  Modified:   0000-00-00
 */

imgduel_load_class('ITableRowGateway');
imgduel_load_class('IDataMap');
imgduel_load_class('Registry');
imgduel_load_class('Token');

/**
 * Class User
 */
class User implements ITableRowGateway, IDataMap
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $username;
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $created;
    /**
     * @var
     */
    public $password;
    /**
     * @var
     */
    public $salt;
    /**
     * @var
     */
    public $status;
    /**
     * @var bool
     */
    private $_new = true;

    //  ITableRowGateway

    /**
     *  Fetch User by Primary Key
     * @param $pk
     * @return User
     */
    public static function fetchByPk($pk)
    {
        $db = Registry::get('IMGDUEL_DATABASE');
        $sql = 'SELECT `id`, `username`, `email`, `status` FROM `user` WHERE `id` = ?';
        $ret = $db->fetchObjectOfType('User', $sql, (int)$pk);
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
            'username' => IMGDUEL_DATATYPE_STRING,
            'email' => IMGDUEL_DATATYPE_STRING,
            'created' => IMGDUEL_DATATYPE_STRING,
            'password' => IMGDUEL_DATATYPE_STRING,
            'salt' => IMGDUEL_DATATYPE_STRING,
            'status' => IMGDUEL_DATATYPE_STRING
        );
    }

    /**
     *  Save User
     */
    public function save()
    {
        $db = Registry::get('IMGDUEL_DATABASE');
        if ($this->_new) {
            $this->created = date('Y-m-d H:i:s');

            $hashes = Token::handlePassword($this->password);
            $this->password = $hashes['hashed_password'];
            $this->salt = $hashes['salt'];
            $this->status = 'inactive';

            $ret = $db->write('INSERT INTO `user` (`id`,`username`,`email`,`created`,`password`,`salt`,`status`) VALUES (NULL, ?, ?, ?, ?, ?, ?)',
                $this->username,
                $this->email,
                $this->created,
                $this->password,
                $this->salt,
                $this->status
            );
            if ($ret) {
                $this->id = (int)$db->lastInsertId();
                $this->_new = false;
            }
            return $ret;
        } else {
            //  we will not alter the username, created, password, or hash
            return $db->write('UPDATE `user` SET `email` = ?, `status` = ? WHERE `id` = ?', $this->email, $this->status, (int)$this->id);
        }
    }

    /**
     *  Delete User
     */
    public function delete()
    {
        if (!isset($this->id)) {
            return 0;
        }
        $db = Registry::get('IMGDUEL_DATABASE');
        $ret = $db->write('DELETE FROM `user` WHERE `id` = ?', (int)$this->id);
        return $ret ? $db->affectedRows() : 0;
    }
}