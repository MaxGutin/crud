<?php
/* This file contains:
 * Logout function, Session authentication,  Activate account check.
 */

session_start();

// Logout function
function logout()
{
    $_SESSION = array();
    unset($_SESSION[session_name()]);
    unset($_COOKIE[session_name()]);
    session_destroy();
    header('Location: index.php?left');
}
if (isset($_REQUEST['logout'])) {
    logout();
}

// проверяем что сессия принадлежит пользователю
if ($_SERVER['PHP_SELF'] != 'index.php' AND $_SERVER['PHP_SELF'] != 'singup.php') {  // проверяем что мы не на странице авторизации или регистрации

    if (empty($_SESSION['user']['login'])) header('Location: index.php?empty_session');     // если сессия отсутствует
    try {                                                                           // ищем совпадение данных сессии и БД
        $stmt = $pdo->prepare(SQL_LOGIN);
        $stmt->bindParam(':login', $_SESSION['user']['login']);
        $result = $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo '=== SESSION EXCEPTION ===  ' . $e->getMessage();
    }

    // Activate account check
    if ($user['active'] == 0) {
        echo 'Please activate your account';
        exit;
    }

    if ($user) {                                                        // если пользователь найден
        if ($_SESSION['user']['password'] !== $user['password']) {      // если пароли не сопадают, то ...
            header('Location: index.php?log-err');                // отправляем на авторизацию
        }
    }
}
