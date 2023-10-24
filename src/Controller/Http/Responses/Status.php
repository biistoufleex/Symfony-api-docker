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

    static function ok(): Status
    {
        return new Status('OK', null);
    }

    static function error(String $message): Status
    {
        return new Status('KO', $message);
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'message' => $this->message
        ];
    }
}
