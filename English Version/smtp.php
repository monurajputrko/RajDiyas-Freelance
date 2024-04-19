<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/PHPMailer.php';
require __DIR__ . '/PHPMailer/Exception.php';
require __DIR__ . '/PHPMailer/SMTP.php';

class SMTP {
    private function __construct() {}

    public static function send($host, $user, $password, $encryption, $port, $from, $fromName, $to, $subject, $body, $attachments, $attachmentFolder, $debug = 0) {
        $mail = new PHPMailer(true);
        try {
            $mail->CharSet = 'utf-8';
            $mail->Encoding = 'base64';
            $mail->setLanguage('de');
            $mail->SMTPDebug = $debug;
            $mail->isSMTP();
            $mail->Host = $host;
            $mail->SMTPAuth = true;
            $mail->Username = $user;
            $mail->Password = $password;
            $mail->SMTPSecure = $encryption;
            $mail->Port = $port;
            $mail->setFrom($from, $fromName);
            $mail->addAddress($to);
            $mail->addReplyTo($from);
            foreach($attachments as $attachment) {
                $mail->addAttachment($attachmentFolder . '/' . $attachment, $attachment);
            }

            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->send();
            return true;
        } catch(Exception $e) {
            return false;
        }
    }
}
