<?php

namespace App\Controllers;

use App\Core\Request;
use App\Traits\ResponseTrait;
use App\Traits\ValidateTokenTrait;
use App\Utils\Downloader;

class DownloadController 
{
    use ValidateTokenTrait, ResponseTrait;

    protected Request $request;
    protected $data;

    public function dispatch(Request $request)
    {
        $this->request = $request;

        if ($this->validateRequest()) {
            $this->download();
        }
    }

    public function download() 
    {
        try {
            $file = Downloader::download($this->request->input('user_id'));
        } catch (\Throwable $th) {
            return $this->response(400);
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Content-Length: ' . filesize($file));

        $fp = fopen($file, 'rb');

        while (!feof($fp)) {
            echo fread($fp, 8192);

            ob_flush();
            flush();
        }

        fclose($fp);
        exit;
    }
}