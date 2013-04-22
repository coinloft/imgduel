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
 *  Token.php
 *  Build A Social App In PHP
 *  SkillShare/Start It Up Delaware/The coIN Loft
 *  Created:    2013-04-18
 *  Modified:   0000-00-00
 */

class Token
{
    /**
     * @var string
     */
    private $_token;

    /**
     *  Constructor
     *  Creates token upon creation
     */
    public function __construct()
    {
        //  raw bytes to
        $bytes = null;
        //  cant use openssl or read from random.  need to generate some bytes
        $make_bytes = false;

        //  try to get openssl random bytes, if possible
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes(64);
        } else if (file_exists('/dev/urandom')) {
            //  try to read from random
            $fp = fopen('/dev/urandom', 'r');
            if (false !== $fp) {
                $bytes = fread($fp, 64);
                fclose($fp);
            } else {
                $make_bytes = true;
            }
        } else {
            $make_bytes = true;
        }
        //  need to make bytes?
        if ($make_bytes) {
            $bytes = '';
            for ($i = 0; $i < 16; $i++) {
                $base = mt_rand(0, 2147483647);
                $bytes .= pack('V', $base);
            }
        }
        //  makes a 128-character string
        $hex128 = bin2hex($bytes);
        //  front half (64 chars) of the 128 char string
        $seed = substr($hex128, 0, 64);
        //  back half (64 chars) of the 128 char string, plus blowfish encoding format
        $salt = '$2y$07$' . substr($hex128, -64, 64) . '$';

        //  the whole token with blowfish qualifiers prepended
        $full_token = crypt($seed, $salt);
        //  split the token by $
        $token_parts = explode('$', $full_token);

        //  set token to last segment in array
        $this->_token = (string)array_pop($token_parts);
    }

    /**
     * @param $password_plain
     * @param null $salt
     * @return array
     */
    public static function handlePassword($password_plain, $salt = null)
    {
        if (!isset($salt)) {
            $token = new Token();
            $salt = (string)$token;
        }
        $blowfish_salt = '$2y$07$' . $salt . '$';
        $crypt = crypt($password_plain, $blowfish_salt);
        $hashed_parts = explode('$', $crypt);
        $hashed_pass = (string)array_pop($hashed_parts);

        return array(
            'hashed_password' => $hashed_pass,
            'salt' => $salt
        );
    }

    /**
     * String magic method
     * @return string
     */
    public function __toString()
    {
        return $this->_token;
    }
}