<?php

namespace App\Traits;

use App\Utils\Token;

trait ValidateTokenTrait
{
    protected function validateRequest()
    {
        if (!$this->request->input('token')) {
            return $this->response(400);
        }

        try {
            if ($data = Token::decryptToken($this->request->input('token'))) {
                $this->data = json_decode($data);
            }
        } catch (\Throwable $th) {
            return $this->response(500);
        }

        return true;
    }
}
