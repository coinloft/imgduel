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
 *  IDataMap.php
 *  Build A Social App In PHP
 *  SkillShare/Start It Up Delaware/The coIN Loft
 *  Created:    2013-04-19
 *  Modified:   0000-00-00
 */

if (!defined ('IMGDUEL_DATAMAP_VARS')) {
    /**
     *  flag
     */
    define ('IMGDUEL_DATAMAP_VARS', true);
    /**
     *  default enum
     */
    define ('IMGDUEL_DATAMAP_STRING', 0);
    /**
     *  int enum
     */
    define ('IMGDUEL_DATAMAP_INT',  100);
    /**
     *  bool enum
     */
    define ('IMGDUEL_DATAMAP_BOOL', 101);
}

/**
 * Interface IDataMap
 */
interface IDataMap
{
    /**
     * Gets a map to all the class members
     * @return array
     */
    static function getMap();
}