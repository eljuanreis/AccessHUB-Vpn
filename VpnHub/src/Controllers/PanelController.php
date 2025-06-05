<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Services\LoginService;
use App\Utils\Route;
use App\Utils\Session;
use App\Utils\SessionFlash;
use App\Validators\Web\LoginValidator;

class PanelController
{
    public function show()
    {
        return View::make('panel/dashboard');
    }
}
