<?php

namespace App\Utils;

use Exception;

class Downloader
{
    protected const DIRECTORY = '/etc/openvpn/client-configs/%s.zip';
    protected const CREATE_USER_SCRIPT = 'sudo sh /home/userlinux/create_user.sh %s';

    public static function download($userId)
    {
        // return 'C:\Users\dti\Desktop\Nova pasta\teste.zip';

        $path = sprintf(static::DIRECTORY, $userId);

        if (!file_exists($path) && !static::make($userId)) {
            throw new \Exception('Erro ao gerar o arquivo');
        }

        return $path;
    }

    protected static function make($userId)
    {
        $output = null;
        $retval = null;

        $script = sprintf(static::CREATE_USER_SCRIPT, $userId);

        exec($script, $output, $retval);

        if ($retval === 0) {
            return true;
        }

        return false;
    }
}
