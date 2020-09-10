<?php
require_once '../includes/db.php';
//try {
//    $stmt = $pdo->prepare(SQL_CREATE_TASKS_TABLE); // prepare — Подготавливает SQL-запрос к выполнению
//    $result = $stmt->execute(); // execute — Запускает подготовленный запрос на выполнение, сохраняем результат
//} catch (PDOException $e) {
//    $e->getMessage();
//}
//phpinfo();

//$test = 'Text';
//setcookie('COOKIE', $test);

//setcookie('COOKIE', '', time()-3600);
//unset($_COOKIE['COOKIE']);

//unset($_COOKIE['login']);
//unset($_COOKIE['token']);
//setcookie('login', '', time()-3600);
//setcookie('token', '', time()-3600);

session_start();
tester2($_SESSION);
tester2($_COOKIE);

//session_destroy();

