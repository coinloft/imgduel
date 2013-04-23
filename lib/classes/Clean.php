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
 *  Clean.php
 *  Build A Social App In PHP
 *  SkillShare/Start It Up Delaware/The coIN Loft
 *  Created:    2013-04-19
 *  Modified:   0000-00-00
 */

class Clean
{
//    /**
//     *  SANITIZES THE INPUT AS AN INTEGER
//     */
//    const SANITIZE_INT = 0x00001;
//    /**
//     *  SANITIZES THE INPUT AS A BOOLEAN
//     */
//    const SANITIZE_BOOL = 0x00002;
//    /**
//     *  SANITIZES A STRING AGAINST XSS
//     */
//    const SANITIZE_XSS = 0x00004;
//    /**
//     *  SANITIZES AN ARRAY ACCORDING TO A WHITELIST
//     */
//    const SANITIZE_WHITELIST = 0x00008;
//    /**
//     *  VALIDATES AN INTEGER
//     */
//    const VALIDATE_INT = 0x00010;
//    /**
//     *  VALIDATES AGAINST A WHITELIST
//     */
//    const VALIDATE_WHITELIST = 0x00020;
//    /**
//     *  VALIDATES AGAINST A REGULAR EXPRESSION
//     */
//    const VALIDATE_REGEX = 0x00040;
//    /**
//     *  VALIDATES AN EMAIL ADDRESS
//     */
//    const VALIDATE_EMAIL = 0x00080;
//    /**
//     *  VALIDATES SOMETHING IS NOT EMPTY
//     */
//    const VALIDATE_NOTEMPTY = 0x00100;
//
//    public static function sanitize ($input, $sanitizations, $params = null, $default = null)
//    {
//        if (is_array($sanitizations)) {
//            $ret = $input;
//            foreach ($sanitizations as $sanitization) {
//                $ret = isset($params[$sanitization]) ?
//                    self::_sanitize($input, $sanitization, $params[$sanitization], $default) :
//                    self::_sanitize($input, $sanitization, null, $default);
//            }
//
//            return $ret;
//        } else {
//            return self::_sanitize($input, $sanitizations, $params, $default);
//        }
//    }
//
//    private static function _sanitize ($input, $sanitization, $param = null, $default = null)
//    {
//        switch ($sanitization) {
//            case self::SANITIZE_INT:
//                return (int)$input;
//            case self::SANITIZE_BOOL:
//                if (is_array($param) && in_array($input, $param, true)) {
//                    return true;
//                }
//
//                return isset($default) ? $default : (bool)$input;
//            case self::SANITIZE_XSS:
//                $html401 = defined('ENT_HTML401') ? ENT_HTML401 : 0;
//
//                return htmlspecialchars($input, ENT_QUOTES | $html401, 'UTF-8');
//            case self::SANITIZE_WHITELIST:
//                if (!is_array($param)) {
//                    return isset($default) ? $default : $input;
//                }
//                if (!in_array($input, $param, true)) {
//                    return isset($default) ? $default : $input;
//                }
//
//                return $input;
//        }
//    }
//
//    public static function validate ($input, $validations, $params = null)
//    {
//        if (is_array($validations)) {
//            $ret = 1;
//            foreach ($validations as $validation) {
//                $ret &= isset($params[$validation]) ?
//                    self::_validate($input, $validation, $params[$validation]) :
//                    self::_validate($input, $validation);
//            }
//
//            return (bool)$ret;
//        } else {
//            return (bool)self::_validate($input, $validations, $params);
//        }
//    }
//
//    private static function _validate ($input, $validation, $param = null)
//    {
//        switch ($validation) {
//            case self::VALIDATE_INT:
//                return (int)ctype_digit($input);
//
//            case self::VALIDATE_WHITELIST:
//                if (!is_array($param)) {
//                    return 0;
//                }
//                return in_array($input, $param, true);
//            case self::VALIDATE_REGEX:
//                FILTER_VALIDATE_REGEXP
//        }
//    }
}