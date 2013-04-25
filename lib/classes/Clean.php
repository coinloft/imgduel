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
    /**
     *  SANITIZES THE INPUT AS AN INTEGER
     */
    const SANITIZE_INT = 1;
    /**
     *  SANITIZES THE INPUT AS A BOOLEAN
     */
    const SANITIZE_BOOL = 2;
    /**
     *  SANITIZES A STRING AGAINST XSS
     */
    const SANITIZE_XSS = 4;
    /**
     *  SANITIZES AN ARRAY ACCORDING TO A WHITELIST
     */
    const SANITIZE_WHITELIST = 8;
    /**
     *  VALIDATES AN INTEGER
     */
    const VALIDATE_INT = 16;
    /**
     *  VALIDATES AGAINST A WHITELIST
     */
    const VALIDATE_WHITELIST = 32;
    /**
     *  VALIDATES AGAINST A REGULAR EXPRESSION
     */
    const VALIDATE_REGEX = 64;
    /**
     *  VALIDATES AN EMAIL ADDRESS
     */
    const VALIDATE_EMAIL = 128;

    /**
     * @param int $superglobal
     * @param array $filters
     * @param array $params
     * @param array $defaults
     * @return array
     */
    public static function scrubSuperGlobal($superglobal, array $filters, array $params = null, array $defaults = null)
    {
        $_ref = null;
        switch ($superglobal) {
            case INPUT_GET:
                $_ref =& $_GET;
                break;
            case INPUT_POST:
                $_ref =& $_POST;
                break;
            case INPUT_REQUEST:
                $_ref =& $_REQUEST;
                break;
            case INPUT_SERVER:
                $_ref =& $_SERVER;
                break;
            case INPUT_SESSION:
                $_ref =& $_SESSION;
                break;
            case INPUT_COOKIE:
                $_ref =& $_COOKIE;
                break;
            case INPUT_ENV:
                $_ref =& $_ENV;
                break;
            default:
                $_ref = array();
        }

        return self::scrubArray($_ref, $filters, $params, $defaults);
    }

    /**
     * @param array $array
     * @param array $filters
     * @param array $params
     * @param array $defaults
     * @return array
     */
    public static function scrubArray(array $array, array $filters, array $params = null, array $defaults = null)
    {
        $filtered_inputs = array_intersect_key($array, $filters);
        $filtered_outputs = array();

        foreach ($filtered_inputs as $key => $dirty) {
            $_param = isset($params[$key]) ? $params[$key] : null;
            $_default = isset($defaults[$key]) ? $defaults[$key] : null;
            $filtered_outputs[$key] = self::sanitize($dirty, $filters[$key], $_param, $_default);
        }

        return $filtered_outputs;
    }

    /**
     * @param $input
     * @param $sanitize_type
     * @param null $params
     * @param null $default
     * @return bool|int|null|string
     */
    public static function sanitize($input, $sanitize_type, $params = null, $default = null)
    {
        if (is_array($sanitize_type)) {
            $ret = $input;
            foreach ($sanitize_type as $sanitizer) {
                $ret = isset($params[$sanitizer]) ?
                    self::_sanitize($input, $sanitizer, $params[$sanitizer], $default) :
                    self::_sanitize($input, $sanitizer, null, $default);
            }

            return $ret;
        } else {
            return self::_sanitize($input, $sanitize_type, $params, $default);
        }
    }

    /**
     * @param $input
     * @param $sanitizer
     * @param null $param
     * @param null $default
     * @return bool|int|null|string
     */
    private static function _sanitize($input, $sanitizer, $param = null, $default = null)
    {
        switch ($sanitizer) {
            case self::SANITIZE_INT:
                return (int)$input;
            case self::SANITIZE_BOOL:
                if (is_array($param) && in_array($input, $param, true)) {
                    return true;
                }

                return isset($default) ? $default : (bool)$input;
            case self::SANITIZE_XSS:
                $html401 = defined('ENT_HTML401') ? ENT_HTML401 : 0;

                return htmlspecialchars($input, ENT_QUOTES | $html401, 'UTF-8');
            case self::SANITIZE_WHITELIST:
                if (!is_array($param)) {
                    return isset($default) ? $default : $input;
                }
                if (!in_array($input, $param, true)) {
                    return isset($default) ? $default : $input;
                }

                return $input;
            default:
                return $input;
        }
    }

    /**
     * @param mixed $input
     * @param int|array $validations
     * @param mixed $params
     * @return bool|int
     */
    public static function validate($input, $validations, $params = null)
    {
        if (is_array($validations)) {
            $ret = 1;
            foreach ($validations as $validation) {
                $ret &= isset($params[$validation]) ?
                    self::_validate($input, $validation, $params[$validation]) :
                    self::_validate($input, $validation);
                if (0 === $ret) {
                    return false;
                }
            }

            return true;
        } else {
            return self::_validate($input, $validations, $params);
        }
    }

    /**
     * @param $input
     * @param $validation
     * @param null $param
     * @return bool
     */
    private static function _validate($input, $validation, $param = null)
    {
        switch ($validation) {
            case self::VALIDATE_INT:
                return ctype_digit($input);

            case self::VALIDATE_WHITELIST:
                if (!is_array($param)) {
                    return false;
                }
                return in_array($input, $param, true);

            case self::VALIDATE_REGEX:
                $regexp = filter_var($input, FILTER_VALIDATE_REGEXP, array(
                    'options' => array(
                        'regexp' => $param
                    )
                ));
                return ($regexp !== false);
            case self::VALIDATE_EMAIL:
                return (false !== filter_var($input, FILTER_VALIDATE_EMAIL));

            default:
                return false;

        }
    }
}