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
 *  Logger.php
 *  Build A Social App In PHP
 *  SkillShare/Start It Up Delaware/The coIN Loft
 *  Created:    2013-04-26
 *  Modified:   0000-00-00
 */

imgduel_load_class('Registry');
imgduel_load_class('User');

class Logger
{
    const CONTEXT_GENERAL = 'general';
    const CONTEXT_AUTH = 'auth';
    const CONTEXT_UPLOAD = 'upload';

    const ENTITY_USER = 'user';
    const ENTITY_VOTE = 'vote';
    const ENTITY_IMAGE = 'image';
    const ENTITY_DUEL = 'duel';

    private static function _log($subject, $entity, $context, $label, $data='')
    {
        $db = Registry::get('IMGDUEL_DATABASE');
        $db->write('INSERT INTO `logger` (`subject`, `entity`, `context`, `label`, `data`) VALUES (?,?,?,?,?)',
            $subject, $entity, $context, $label, $data);
    }

    public static function userLogin(User $user)
    {
        self::_log($user->id, self::ENTITY_USER, self::CONTEXT_AUTH, 'login');
    }

    public static function userLogout(User $user)
    {
        self::_log($user->id, self::ENTITY_USER, self::CONTEXT_AUTH, 'logout');
    }

    public static function userCreated(User $user)
    {
        self::_log($user->id, self::ENTITY_USER, self::CONTEXT_AUTH, 'created');
    }

    public static function userCreationFailed($failObj)
    {
        $failObj = serialize($failObj);
        self::_log(0, self::ENTITY_USER, self::CONTEXT_AUTH, 'create_failed', $failObj);
    }

    public static function imageUploaded($image_id, $data)
    {
        $data = serialize($data);
        self::_log($image_id, self::ENTITY_IMAGE, self::CONTEXT_UPLOAD, 'image_uploaded', $data);
    }
}