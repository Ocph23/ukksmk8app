<?php

namespace App\Http;

use PDOException;

class DatabaseHelper
{
    public static function  GetErrorPDOError(PDOException $ex)
    {
        $error["message"]=$ex->getMessage();
        if($ex->errorInfo[1]==1062){
            $error["message"]="Data Sudah Ada !";
        }
        return $error;
    }
}
