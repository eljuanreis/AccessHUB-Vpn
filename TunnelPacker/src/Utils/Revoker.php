<?php

namespace App\Utils;

class Revoker 
{
    protected const REMOVE_USER_SCRIPT = 'sudo sh /home/userlinux/revoke_user.sh %s';

    public static function revoke($userId)
    {
        $output = null;
        $retval= null;

        $script = sprintf(static::REMOVE_USER_SCRIPT, $userId);

        exec($script, $output, $retval);

        if ($retval === 0) {
            return true;
        }

        return false;
    }
}