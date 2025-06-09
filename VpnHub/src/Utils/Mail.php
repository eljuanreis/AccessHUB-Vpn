<?php

namespace App\Utils;

use PHPMailer\PHPMailer\PHPMailer;
use App\Utils\Env;

class Mail
{
    public static function send($to, $subject, $body, $headers = '')
    {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = Env::get('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->Username = Env::get('MAIL_USERNAME');
            $mail->Password = Env::get('MAIL_PASSWORD');
            $mail->SMTPSecure = Env::get('MAIL_ENCRYPTION', 'tls');
            $mail->Port = Env::get('MAIL_PORT');

            $mail->setFrom(Env::get('MAIL_FROM_ADDRESS'), Env::get('MAIL_FROM_NAME'));
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            return $mail->send();
        } catch (\Throwable $e) {
            error_log('Erro ao enviar e-mail: ' . $e->getMessage());
            return false;
        }
    }
}
