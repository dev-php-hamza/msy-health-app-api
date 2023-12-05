<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

class ApiResponse
{
    public static function success($data=null)
    {
        $res = array();
        $res['status'] = true;
        $res['code']   = 200;

        if (!is_null($data)) {
            $res['data'] = $data;
        }
        return $res;
    }

    public static function error($errorType, $errorMsg, $statusCode=null)
    {
        $res = array();
        $res['status'] = false;
        $res['code']   = 404;

        if (!is_null($statusCode)) {
            $res['code'] = $statusCode;
        }
        
        $res['errors'] = array();
        $res['errors']['error'] = $errorMsg;
        $res['errors']['type']  = $errorType;
        return $res;
    }

    public static function modelNotFound($errorType, $model)
    {
        $res = array();
        $res['status'] = false;
        $res['code']   = 400;

        $res['errors'] = array();
        $res['errors']['error'] = $model.' not found';
        $res['errors']['type']  = $errorType;

        return $res;
    }

    public static function validation($errors)
    {
        $res = array();
        $res['status'] = false;
        $res['code']   = 422;
        $res['type']   = 'Validation';
        $res['errors'] = $errors;

        return $res;
    }
}