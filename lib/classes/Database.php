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
 *  Database.php
 *  Build A Social App In PHP
 *  SkillShare/Start It Up Delaware/The coIN Loft
 *  Created:    2013-04-18
 *  Modified:   0000-00-00
 */

imgduel_load_class('IDataMap');
imgduel_load_class('Registry');
imgduel_load_class('Config');

/**
 * Class Database
 */
class Database
{
    /**
     * Internal database pointer
     * @var PDO
     */
    private $_pdo;
    /**
     * Number of affected rows from last write operation
     * @var int
     */
    private $_affectedRows = 0;

    /**
     * Constructor
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string $dbname
     * @param string $driver
     * @param int|string|null $port
     * @param string|null $socket
     * @param array $options
     * @param string $config_tag
     */
    public function  __construct($host, $username, $password, $dbname, $driver, $port = null, $socket = null, $options = array(), $config_tag = '')
    {
        $registry_address = "__PDO__{$config_tag}";
        $this->_pdo = Registry::get($registry_address);

        if (!isset($this->_pdo)) {
            if (!isset($host, $username, $password, $dbname)) {
                trigger_error('Could not instantiate database.  Invalid config params', E_USER_ERROR);
            }

            //  for PDO, you cant have a socket set AND a port/hostname
            //  i.e. the socket, if set, means that the port and hostname
            //  should NOT be set
            if (!is_null($socket)) {
                $dsn = "{$driver}:unix_socket={$socket};dbname={$dbname}";
            } else {
                if (is_null($port)) {
                    $port = 3306;
                }
                $dsn = "{$driver}:host={$host};port={$port};dbname={$dbname}";
            }
            $pdo = null;
            try {
                $pdo = new PDO($dsn, $username, $password, $options);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $ex) {
                trigger_error($ex->getMessage(), E_USER_WARNING);
                return;
            }
            Registry::set($registry_address, $pdo);
            $this->_pdo = & $pdo;
        }
    }

    /**
     * Creates a database instance from a Config object
     * @param Config $config
     * @return Database
     */
    public static function fromConfig(Config $config)
    {
        $options = $config->get('DATABASE/options');
        if (empty($options)) {
            $options = array();
        } else {
            $options = unserialize($options);
            if (false === $options) {
                $options = array();
            }
        }
        return new Database(
            $config->get('DATABASE/host'),
            $config->get('DATABASE/username'),
            $config->get('DATABASE/password'),
            $config->get('DATABASE/dbname'),
            $config->get('DATABASE/driver'),
            $config->get('DATABASE/port'),
            $config->get('DATABASE/socket'),
            $options,
            $config->tag
        );
    }

    /**
     *
     * @param $class
     * @param $sql
     * @return null
     */
    public function fetchObjectsOfType($class, $sql)
    {
        $args = func_get_args();
        array_shift($args);
        $result = call_user_func_array(array($this, '_query'), $args);
        if (false === $result->exec) {
            return null;
        }
        $stmt = $result->stmt;
        if (class_exists($class)) {
            $interfaces = array_values(class_implements($class, false));
            if (in_array('IDataMap', $interfaces, true)) {
                /** @noinspection PhpUndefinedMethodInspection */
                $assoc = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if (false === $assoc) {
                    return null;
                }
                $map = call_user_func("{$class}::getMap");
                $numRows = count($assoc);
                $ret = array();
                for ($i = 0; $i < $numRows; $i++) {
                    $row = $assoc[$i];
                    $obj = new $class();
                    foreach ($map as $property => $type) {
                        switch ($type) {
                            case IMGDUEL_DATAMAP_INT:
                                $obj->$property = (int)$row[$property];
                                break;
                            case IMGDUEL_DATAMAP_BOOL:
                                $obj->$property = (bool)$row[$property];
                                break;
                            case IMGDUEL_DATAMAP_STRING:
                            default:
                                $obj->$property = (string)$row[$property];
                                break;
                        }
                    }
                    $ret[] = $obj;
                }
                unset($assoc);
                return $ret;
            }
        }
        /** @noinspection PhpUndefinedMethodInspection */
        return $stmt->fetchAll(PDO::FETCH_CLASS, $class);
    }

    /**
     * @param $sql
     * @return null
     */
    public function fetchObjects($sql)
    {
        $args = func_get_args();
        $result = call_user_func_array(array($this, '_query'), $args);
        if (false === $result->exec) {
            return null;
        }

        $stmt = $result->stmt;
        /** @noinspection PhpUndefinedMethodInspection */
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param $sql
     * @return null
     */
    public function fetchAll($sql)
    {
        $args = func_get_args();
        $result = call_user_func_array(array($this, '_query'), $args);
        if (false === $result->exec) {
            return null;
        }

        $stmt = $result->stmt;
        /** @noinspection PhpUndefinedMethodInspection */
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param $sql
     * @return null
     */
    public function fetchOne($sql)
    {
        $args = func_get_args();
        $stmtObj = call_user_func_array(array($this, '_query'), $args);
        if (false === $stmtObj->exec) {
            return null;
        }

        $stmt = $stmtObj->stmt;
        var_dump($stmt);
        /** @noinspection PhpUndefinedMethodInspection */
        return $stmt->fetchColumn();
    }

    /**
     * @param $class
     * @param $sql
     * @return null
     */
    public function fetchObjectOfType($class, $sql)
    {
        $args = func_get_args();
        array_shift($args);
        $stmtObj = call_user_func_array(array($this, '_query'), $args);
        if (false === $stmtObj->exec) {
            return null;
        }

        $stmt = $stmtObj->stmt;
        if (class_exists($class)) {
            $interfaces = array_values(class_implements($class, false));
            if (in_array('IDataMap', $interfaces, true)) {
                /** @noinspection PhpUndefinedMethodInspection */
                $assoc = $stmt->fetch(PDO::FETCH_ASSOC);
                if (false === $assoc) {
                    return null;
                }
                $map = call_user_func("{$class}::getMap");
                $obj = new $class();
                foreach ($map as $property => $type) {
                    switch ($type) {
                        case IMGDUEL_DATAMAP_INT:
                            $obj->$property = (int)$assoc[$property];
                            break;
                        case IMGDUEL_DATAMAP_BOOL:
                            $obj->$property = (bool)$assoc[$property];
                            break;
                        case IMGDUEL_DATAMAP_STRING:
                        default:
                            $obj->$property = (string)$assoc[$property];
                            break;
                    }
                }

                return $obj;
            }
        }

        /** @noinspection PhpUndefinedMethodInspection */
        return $stmt->fetchObject($class);
    }

    /**
     * @param $sql
     * @return null
     */
    public function fetchObject($sql)
    {
        $args = func_get_args();
        $stmtObj = call_user_func_array(array($this, '_query'), $args);
        if (false === $stmtObj->exec) {
            return null;
        }

        $stmt = $stmtObj->stmt;
        /** @noinspection PhpUndefinedMethodInspection */
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @param $sql
     * @return null
     */
    public function fetchRow($sql)
    {
        $args = func_get_args();
        $stmtObj = call_user_func_array(array($this, '_query'), $args);
        if (false === $stmtObj->exec) {
            return null;
        }

        $stmt = $stmtObj->stmt;
        /** @noinspection PhpUndefinedMethodInspection */
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param $sql
     * @return array|null
     */
    public function fetchColumn($sql)
    {
        $args = func_get_args();
        $stmtObj = call_user_func_array(array($this, '_query'), $args);
        if (false === $stmtObj->exec) {
            return null;
        }
        $stmt = $stmtObj->stmt;
        $ret = array();
        /** @noinspection PhpUndefinedMethodInspection */
        while ($col = $stmt->fetchColumn()) {
            $ret[] = $col;
        }

        return $ret;
    }

    /**
     * @return mixed
     */
    public function lastInsertId()
    {
        return $this->_pdo->lastInsertId();
    }

    /**
     * @return int
     */
    public function affectedRows()
    {
        return $this->_affectedRows;
    }

    /**
     * @param $sql
     * @return bool
     */
    public function write($sql)
    {
        $args = func_get_args();
        $stmtObj = call_user_func_array(array($this, '_query'), $args);
        if (false === $stmtObj->exec) {
            return false;
        }
        $stmt = $stmtObj->stmt;
        $this->_affectedRows = $stmt->rowCount();
        return true;
    }

    /**
     * @return bool
     */
    public function startTransaction()
    {
        if ($this->_pdo->inTransaction()) {
            return false;
        }
        return $this->_pdo->beginTransaction();
    }

    /**
     * @return bool
     */
    public function commitTransaction()
    {
        if (!$this->_pdo->inTransaction()) {
            return false;
        }
        return $this->_pdo->commit();
    }

    /**
     * @return bool
     */
    public function rollbackTransaction()
    {
        if (!$this->_pdo->inTransaction()) {
            return false;
        }
        return $this->_pdo->rollBack();
    }

    /**
     * @return array
     */
    public function lastError()
    {
        return array(
            'errorCode' => $this->_pdo->errorCode(),
            'errorInfo' => $this->_pdo->errorInfo()
        );
    }

    /**
     * @param $sql
     * @return stdClass
     */
    private function _query($sql)
    {
        try {
            $stmt = $this->_pdo->prepare($sql);

            $args = array_values(func_get_args());
            array_shift($args);

            $numArgs = count($args);
            for ($i = 0; $i < $numArgs; $i++) {
                $p = $i + 1;
                $t = gettype($args[$i]);
                switch ($t) {
                    case 'integer':
                        $stmt->bindValue($p, $args[$i], PDO::PARAM_INT);
                        break;
                    case 'boolean':
                        $stmt->bindValue($p, $args[$i], PDO::PARAM_BOOL);
                        break;
                    default:
                        $v = $this->_pdo->quote($args[$i]);
                        $stmt->bindValue($p, $v, PDO::PARAM_STR);
                }
            }
            $ret = new stdClass();
            $ret->stmt = $stmt;
            $ret->exec = $stmt->execute();

            return $ret;
        } catch (PDOException $ex) {
            $ret = new stdClass();
            $ret->stmt = false;
            $ret->exec = false;
            $ret->error = $ex->getCode();
            trigger_error($ex->getMessage(), E_USER_WARNING);
            return $ret;
        }
    }
}