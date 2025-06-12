<?php

namespace App\Utils;

class Token
{
    public static function encryptToken($data)
    {
        $cipher = $_ENV['CIPHER'];
        $key = $_ENV['TOKEN'];

        $ivLength = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);

        $encrypted = openssl_encrypt($data, $cipher, $key, OPENSSL_RAW_DATA, $iv);

        if ($encrypted === false) {
            throw new \Exception("Falha ao criptografar.");
        }

        return base64_encode($iv . $encrypted);
    }

    public static function decryptToken($encryptedData)
    {
        $cipher = $_ENV['CIPHER'];
        $key = $_ENV['TOKEN'];

        $data = base64_decode($encryptedData, true);
        if ($data === false) {
            throw new \Exception("Base64 inválido.");
        }

        $ivLength = openssl_cipher_iv_length($cipher);
        $iv = substr($data, 0, $ivLength);
        $encrypted = substr($data, $ivLength);

        $decrypted = openssl_decrypt($encrypted, $cipher, $key, OPENSSL_RAW_DATA, $iv);

        if ($decrypted === false) {
            throw new \Exception("Falha ao descriptografar.");
        }

        return $decrypted;
    }
}
