<?php

namespace Jara\Core\Helpers;

class Videos
{

    public static function add($file, $folder = 'videos')
    {
        $extension = explode('/', mime_content_type($file['tmp_name']))[1];

        if (isset($file['tmp_name'])) {
            $newName = md5($file['name'] . $file['tmp_name'] . microtime(true));
        } else {
            $newName = date('dmy') . md5_file($file);
        }

        $dest = BASEPATH . '/public/uploads/' . $folder . '/' .  $newName . '.' . $extension;

        file_put_contents($dest, file_get_contents($file['tmp_name']));

        return $newName . '.' . $extension;
    }

    public static function remove($filename,  $folder = 'videos')
    {
        $file  = BASEPATH . '/public/uploads/' . $folder . '/' .  $filename;
        unlink($file);
    }
}
