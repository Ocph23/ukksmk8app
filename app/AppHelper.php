<?php

namespace App;

use Faker\Core\File;
use PDOException;
use Intervention\Image\Facades\Image;

class AppHelper
{
    public static function base64ToFile($base64, $path, $width = 400, $height = 400)
    {
        $image = str_replace('data:image/png;base64,', '', $base64);
        $image = str_replace(' ', '+', $image);
        $imageName = md5(rand(11111, 99999)) . '_' . time() . '.jpg';
        $path = $path . $imageName;
        $input = File::put($path, base64_decode($image));
        $image = Image::make($path)->resize($width, $height);
        $result = $image->save($path);
        return $imageName;
    }
}
