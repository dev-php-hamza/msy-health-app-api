<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class Helper
{
    public static function file_exists_sys($dir, $filename)
    {
        $flag = false;
        $fileToBeDeleted = public_path().'/'.$dir.'/'.$filename;
        if (file_exists($fileToBeDeleted)) {

            $flag = true;
        }

        return $flag;
    }

    public static function make_dir($dir)
    {
        File::makeDirectory(public_path().'/'.$dir, 777,true);
        chmod(public_path().'/'.$dir, 0777);
    }

    public static function make_file_name($file_extension)
    {
        return time().rand(1, 100000).'.'.$file_extension;;
    }

    public static function unlink_file($dir, $filename)
    {
        @unlink(public_path().'/'.$dir.'/'.$filename);
    }
}