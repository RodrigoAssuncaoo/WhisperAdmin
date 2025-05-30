<?php

require_once '../../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendVerificationEmail($email, $token, $nome = '') {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = '3b4e0b89e5dac2';
        $mail->Password = '38b9529462f0f5';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        $mail->setFrom('geral@whisper.pt', 'Whisper');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Verificação de Email - Whisper';

        // Prepara variáveis que o template irá usar
        $firstName = explode(' ', trim($nome))[0];
        $referralLink = "http://WhisperAdmin.com/verify.php?token=$token";
        $referralCode = 'WHISPER15';
        $companyName = 'Whisper';

        // Inclui o template e captura o conteúdo HTML
        ob_start();
        include '../../../backend/email_template.php';
        $mail->Body = ob_get_clean();

        $mail->send();

        return [
            'status' => 'success',
            'data' => ['message' => 'Email enviado com sucesso'],
        ];
    } catch (Exception $e) {
        return [
            'status' => 'error',
            'message' => $e->getMessage()
        ];
    }
}
?>
