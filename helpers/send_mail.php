<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../config_mail.php";

function sendVerificationEmail(string $toEmail, string $token): array {
    global $MAIL_HOST,$MAIL_PORT,$MAIL_SECURE,$MAIL_USER,$MAIL_PASS,$MAIL_FROM,$MAIL_FROM_NAME,$APP_URL;

    $verifyLink = $APP_URL . "/verify.php?email=" . urlencode($toEmail) . "&token=" . urlencode($token);

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $MAIL_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = $MAIL_USER;
        $mail->Password   = $MAIL_PASS;
        $mail->SMTPSecure = $MAIL_SECURE; // 'tls' atau 'ssl'
        $mail->Port       = $MAIL_PORT;

        // jika di lokal kadang SSL self-signed:
        $mail->SMTPOptions = [
          'ssl' => ['verify_peer' => false, 'verify_peer_name' => false, 'allow_self_signed' => true]
        ];

        $mail->setFrom($MAIL_FROM, $MAIL_FROM_NAME);
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = 'Verifikasi Akun Net Sun Power';
        $mail->Body = "
            <p>Terima kasih telah mendaftar di <b>{$MAIL_FROM_NAME}</b>.</p>
            <p>Silakan klik tautan berikut untuk memverifikasi akun Anda:</p>
            <p><a href='{$verifyLink}'>{$verifyLink}</a></p>
            <hr>
            <p>Jika tautan tidak bisa diklik, salin-tempel URL ini ke browser:</p>
            <code>{$verifyLink}</code>
        ";

        $mail->send();
        return [true, null];
    } catch (Exception $e) {
        return [false, $mail->ErrorInfo];
    }
}
