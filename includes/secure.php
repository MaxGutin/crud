<?php
session_start();

// Logout function
if (isset($_REQUEST['logout'])) {
    $_SESSION = array();
    unset($_COOKIE[session_name()]);
    session_destroy();
    header('Location: singin.php?left');
}
// todo Почини проверку на сессию
// Checking to login session
//if ($_SESSION['logged_user']['login'] == '') { // если сессия пустая, то отправляем на авторизацию
//    header('Location: singin.php?empty_session');
//}

// проверяем что мы не на странице авторизации или регистрации
$loginPageAddress = substr($_SERVER['SCRIPT_NAME'], -10, 10); // читаем последние 10 символов URL (singin.php)
if ( $loginPageAddress !== 'singin.php' and $loginPageAddress !== 'singup.php' ) {

    // ищем совпадение данных сессии и БД
    try {
        $stmt = $pdo->prepare(SQL_LOGIN);
        $stmt->bindParam(':login', $_SESSION['logged_user']['login']);
        $result = $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo '=== SESSION EXCEPTION ===  ' . $e->getMessage();
    }
    if ($user) {
        // если пользователь не найден и пароли не сопадают, то отправляем на авторизацию
        if ($_SESSION['logged_user']['password'] === $user['password']) {
            // всё ок
        } else header('Location: singin.php?log-err');
    }
}
