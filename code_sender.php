<?php
require_once 'includes/db.php';
session_start();
try {
    $verify_code = $_SESSION['user']['verify_code'];
    $verify_url = $_SERVER['HTTP_HOST'] . '/email_verify.php?' . $verify_code;

    $to = $_SESSION['user']['email'];
    $subject = 'Подтверждение регистрации';
    $message = 'Для активации вашего аккаунта нажмите на ссылку — ' . $verify_url;
    $headers = "MIME-Version: 1.0\r\n" . "Content-type: text/html; charset=utf-8\r\n" . "From: webmaster@goodman.com\r\n" . "Reply-To: webmaster@example.com\r\n";
    $mail_result = mail($to, $subject, $message, iconv ('utf-8', 'windows-1251', $headers));
} catch (Exception $e) {
    echo '=== EMAIL VERIFICATION ERROR: (code_sender.php) === ' . $e->getMessage();
}


// проверка отправки письма
if ($mail_result) {
//    header('Location: ./user.php');
    echo 'E-mail was send.';
} else echo 'Ошибка отправки письма с кодом подтверждения.';