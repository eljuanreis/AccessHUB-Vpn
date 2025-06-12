<?php

namespace App\Utils;

use Exception;

class Downloader
{
    protected const DIRECTORY = '/etc/openvpn/client-configs/%s.zip';
    protected const CREATE_USER_SCRIPT = 'sudo /home/userlinux/create_user.sh %s';

    public static function download($userId)
    {
        $path = sprintf(static::DIRECTORY, $userId);

        if (!file_exists($path)) {
            throw new \Exception('Erro ao gerar o arquivo');
        }

        return $path;
    }

    public static function make($userId)
    {
        $output = null;
        $retval = null;

        $script = sprintf(static::CREATE_USER_SCRIPT, $userId);

        exec($script, $output, $retval);

        if ($retval === 0) {
             return sprintf(static::DIRECTORY, $userId);
        }

        return false;
    }
}
