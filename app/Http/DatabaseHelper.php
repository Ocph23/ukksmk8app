<?php

namespace App\Http;

use Faker\Core\File;
use PDOException;
use Intervention\Image\Facades\Image;

class DatabaseHelper
{
    public static function  GetErrorPDOError(PDOException $ex)
    {
        $error["message"] = $ex->getMessage();
        if ($ex->errorInfo[1] == 1062) {
            $error["message"] = "Data Sudah Ada !";
        }

        if ($ex->errorInfo[1] == 1451) {
            $error["message"] = "Data Memiliki Relasi Dengan Yang Lain, Data Tidak Dapat Dihapus !";
        }



        return $error;
    }
}
