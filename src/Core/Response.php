<?php

namespace Sangtech\Scandiweb\Core;

/**
 * Class Response
 * 
 * @author Aboubacar Sangare <abouba.sang@outlook.com>
 * @package Sangtech\Scandiweb\Core
 */

class Response
{
    public function setStatusCode(int $code): void
    {
        http_response_code($code);
    }

    public function send($message): void
    {
        if (is_string($message)) {
            echo $message;
        }
        if (is_array($message)) {
            echo json_encode($message);
        }
    }
}
