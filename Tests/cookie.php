<?php
session_start();
$test = 'COOKIE';
setcookie('COOKIE', $test);
$_SESSION['test'] = 'SESSION';