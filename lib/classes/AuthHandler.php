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
 *  AuthHandlerhHandler.php
 *  Build A Social App In PHP
 *  SkillShare/Start It Up Delaware/The coIN Loft
 *  Created:    2013-04-26
 *  Modified:   0000-00-00
 */

imgduel_load_class('Token');
imgduel_load_class('User');
imgduel_load_class('Registry');
imgduel_load_class('Session');

/**
 * Class AuthHandler
 */
class AuthHandler
{
    /**
     * @param $username
     * @param $password
     * @return null|User
     */
    public static function authenticateWithCredentials($username, $password)
    {
        $user = User::getAuthenticationTokensForUsername($username);
        if (!isset($user)) {
            return null;
        }

        $hashes = Token::handlePassword($password, $user->salt);
        if (!empty($hashes['hashed_password']) && ($hashes['hashed_password'] === $user->password)) {
            $authUser = User::fetchByPk($user->id);
            return ($authUser->status === 'active') ? $authUser : null;
        }
        return null;
    }

    /**
     * @param $username
     * @param $email
     * @param $password
     * @return stdClass|User
     */
    public static function createUserWithInfo($username, $email, $password)
    {
        $hashes = Token::handlePassword($password);
        $storedPassword = $hashes['hashed_password'];
        $salt = $hashes['salt'];
        $db = Registry::get('IMGDUEL_DATABASE');
        $db->startTransaction();
        $active = 'active';
        $sql = 'INSERT INTO `user` (`username`, `email`, `password`, `salt`, `status`) VALUES (?,?,?,?,?)';

        $user = null;
        try {
            $db->write($sql, $username, $email, $storedPassword, $salt, $active);
            $userid = $db->lastInsertId();
            $user = User::fetchByPk($userid);
            Logger::userCreated($user);
            $db->commitTransaction();
        } catch (DatabaseException $ex) {
            $db->rollbackTransaction();
            $user = new stdClass();
            $user->error = true;
            $user->message = 'DATABASE_ERROR';
            $user->exception = $ex->getMessage();
            $user->errorInfo = $ex->errorInfo;
            Logger::userCreationFailed($user);
        } catch (UniqueKeyException $ex) {
            $db->rollbackTransaction();
            $user = new stdClass();
            $user->error = true;
            $user->message = 'UNIQUE_KEY_EXCEPTION';
            $user->exception = $ex->getMessage();
            $user->errorInfo = $ex->errorInfo;
            Logger::userCreationFailed($user);
        }

        return $user;
    }

    /**
     * Validate CSRF Token
     * @return bool
     */
    public static function validateCSRFToken()
    {
        $session = new Session();
        if (!isset($session->csrf)) {
            return false;
        }
        if (!isset($_POST['csrf'])) {
            return false;
        }
        return ($session->csrf === $_POST['csrf']);
    }
}