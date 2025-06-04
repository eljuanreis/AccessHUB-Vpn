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
            $this->data = Token::decryptToken($this->request->input('token'));
        } catch (\Throwable $th) {
            return $this->response(500);
        }

        return true;
    }
}
