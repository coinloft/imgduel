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
 *  Session.php
 *  Build A Social App In PHP
 *  SkillShare/Start It Up Delaware/The coIN Loft
 *  Created:    2013-04-17
 *  Modified:   0000-00-00
 */

class Session
{
    /**
     *  Static flag to determine if the session has started
     * @var bool
     */
    private static $_started = false;

    /**
     *  Constructor
     *  Will start a session if the static started flag is false
     */
    public function __construct()
    {
        if (!self::$_started) {
            session_start();
            self::$_started = true;
            if (!isset($_SESSION['__DEFAULT__'])) {
                $_SESSION['__DEFAULT__'] = array();
            }
        }
    }

    /**
     *  Destroy session
     */
    public function destroy()
    {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 86400,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
        self::$_started = false;
    }

    /**
     *
     * @param $key
     * @return null|mixed
     */
    public function __get($key)
    {
        return isset($_SESSION['__DEFAULT__'][$key]) ? $_SESSION['__DEFAULT__'][$key] : null;
    }

    /**
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        if (isset($value)) {
            $_SESSION['__DEFAULT__'][$key] = $value;
        }
    }

    /**
     * @param $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($_SESSION['__DEFAULT__'][$key]);
    }

    /**
     * @param $key
     */
    public function __unset($key)
    {
        if (isset($_SESSION['__DEFAULT__'][$key])) {
            unset($_SESSION['__DEFAULT__'][$key]);
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return session_id();
    }
}