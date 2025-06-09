<?php

namespace App\Controllers;

use App\Core\Request;
use App\Traits\ResponseTrait;
use App\Traits\ValidateTokenTrait;
use App\Utils\Downloader;

class DownloadController
{
    use ValidateTokenTrait;
    use ResponseTrait;

    protected Request $request;
    protected $data;

    public function make(Request $request)
    {
        $this->request = $request;

        if (!$this->validateRequest()) {
            return false;
        }

        try {
            $download =  Downloader::make($this->data->identifier);

            return $this->response(200, $download);
        } catch (\Throwable $th) {
            throw $th;
            return $this->response(400);
        }
    }

    public function download(Request $request)
    {
        $this->request = $request;

        if (!$this->validateRequest()) {
            return false;
        }

        try {
            $file = Downloader::download($this->data->identifier);
        } catch (\Throwable $th) {
            return $this->response(400);
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
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
