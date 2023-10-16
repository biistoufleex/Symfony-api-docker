<?php

namespace App\Controller\Http\Responses;

class Status
{
    private String $code;
    private ?String $message;

    public function __construct(?String $code, ?String $message)
    {
        $this->code = $code;
        $this->message = $message;
    }

    static function ok()
    {
        return new Status('OK', null);
    }

    static function error(String $message)
    {
        return new Status('KO', $message);
    }

    public function toArray()
    {
        return [
            'code' => $this->code,
            'message' => $this->message
        ];
    }
}