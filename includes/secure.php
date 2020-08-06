<?php
require_once 'db.php';
session_start();

// Logout function
if ( isset($_REQUEST['logout']) ) {
    $_SESSION = array();
    unset($_COOKIE[session_name()]);
    session_destroy();
} /* else echo 'FAIL LOGOUT!';*/

// Checking to login session
$loginPageAddress = substr($_SERVER['SCRIPT_NAME'], -10, 10); // читаем последние 10 символов URL (singin.php)
if ( $loginPageAddress !== 'singin.php' and $loginPageAddress !== 'singup.php' ) //  если мы не на странице авторизации или регистрации, то ...
{
    try
    {
        $stmt = $pdo->prepare(SQL_LOGIN);
        $stmt->bindParam(':login', $_SESSION['logged_user']['login']);
        $stmt->bindParam(':password', $_SESSION['logged_user']['password']);
        $result = $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ( !$user )
        {
            header('Location: singin.php?log-err');
        }
    }
    catch (PDOException $e)
    {
        echo $e->getMessage();
    }
}