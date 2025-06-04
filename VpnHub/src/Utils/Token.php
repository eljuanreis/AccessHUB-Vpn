<?php

namespace App\Utils;

class Token
{
    public static function encryptToken($data)
    {
        $ivLength = openssl_cipher_iv_length($_ENV['CIPHER']);

        $iv = openssl_random_pseudo_bytes($ivLength);

        $encrypted = openssl_encrypt($data, $_ENV['CIPHER'], $_ENV['TOKEN'], 0, $iv);

        $result = base64_encode($iv . $encrypted);

        return $result;
    }

    public static function decryptToken($encryptedData)
    {
        $data = base64_decode($encryptedData);

        $ivLength = openssl_cipher_iv_length($_ENV['CIPHER']);

        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);

        return openssl_decrypt($encrypted, $_ENV['CIPHER'], $_ENV['TOKEN'], 0, $iv);
    }
}
