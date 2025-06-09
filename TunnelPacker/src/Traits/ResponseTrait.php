<?php

namespace App\Traits;

trait ResponseTrait
{
    protected function response(int $status, string $body = "")
    {
        switch ($status) {
            case 200:
                http_response_code(200);
                echo $body;
                break;
            case 400:
                http_response_code(404);
                echo "404 Not Found";
                break;

            case 500:
                http_response_code(500);
                echo "Algo deu errado";
                break;
        }
    }
}
