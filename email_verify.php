<?php
// проверка наличия кода в ссылке
// сравнение ссылки с БД
// смена значения активации аккаунта
// авторизация
// перенаправление на профиль

session_start();
require_once 'includes/db.php';
if ($_REQUEST) {
    $verify_code = array_key_first($_REQUEST);

    // search code in DB
    try {
        $stmt = $pdo->prepare(SQL_VERIFY_CODE);
        $stmt->bindParam(':verify_code', $verify_code);
        $result = $stmt->execute();
        $user_count = $stmt->rowCount();

        // check the code
        if ($user_count > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // change account status
            $active = 1;
            $stmt = $pdo->prepare(SQL_ACTIVATE_USER);
            $stmt->bindParam(':active', $active);
            $stmt->bindParam(':login', $user['login']);
            $stmt->execute();

            // start session
            $_SESSION['user'] = $user;
            header('Location: user.php');
        } else echo "Не правильный код активации аккаунта.";
    } catch (PDOException $e) {
        echo '==== PDO Exception =====: ' . $e->getMessage();
    }
} else header('Location: ./index.php?verify_err');
