<?php

namespace App\Helper;

use App\Constants\HttpResponse;

class MyHelper
{
    public static function customResponse($content = [], $message = "", $status = HttpResponse::HTTP_OK, $headers = [])
    {
        $content = [
            "message" => $message,
            "data" => $content,
        ];
        return response($content, $status, $headers);
    }
}
