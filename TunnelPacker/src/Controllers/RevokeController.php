<?php

namespace App\Controllers;

use App\Core\Request;
use App\Traits\ResponseTrait;
use App\Traits\ValidateTokenTrait;
use App\Utils\Revoker;

class RevokeController 
{
    use ValidateTokenTrait, ResponseTrait;

    protected Request $request;
    protected $data;

    public function dispatch(Request $request)
    {
        $this->request = $request;

        if ($this->validateRequest()) {
            $this->revoke();
        }
    }

    public function revoke() 
    {
        try {
            $exec = Revoker::revoke($this->request->input('user_id'));
        } catch (\Throwable $th) {
            return $this->response(400);
        }

        if (!$exec) {
            return $this->response(500);
        }

        return $this->response(200);
    }
}