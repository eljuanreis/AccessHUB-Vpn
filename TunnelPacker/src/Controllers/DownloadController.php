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
            $download =  Downloader::make($this->data);

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
            $filepath = Downloader::download($this->data);
        } catch (\Throwable $th) {
            return $this->response(400);
        }

        $fp = fopen($filepath, 'rb');

        if (!file_exists($filepath)) {
            return $this->response(404);
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="download.zip"');
        header('Content-Length: ' . filesize($filepath));

        $fp = fopen($filepath, 'rb');

        if (!$fp) {
            return $this->response(500);
        }

        while (!feof($fp)) {
            echo fread($fp, 8192);
            ob_flush();
            flush();
        }

        fclose($fp);
        exit;
    }

}
