<?php
$dsn = 'mysql:dbname=crud;host=localhost'; // Создаём переменные с данными для подключения.
$db_user = 'root';
$db_password = 'root';
try {
    $pdo = new PDO($dsn, $db_user, $db_password); // Инициализируем объект PDO и вставляем данные для подключения
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Настраиваем обработку ошибок
} catch (PDOException $e) {
    print 'Error connection: ' . $e->getMessage(); // выводим ошибки подключения
}

// Тестировщики
function tester1($a)
{
    echo "<pre>";
    print_r($a);
    echo '</pre>';
}
function tester2($a)
{
	echo "<pre>";
	var_dump($a);
	echo '</pre>';
}

// Константы с SQL выражениями
const SQL_LOGIN = '
    SELECT id, role, full_name, login, password FROM users WHERE login = :login AND password = :password
';

const SQL_CREATE_USERS_TABLE = '
	CREATE TABLE IF NOT EXISTS users (
		id INT UNSIGNED AUTO_INCREMENT NOT NULL,
		role VARCHAR(50) NOT NULL,
		full_name VARCHAR(255) NOT NULL,
		login VARCHAR(50) NOT NULL,
		password VARCHAR(50) NOT NULL,
		PRIMARY KEY (id)
	)
';

const SQL_INSERT_USER = '
    INSERT INTO users (role, full_name, login, password) VALUE (?,?,?,?)
';

const SQL_GET_USER = '
    SELECT id, role, full_name, login, password FROM users WHERE id = :id
';

const SQL_GET_USERS = '
    SELECT * FROM users
';

const SQL_UPDATE_USER_BY_ID = '
    UPDATE users SET
      role = :role,
      full_name = :full_name,
      login = :login, 
      password = :password
    WHERE
      id = :id
';

const SQL_DELETE_USER = 'DELETE FROM users WHERE id = :id';
