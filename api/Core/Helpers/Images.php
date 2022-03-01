<?php

namespace Jara\Core\Helpers;

use Imagecow\Image;

class Images
{
    public static function add($file, $height, $width, $format = 'png', $folder = 'images')
    {
        if (strpos($file, ';base64') !== false) {
            $newName = md5($file . microtime(true));
            // file_put_contents(BASEPATH . '/public/uploads/' . $folder . '/' .  $newName . '.' . $format, base64_decode($file));
            // $file  = BASEPATH . '/public/uploads/' . $folder . '/' .  $newName . '.' . $format;
        } else {
            $newName = date('dmy') . md5_file($file);
        }

        Image::fromString(file_get_contents($file), Image::LIB_GD)
            ->autoRotate()
            ->resizeCrop($width, $height, 'center', 'middle')
            ->format($format)
            ->save(BASEPATH . '/public/uploads/' . $folder . '/' .  $newName . '.' . $format);
        return $newName . '.' . $format;
    }


    public static function add_fullsized($file, $format = 'png', $folder = 'images')
    {
        if (strpos($file, ';base64') !== false) {
            $newName = md5($file . microtime(true));
            // file_put_contents(BASEPATH . '/public/uploads/' . $folder . '/' .  $newName . '.' . $format, base64_decode($file));
            // $file  = BASEPATH . '/public/uploads/' . $folder . '/' .  $newName . '.' . $format;
        } else {
            $newName = date('dmy') . md5_file($file);
        }

        Image::fromString(file_get_contents($file), Image::LIB_GD)
            ->autoRotate()
            ->format($format)
            ->save(BASEPATH . '/public/uploads/' . $folder . '/' .  $newName . '.' . $format);
        return $newName . '.' . $format;
    }

    public static function remove($filename,  $folder = 'images')
    {
        $file  = BASEPATH . '/public/uploads/' . $folder . '/' .  $filename;
        if (file_exists($file)) {
            unlink($file);
        }
    }
}
