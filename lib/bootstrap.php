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
/**
 *  THE BOOTSTRAP IS LOADED
 */
define ('IMGDUEL_BOOSTRAP', true);

//  PATHS
/*********************************************************************************/
/**
 *  ROOT PATH
 */
define ('IMGDUEL_ROOT_PATH', realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..'));
/**
 *  LIB PATH
 */
define ('IMGDUEL_LIB_PATH', IMGDUEL_ROOT_PATH . DIRECTORY_SEPARATOR . 'lib');
/**
 *  CLASS PATH
 */
define ('IMGDUEL_CLASS_PATH', IMGDUEL_LIB_PATH . DIRECTORY_SEPARATOR . 'classes');
/**
 *  CONFIG PATH
 */
define ('IMGDUEL_CONF_PATH', IMGDUEL_ROOT_PATH . DIRECTORY_SEPARATOR . 'conf');
/**
 *  WEB ROOT
 */
define ('IMGDUEL_WWW_PATH', IMGDUEL_ROOT_PATH . DIRECTORY_SEPARATOR . 'www');

//  Data Type Enumerations
/*********************************************************************************/
/**
 *  NULL
 */
define ('IMGDUEL_DATATYPE_NULL', 1);
/**
 *  INTEGER
 */
define ('IMGDUEL_DATATYPE_INT', 1 << 1);
/**
 *  FLOAT
 */
define ('IMGDUEL_DATATYPE_FLOAT', 1 << 2);
/**
 *  STRING
 */
define ('IMGDUEL_DATATYPE_BOOL', 1 << 3);
/**
 *  STRING
 */
define ('IMGDUEL_DATATYPE_STRING', 1 << 4);
/**
 *  ARRAY
 */
define ('IMGDUEL_DATATYPE_ARRAY', 1 << 5);
/**
 *  OBJECT
 */
define ('IMGDUEL_DATATYPE_OBJECT', 1 << 6);
/**
 *  FUNCTION
 */
define ('IMGDUEL_DATATYPE_LAMBDA', 1 << 7);
/**
 *  FUNCTION
 */
define ('IMGDUEL_DATATYPE_EMAIL', 1 << 8);

//  RUNTIME SETTINGS
/*********************************************************************************/
require IMGDUEL_LIB_PATH . DIRECTORY_SEPARATOR . 'runtime.php';

//  INCLUDES
/*********************************************************************************/
require IMGDUEL_LIB_PATH . DIRECTORY_SEPARATOR . 'functions.php';

//  bootstrap the database and config files
/*********************************************************************************/
imgduel_load_class('Config');
imgduel_load_class('Registry');
imgduel_load_class('Database');

$config = new Config(imgduel_default_config());
Registry::set('IMGDUEL_CONFIG', $config);

$database = Database::fromConfig($config);
Registry::set('IMGDUEL_DATABASE', $database);
