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
 *  ImageUploader.php
 *  Build A Social App In PHP
 *  SkillShare/Start It Up Delaware/The coIN Loft
 *  Created:    2013-04-28
 *  Modified:   0000-00-00
 */

imgduel_load_class('Image');
imgduel_load_class('Token');

/**
 * Class ImageUploader
 */
class ImageUploader
{
    /**
     *
     */
    const TYPE_JPG = 'image/jpeg';
    /**
     *
     */
    const TYPE_GIF = 'image/gif';
    /**
     *
     */
    const TYPE_PNG = 'image/png';
    /**
     *
     */
    const TYPE_BMP = 'image/bmp';
    /**
     *
     */
    const MAX_IMAGE_BYTES = 500000;
    /**
     *
     */
    const MAX_IMAGE_DIM = 400;

    /**
     * @param $index
     * @return stdClass
     */
    public static function uploadImage($index)
    {
        $ret = new stdClass();
        $ret->error = true;

        if (!isset($_FILES[$index])) {
            $ret->message = 'UPLOAD_FILE_NOT_FOUND';
            return $ret;
        }

        $image = $_FILES[$index];

        $types = array(
            self::TYPE_JPG,
            self::TYPE_GIF,
            self::TYPE_PNG,
            self::TYPE_BMP
        );

        if (!(isset($image['type']) && in_array($image['type'], $types, true))) {
            $ret->message = 'IMAGE_INVALID_FILE_TYPE';
            return $ret;
        }

        if (!(isset($image['size']) && $image['size'] <= self::MAX_IMAGE_BYTES)) {
            $ret->message = 'IMAGE_TOO_LARGE';
            return $ret;
        }

        if (!(isset($image['size']) && $image['size'] !== 0)) {
            $ret->message = 'IMAGE_EMPTY';
            return $ret;
        }

        if (!(isset($image['error']) && $image['error'] === 0)) {
            $ret->message = 'IMAGE_ERROR';
            return $ret;
        }

        if (!(isset($image['tmp_name']) && file_exists($image['tmp_name']))) {
            $ret->message = 'IMAGE_FILE_NOT_FOUND';
            return $ret;
        }

        $imgData = null;
        switch ($image['type']) {
            case self::TYPE_JPG:
                $imgData = imagecreatefromjpeg($image['tmp_name']);
                break;
            case self::TYPE_GIF:
                $imgData = imagecreatefromgif($image['tmp_name']);
                break;
            case self::TYPE_PNG:
                $imgData = imagecreatefrompng($image['tmp_name']);
                break;
            case self::TYPE_BMP:
                $imgData = imagecreatefromwbmp($image['tmp_name']);
                break;
            default:
                //  this should never happen, but paranoia is healthy
                //  when writing secure code
                $ret->message = 'IMAGE_INVALID_FILE_TYPE';
                return $ret;
        }

        //  if there was a problem creating the image resource, gtfo
        if (false === $imgData) {
            $ret->message = 'IMAGE_READ_ERROR';
            return $ret;
        }

        //  get width and height.  if getting width fails, gtfo
        $width = imagesx($imgData);
        if (false === $width) {
            imagedestroy($imgData);
            $ret->message = 'IMAGE_READ_ERROR';
            return $ret;
        }
        $height = imagesy($imgData);

        //  generate the image name
        $hash = md5_file($image['tmp_name']);

        //  The global config already exists in the registry.  Both the registry
        //  and the config were loaded during the bootstrap
        $config = Registry::get('IMGDUEL_CONFIG');
        $imagePath = $config->get('IMAGE/upload_path') . "/{$hash}.png";

        //  get the maximum image dimension
        $maxdim = max($width, $height);

        //  if the max image dimension is greater than the limit, we need to shrink
        //  the image a bit
        if ($maxdim > self::MAX_IMAGE_DIM) {
            $scaleFactor = self::MAX_IMAGE_DIM / $maxdim;
            $nwidth = round($width * $scaleFactor);
            $nheight = round($height * $scaleFactor);

            //  make a new image, place the old image on the resized image, then destroy the old image
            //  alias the name of the old image to the new image so everything is cleaned up properly
            //  upon imagedestroy()
            $tempImg = imagecreatetruecolor($nwidth, $nheight);
            imagecopyresized($tempImg, $imgData, 0, 0, 0, 0, $nwidth, $nheight, $width, $height);
            imagedestroy($imgData);
            $imgData =& $tempImg;
        }

        //  write the image to the upload path
        $written = imagepng($imgData, $imagePath, 0, PNG_FILTER_PAETH);
        if (!$written) {
            imagedestroy($imgData);
            $ret->message = 'IMAGE_WRITE_ERROR';
            return $ret;
        }
        imagedestroy($imgData);

        //  *********************************************************************************
        //  all the file creation is done at this point, now create an image in the database

        //  create image object, set its path and token
        $imgObject = new Image();
        $imgObject->filepath = $imagePath;

        $token = new Token();
        $imgObject->token = (string)$token;

        $db = Registry::get('IMGDUEL_DATABASE');
        $db->startTransaction();

        try {
            $imgObject->save();

            //  create the union association between the user and the image
            $session = new Session();
            $user = $session->user;

            $db->write('INSERT INTO `user_image` (`user_id`, `image_id`) VALUES (?, ?)', $user->id, $imgObject->id);
            $db->commitTransaction();
        } catch (UniqueKeyException $ex) {
            $db->rollbackTransaction();
            $ret->message = 'IMAGE_DB_ERROR';
            return $ret;
        } catch (DatabaseException $ex) {
            $db->rollbackTransaction();
            $ret->message = 'IMAGE_DB_ERROR';
            return $ret;
        }

        $ret->image_id = $imgObject->id;
        $ret->user_id = $user->id;
        $ret->error = false;
        return $ret;
    }
}