<?php

namespace App\Helpers;

use Illuminate\Http\Response;

class Sender
{
    public static function success($message = null, $data = null, $statusCode = 200)
    {
        return self::send('success', $message, $data, $statusCode);
    }

    public static function error($message = null, $data = null, $statusCode = 500)
    {
        return self::send('error', $message, $data, $statusCode);
    }

    public static function send($status, $message = null, $data = null, $statusCode = 200)
    {
        $response = [
            'status' => $status,
        ];

        if ($message !== null) {
            $response['message'] = $message;
        }

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }
}