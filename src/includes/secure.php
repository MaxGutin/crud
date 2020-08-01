<?php
require_once 'includes/db.php';
session_start();

// Logout function
if ( isset($_REQUEST['logout']) ) {
    $_SESSION = array();
    unset($_COOKIE[session_name()]);
    session_destroy();
} /* else echo 'FAIL LOGOUT!';*/

// Checking to login session
$loginPageAddress = substr($_SERVER['SCRIPT_NAME'], -9, 9);
if ( $loginPageAddress !== 'login.php' ) {
//  URL no login.php
    try {
        $stmt = $pdo->prepare(SQL_LOGIN);
        $stmt->bindParam(':login', $_SESSION['logged_user']['login']);
        $stmt->bindParam(':password', $_SESSION['logged_user']['password']);
        $result = $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ( !$user ) {
//          echo 'Сессия не активна!';
            header('Location: login.php');
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
