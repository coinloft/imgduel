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
 *  bootstrap.php
 *  Build A Social App In PHP
 *  SkillShare/Start It Up Delaware/The coIN Loft
 *  Created:    2013-03-30
 *  Modified:   0000-00-00
 */

//  FLAGS
/*********************************************************************************/
//  the bootstrap is loaded.
define ('IMGDUEL_BOOSTRAP', true);

//  PATHS
/*********************************************************************************/
//  Root path
define ('IMGDUEL_ROOT_PATH', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'));
//  Lib path
define ('IMGDUEL_LIB_PATH', IMGDUEL_ROOT_PATH . DIRECTORY_SEPARATOR . 'lib');
//  Class path
define ('IMGDUEL_CLASS_PATH', IMGDUEL_LIB_PATH . DIRECTORY_SEPARATOR . 'classes');
//  Config Path
define ('IMGDUEL_CONF_PATH', IMGDUEL_ROOT_PATH . DIRECTORY_SEPARATOR . 'conf');
//  Site Root
define ('IMGDUEL_WWW_PATH', IMGDUEL_ROOT_PATH . DIRECTORY_SEPARATOR . 'www');

//  RUNTIME SETTINGS
/*********************************************************************************/
require IMGDUEL_LIB_PATH . DIRECTORY_SEPARATOR . 'runtime.php';

//  INCLUDES
/*********************************************************************************/
require IMGDUEL_LIB_PATH . DIRECTORY_SEPARATOR . 'functions.php';